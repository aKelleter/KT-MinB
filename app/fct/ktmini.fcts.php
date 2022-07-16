<?php


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
  
    extract($datas);
    
    if(empty($titre-article) || empty($teaser-article) || empty($content-article))
    {
        $rt['status'] = false;
        $rt['msg'] = T_("The title, teaser and article are mandatory");
    } 
    
    if($rt['status'])
    {
        // Traitement du fichier joint   
        if(isset($_FILES['file-article']) && !empty($_FILES['file-article']) && !empty($_FILES['file-article']['name'])) {

            $index = 'file-article';
            $extensions = unserialize(ALLOWEXT);
            $nameFile = $_FILES['file-article']['name'];

            $statusUpload = @AKUploadFile($index, $nameFile, PATHFILE, MAXSIZE, $extensions);

            if ($statusUpload == false){
                $jointFile = '';
                $process['status'] = false;
                $process['msg'] = T_("The system encountered a problem when saving the attached file"); 
            }else
                $jointFile = $nameFile;    

        }else
            $jointFile = '';
        
        $con = new Model('content');
        $article = $con->db->prepare("
            INSERT
            INTO
            content(
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
            "title" => htmlentities($titre-article),
            "date"  => date ('Y-m-d'),
            "teaser" => htmlentities(AKFiltre($teaser-article)),
            "minithumb" => $minithumb-article,
            "article" => htmlentities(AKFiltre($content-article)),
            "slug" => AKSlugify($titre-article),
        ]);

        // Si pas d'erreur
        if($rt['status']){
            $rt['msg'] = 'Your new article is created';   
            
            // Insertion du fichier joint
            $sql = "SELECT LAST_INSERT_ID()FROM content";
            $req = $con->db->query($sql);
            $lastid = $req->fetch(PDO::FETCH_ASSOC);
            
        }else
            $rt['msg'] = 'The system encountered a problem when creating the article';

        $article->closeCursor();
        
    }
    
    return $rt;
}

