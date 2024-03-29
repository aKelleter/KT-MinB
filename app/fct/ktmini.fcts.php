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
    $sql = ' SELECT * FROM ' . $con->table;

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
 * Retourne le tableau de toutes les article (pour l'auteur, ce compris les non publiées)
 * 
 * @return array
 */
function loadAllArticles()
{
    $con = new Model('content');
    $sql = ' SELECT * FROM ' . $con->table .' ORDER  BY id DESC';

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
                'icon' => $data ['icon'],
                'slug' => $data ['slug'],
                'published' => $data ['published'],
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
 * Retourne le tableau de toutes les icônes 
 * 
 * @return array
 */
function loadAllIcons()
{
    $con = new Model('icon');
    $sql = ' SELECT * FROM ' . $con->table .' ORDER BY icon ASC';

    if ($con)
    {
        $req = $con->db->query($sql);

        $list = array();
        $datas = $req->fetchall();
          
        foreach ($datas as $data)
        {
            $list[] = array(
                'id' => $data ['id'],
                'icon' => $data ['icon'],
                'filename' => $data ['filename']
            );
        }
        
        return $list;

    }else
        return false;
}


/**
 * Retourne le tableau des articles publiés uniquement (pour le visiteur)
 * 
 * @return array
 */
function loadPublishedArticles()
{
    $con = new Model('content');
    $sql = ' SELECT * FROM ' . $con->table .' WHERE published = 1 ORDER  BY id DESC';

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
                'icon' => $data ['icon'],
                'slug' => $data ['slug'],
                'published' => $data ['published'],
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
    $sql = 'SELECT * FROM ' . $con->table . ' WHERE id='.$id;

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
    $sql = 'SELECT * FROM ' . $con->table . ' WHERE content_id='.$id;

    if($con)
    {
        $req = $con->db->query($sql);
        return $req->fetchall(PDO::FETCH_ASSOC);
    }else
        return false;
}

/**
 * Retourne les infos du fichier passé en paramètre
 * 
 * @param type $id
 * @return boolean
 */
function getJointFile($id)
{
    $con = new Model('files');
    $sql = 'SELECT * FROM ' . $con->table . ' WHERE id='.$id;

    if($con)
    {
        $req = $con->db->query($sql);
        return $req->fetch(PDO::FETCH_ASSOC);
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

            $rtUP = AKUploadFile($index, $nameFile, PATH_FILE, MAXSIZE, $extensions);

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
            INTO ".$con->table." (
            title,
            date,
            teaser,
            icon,
            article,
            slug                    
            ) 
            VALUES(
            :title,
            :date,
            :teaser,
            :icon,
            :article,
            :slug
            )
        ");

        $rt['status'] = $article->execute([
            "title" => htmlentities($title_article),
            "date"  => date ('Y-m-d'),
            "teaser" => htmlentities(AKFiltre($teaser_article)),
            "icon" => $icon_article,
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
    $rt = array("status" => false, "type" => "info", "code" => "0000", "msg" => "");
    $flagAddArticleInDB = null;
    $jointFile = $rtAF = $rtUP = $filesList = array();
        
    extract($datas);
    
    // Vérification de la présence des champs obligatoires
    if(empty($title_article) || empty($teaser_article) || empty($content_article))
    {
        $rt['status'] = false;
        $rt['type'] = 'danger';
        $rt['code'] = '0001';
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
            INTO ".$con->table." (
            title,
            date,
            teaser,
            icon,
            article,
            slug,
            published
            ) 
            VALUES(
            :title,
            :date,
            :teaser,
            :icon,
            :article,
            :slug,
            :published
            )
        ");

        $rt['status'] = $article->execute([
            "title" => AKFiltre($title_article),
            "date"  => date ('Y-m-d'),
            "teaser" => nl2br(htmlentities($teaser_article)),
            "icon" => $icon_article,
            "article" => nl2br(htmlentities($content_article)),
            "slug" => AKSlugify($title_article),
            "published" => (isset($published_article) && $published_article == 'on')? 1 : 0
        ]);
        
        $flagAddArticleInDB =($rt['status'])? true : false; 
       
        // Si l'insert de l'article s'est bien déroulé
        if($flagAddArticleInDB)
        {
            // On va chercher l'ID du nouvel article
            $sql = "SELECT LAST_INSERT_ID() as lastid FROM $con->table";
            $req = $con->db->query($sql);
            $id = $req->fetch(PDO::FETCH_ASSOC);    
            
            
            // Update de la date de mise à jour du contenu du site
            $con = new Model('params');            
            $sqllu = "SELECT * FROM $con->table WHERE `key`='lastupdate'";
            $reqlu = $con->db->prepare($sqllu);    
            $reqlu->execute();
            $idlu = $reqlu->fetchAll(PDO::FETCH_ASSOC);
            //DEBUG//AKPrintR($idlu);             
            if(isset($idlu[0]['id']) && !empty($idlu[0]['id']))
            {
                $sql = "UPDATE $con->table SET value=:date WHERE id=:id";
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
                    
                    $rtUP[$key] = AKUploadFile($index, $nameFile, PATH_FILE, MAXSIZE, $extensionsAuthorized);
                    
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
                    if(isset($rtUP[$key]))
                        $rtAF[$key] = $rtUP[$key];    
                    else
                        $rtAF[$key] = true;    
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

/**
 * Ajoute le nomdu fichier DB
 * @param type $filename
 * @param type $content_id
 * @return boolean
 */
function addFileInDB($filename, $content_id)
{
    $rt = false;

    // Ajout du fichier joint
    $con = new Model('files');
    $file = $con->db->prepare("
        INSERT
        INTO ".$con->table." (
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

/**
 * Mise à jour d'un article
 * 
 * @param type $datas
 * @return string
 */
function updateArticle($datas)
{
    $rt = array("status" => false, "type" => "info", "code" => "0000", "msg" => "");
    $flagUpdArticleInDB = null;
    $jointFile = $rtAF = $rtUP = $rtDF = $filesList = array();
        
    extract($datas);
    
    // Vérification de la présence des champs obligatoires
    if(empty($title_article) || empty($teaser_article) || empty($content_article))
    {
        $rt['status'] = false;
        $rt['type'] = 'danger';
        $rt['code'] = '0001';
        $rt['msg'] = "The title, teaser and article are mandatory";
    }else
        $rt['status'] = true;
        
    // Si les champs obligatoires sont présents
    if($rt['status'])
    {
        //DEBUG// AKPrintR($_FILES, 'FILES'); die();
        
    
        // Mise à jour de l'article en DB
        $con = new Model('content');
        $article = $con->db->prepare(
            "UPDATE ".$con->table." ".
            "SET ".
            "title = :title, ".
            "teaser = :teaser, ".
            "icon = :icon, ".
            "article = :article, ".
            "slug = :slug, ".
            "published = :published ".
            "WHERE id = :id"    
        );

        $rt['status'] = $article->execute([
            "title" => AKFiltre($title_article),            
            "teaser" => AKFiltre($teaser_article),
            "icon" => $icon_article,
            "article" => htmlentities($content_article),
            "slug" => AKSlugify($title_article),
            "id" => $id_article,
            "published" => (isset($published_article) && $published_article == 'on')? 1 : 0
        ]);
        
        $flagUpdArticleInDB = ($rt['status'])? true : false; 
        
        // Si ma mise à jour de l'article s'est bien déroulée
        if($flagUpdArticleInDB === true)
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
                    
                    $rtUP[$key] = AKUploadFile($index, $nameFile, PATH_FILE, MAXSIZE, $extensionsAuthorized);
                    
                    if($rtUP[$key] === true){                           
                        $jointFile[$key] = $nameFile;
                    }else{                        
                        $jointFile[$key] = $rtUP[$key];                
                    }
                    
                }else
                    $jointFile[$key] = null;
            }
            
            // 2. Ajoute le ou les fichiers joints en DB
            //DEBUG// AKPrintR($rtUP); die();
            foreach($jointFile as $key => $filename)
            {
                if((isset($filename) && !empty($filename)) 
                   && !AKStrContainsStr($filename, 'UFERR') 
                   && !AKStrContainsStr($filename, 'UFSIZE') 
                   && !AKStrContainsStr($filename, 'UFEXT')
                   && !AKStrContainsStr($filename, 'UFUP'))
                {                    
                    if(addFileInDB($filename, $id_article))
                       $rtAF[$key] = true;     
                    else
                       $rtAF[$key] = 'AFERR';     
                }else{
                    if(isset($rtUP[$key]))
                        $rtAF[$key] = $rtUP[$key];    
                    else
                        $rtAF[$key] = true;
                }
            }
            
            // 3. Suppression éventuelle des anciens fichiers
            if(isset($existingfiles_article) && is_array($existingfiles_article) && !empty($existingfiles_article))
            {                
                foreach($existingfiles_article as $id)
                {
                  $rtDF[] = procDeleteJointFile($id);  
                }
            }
            
            // 4. Analyser la situation pour le retour du message
            if($flagUpdArticleInDB === true)
            {                
                $rt['msg'] = "Update article done";
                $rt['type'] = "success";

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
            
            // Check la suppression des fichiers
            if(AKAllIsTrue($rtDF))
            {
                $rt['msg'] .= ' - The selected files have been deleted';
            }
            
            
            
            
        }
        
        $article->closeCursor();
        
    } 
    
    return $rt;
}


/**
 * Procédure du suppression du fichier($id) passé en paramètre
 * 
 * @param type $id
 * @return boolean
 */
function procDeleteJointFile($id)
{
    $file = null;
    $delDB = null;
    $delSYS = null;
    
    $file = getJointFile($id);
    //AKPrintR ($file); die();
    
    $delDB = deleteJointFileInDB($id);
    
    if($delDB)
       $delSYS = deleteJointFileInSYS(PATH_FILE, $file['filename']);
    
    if($delDB && $delSYS)
        return true;
    else
        return false;
    
}

/**
 * Suppression du fichier joint dans la DB
 * 
 * @param type $id
 * @return boolean
 */
function deleteJointFileInDB($id)
{
    
    $con = new Model('files');
    
    $delete = $con->db->prepare('DELETE FROM ' . $con->table . ' WHERE id = :id');
    $delete->bindParam(':id', $id, PDO::PARAM_INT); 
    $proc = $delete->execute();
    
    $delete->closeCursor();

    if($proc)
        return true;
    else
        return false;
    
}

/**
 * Suppression du fichier joint dans le système de fichier
 * 
 * @param type $path
 * @param type $filename
 * @return boolean
 */
function deleteJointFileInSYS($path, $filename)
{
    if(unlink(ABSPATH.$path.$filename))
        return true;
    else
        return false;
}


