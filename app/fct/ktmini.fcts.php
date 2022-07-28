<?php

/**
 * Cette fonction charge tous les enregistrements
 * de la table des paramètres (params)
 *
 * @return array
 */
function loadParams()
{
    $con = new Model('params');
    $sql = ' SELECT * FROM ' . $con->credentials['prefix'] . $con->table;

    if ($con) {

        $req = $con->db->query($sql);

        $list = array();
        $i = 0;

        foreach ($req as $data) {
            $list [$i] = array(
                'id' => $data ['id'],
                'key' => $data ['key'],
                'value' => $data ['value']
            );
            $i ++;
        }

        //$list = json_encode( $list );

        return $list;
    } else
        return false;
}

/**
 * Retourne le tableau des news
 * 
 * @return array
 */
function loadAllArticles()
{
    $con = new Model('content');
    $sql = ' SELECT * FROM ' . $con->credentials['prefix'] . $con->table .' WHERE published = 1 ORDER  BY id DESC';

    if ($con)
    {

        $req = $con->db->query($sql);

        $list = array();
        $datas = $req->fetchall();
        $pos = sizeof($datas);
        $i = 0;          
        foreach ($datas as $data)
        {
            $list [$i] = array(
                'id' => $data ['id'],
                'title' => $data ['title'],
                'date' => $data ['date'],
                'teaser' => $data ['teaser'],
                'article' => $data ['article'],
                'minithumb' => $data ['minithumb'],
                'position' => $pos
            );
            $pos --;
            $i++;
        }
        return $list;

    }else
        return false;
}

/**
 * Retourne l'article dont l'ID est passé en paramètre
 * 
 * @return array
 */
function loadArticle($id)
{
    $con = new Model('content');
    $sql = 'SELECT * FROM ' . $con->credentials['prefix'] . $con->table . ' WHERE id='.$id;

    if($con)
    {
        $req = $con->db->query($sql);
        return $req->fetch(PDO::FETCH_ASSOC);
    }else
        return false;
}

/**
 * Retourne le ou les fichiers joints associés à l'article passé en paramètre
 * 
 * @param type $id
 * @return boolean
 */
function loadJointFiles($id)
{
    $con = new Model('files');
    $sql = 'SELECT * FROM ' . $con->credentials['prefix'] . $con->table . ' WHERE content_id='.$id;

    if($con)
    {
        $req = $con->db->query($sql);
        return $req->fetchall(PDO::FETCH_ASSOC);
    }else
        return false;
}

/**
 * Ajoute un article avec un seul fichier joint
 * 
 * @param type $datas
 * @return string
 */
