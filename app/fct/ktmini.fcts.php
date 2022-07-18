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
    $sql = ' SELECT * FROM ' . $con->credentials['prefix'] . $con->table .' ORDER  BY id DESC';

    if ($con)
    {

        $req = $con->db->query($sql);

        $list = array();
        $i = 0;

        foreach ($req as $data) {
            $list [$i] = array(
                'id' => $data ['id'],
                'title' => $data ['title'],
                'date' => $data ['date'],
                'teaser' => $data ['teaser'],
                'article' => $data ['article'],
                'minithumb' => $data ['minithumb']
            );
            $i ++;
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

    if ($con)
    {
        $req = $con->db->query($sql);
        return $req->fetch(PDO::FETCH_ASSOC);
    }else
        return false;
}

function addArticle($datas)
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
                $rtAF = addFile($jointFile, $id['lastid']);
                
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

function addFile($filename, $content_id)
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
    
    if($rt == true)
        return true;
    else
        return false;    
    
}