function _addArticle($datas)
{
    $rt = array("status" => false, "msg" => "");
    $lastid = null;
    $rtAF = false;
    $rtUP = null;
  
    extract($datas);
    
    if(empty($title_article) || empty($teaser_article) || empty($content_article))
    {
        $rt['status'] = false;
        $rt['msg'] = "The title, teaser and article are mandatory";
    }else
        $rt['status'] = true;
        
    
    if($rt['status'])
    {
        //DEBUG// AKPrintR($_FILES); die();

        // Traitement du fichier joint   
        if(isset($_FILES['file_article']) && !empty($_FILES['file_article']['name']))
        {

            $index = 'file_article';
            $extensions = unserialize(ALLOWEXT);
            $nameFile = AKSlugify($_FILES['file_article']['name']).'-'.GENrands(5);

            $rtUP = AKUploadFile($index, $nameFile, PATHFILE, MAXSIZE, $extensions);

            if ($rtUP == false){
                $jointFile = '';
                $rt['status'] = false;
            }elseif($rtUP){                
                $jointFile = $nameFile;
                $rt['status'] = true;
            }    

        }else{
            $jointFile = 'no-file';
        }
        
        $con = new Model('content');
        $article = $con->db->prepare("
            INSERT
            INTO ".$con->credentials['prefix'].$con->table." (
            title,
            date,
            teaser,
            minithumb,
            article,
            slug                    
            ) 
            VALUES(
            :title,
            :date,
            :teaser,
            :minithumb,
            :article,
            :slug
            )
        ");

        $rt['status'] = $article->execute([
            "title" => htmlentities($title_article),
            "date"  => date ('Y-m-d'),
            "teaser" => htmlentities(AKFiltre($teaser_article)),
            "minithumb" => $minithumb_article,
            "article" => htmlentities(AKFiltre($content_article)),
            "slug" => AKSlugify($title_article),
        ]);

        // Si pas d'erreur
        if($rt['status'])
        {
            $rt['msg'] = 'Your new article has been created';   
            
            if($rtUP)
            {
                // Capture de l'ID du nouvel article
                $sql = "SELECT LAST_INSERT_ID() as lastid FROM content";
                $req = $con->db->query($sql);
                $id = $req->fetch(PDO::FETCH_ASSOC);
            
                // Ajoute le fichier joint
                $rtAF = addFileInDB($jointFile, $id['lastid']);
                
                if($rtAF)
                {
                    if($rtUP == null)
                        $rt['msg'] = 'Your article has been created';
                    elseif($rtUP == true)
                        $rt['msg'] = 'Your article has been created and the attachment could be uploaded to the server';
                    elseif($rtUP == false)
                        $rt['msg'] = 'Your article has been created but the attachment could not be uploaded to the server';
                }
            }   
            
        }else
            $rt['msg'] = 'The system encountered a problem when creating the article';

        $article->closeCursor();        
    }
    
    return $rt;
}

/**
 * Ajoute un article avec un ou plusieurs fichiers joints
 * 
 * @param type $datas
 * @return string
 */
function addArticleMulti($datas)
{
    $rt = array("status" => false, "msg" => "");
    $flagAddArticleInDB = null;
    $jointFile = $rtAF = $rtUP = $filesList = array();
        
    extract($datas);
    
    // Vérification de la présence des champs obligatoires
    if(empty($title_article) || empty($teaser_article) || empty($content_article))
    {
        $rt['status'] = false;
        $rt['msg'] = "The title, teaser and article are mandatory";
    }else
        $rt['status'] = true;
        
    // Si les champs obligatoires sont présents
    if($rt['status'])
    {
        //DEBUG// AKPrintR($_FILES, 'FILES'); die();
        // Ajoute l'article en DB
        $con = new Model('content');
        $article = $con->db->prepare("
            INSERT
            INTO ".$con->credentials['prefix'].$con->table." (
            title,
            date,
            teaser,
            minithumb,
            article,
            slug,
            published
            ) 
            VALUES(
            :title,
            :date,
            :teaser,
            :minithumb,
            :article,
            :slug,
            :published
            )
        ");

        $rt['status'] = $article->execute([
            "title" => AKFiltre($title_article),
            "date"  => date ('Y-m-d'),
            "teaser" => nl2br(htmlentities($teaser_article)),
            "minithumb" => $minithumb_article,
            "article" => nl2br(htmlentities($content_article)),
            "slug" => AKSlugify($title_article),
            "published" => (isset($published_article) && $published_article == 'on')? 1 : 0
        ]);
        
        $flagAddArticleInDB =($rt['status'])? true : false; 
       
        // Si l'insert de l'article s'est bien déroulé
        if($flagAddArticleInDB)
        {
            // On va chercher l'ID du nouvel article
            $sql = "SELECT LAST_INSERT_ID() as lastid FROM content";
            $req = $con->db->query($sql);
            $id = $req->fetch(PDO::FETCH_ASSOC);    
            
            
            // Update de la date de mise à jour du contenu du site
            $con = new Model('params');            
            $sqllu = "SELECT * FROM params WHERE `key`='lastupdate'";
            $reqlu = $con->db->prepare($sqllu);    
            $reqlu->execute();
            $idlu = $reqlu->fetchAll(PDO::FETCH_ASSOC);
            //DEBUG//AKPrintR($idlu);             
            if(isset($idlu[0]['id']) && !empty($idlu[0]['id']))
            {
                $sql = "UPDATE params SET value=:date WHERE id=:id";
                $req = $con->db->prepare($sql);
                $data = [
                'date' => date("d/m/Y"),                
                'id' => $idlu[0]['id']
                ];
                $req->execute($data);
            } 
        }
        
        // Si on a un ID
        if(isset($id['lastid']) && !empty($id['lastid']))
        {
            // 1. Upload du ou des fichiers joints
            foreach($_FILES as $key => $file)
            {
                if(!empty($file['name']))
                {
                    $index = $key;
                    $extensionsAuthorized = unserialize(ALLOWEXT);
                    $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
                    $nameFileWithoutExt = AKSlugify(AKDeleteFileExtension($_FILES[$key]['name']));
                    $nameFile = $nameFileWithoutExt.'-'.GENrands(5).'.'.$ext;
                    $filesList[$key] = $nameFile; //Utilisé pour la gestion des erreurs
                    
                    $rtUP[$key] = AKUploadFile($index, $nameFile, PATHFILE, MAXSIZE, $extensionsAuthorized);
                    
                    if($rtUP[$key] === true){                           
                        $jointFile[$key] = $nameFile;
                    }else{                        
                        $jointFile[$key] = $rtUP[$key];                
                    }
                    
                }else
                    $jointFile[$key] = null;
            }
            
            // 2. Ajoute le ou les fichiers joints en DB
            foreach($jointFile as $key => $filename)
            {
                if((isset($filename) && !empty($filename)) 
                   && !AKStrContainsStr($filename, 'UFERR') 
                   && !AKStrContainsStr($filename, 'UFSIZE') 
                   && !AKStrContainsStr($filename, 'UFEXT')
                   && !AKStrContainsStr($filename, 'UFUP'))
                {                    
                    if(addFileInDB($filename, $id['lastid']))
                       $rtAF[$key] = true;     
                    else
                       $rtAF[$key] = 'AFERR';     
                }else{                    
                    $rtAF[$key] = $rtUP[$key];    
                }
            }
        }
        
            /*
            //DEBUG
            AKPrintR($flagAddArticleInDB, 'FLAG ADD ARTICLE');
            AKPrintR($jointFile, 'JOINTFILE');
            AKPrintR($rtUP, 'RTUP');
            AKPrintR($rtAF, 'RTAF');
            */
        
        // Analyse de la situation pour le retour du message
        if($flagAddArticleInDB === true)
        {
            $rt['msg'] = 'Your article has been created';
            $rt['type'] = 'success';
            
            if(AKAllIsTrue($rtUP) === true && !empty($_FILES['file_article1']['name']))
            {
                $rt['msg'] .= ' and the attachment could be uploaded correctly to the server';
                $rt['type'] = 'success';
            }elseif(!empty($_FILES['file_article1']['name'])){
                $rt['msg'] .= ' but one or several the attachment could not be uploaded to the server'.' : <p>'.AKArrayInline($rtUP, 'ERROR', $filesList).'</p>';
                $rt['type'] = 'warning';
            }
            
        }else{
            $rt['msg'] = 'The system encountered a problem when creating the article';
            $rt['type'] = 'danger';
        }
        
        if(!AKAllIsTrue($rtAF) && !empty($_FILES['file_article1']['name']))
        {
            $rt['msg'] .= '<strong>'.'Some filenames could not be added in DB'.'</strong>';
        }
              
        $article->closeCursor();        
    }
    
    return $rt;
}

function addFileInDB($filename, $content_id)
{
    $rt = false;

    // Ajout du fichier joint
    $con = new Model('files');
    $file = $con->db->prepare("
        INSERT
        INTO ".$con->credentials['prefix'].$con->table." (
        filename,
        content_id              
        ) 
        VALUES(
        :filename,
        :content_id
        )
    ");

    $rt = $file->execute([
        "filename" => $filename,
        "content_id"  => $content_id
    ]);
    
    $file->closeCursor();
    
    if($rt === true)
        return true;
    else
        return false;      
}

