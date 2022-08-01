<?php

/**
 * Retourne le code HTML des balises entêtes des pages du sites
 * 
 * @param string $route
 * @param string $pageTitle
 * @param string $favicon
 * @return string
 */
function HTMLHead($route, $pageTitle = null, $favicon = null)
{
    if(empty($route)) $route = '/';
    
    $html = '';

    $html .='
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="'.GENRouteLink('vendors/bootstrap5/css/bootstrap.min.css', $route).'" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    
    <!-- Material Design Icon CSS -->
    <!--<link href="'.GENRouteLink('assets/theme/'.THEME.'/css/mdi.css', $route).'" rel="stylesheet" />-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
    
    <!-- AlainKelleter CSS -->
    <link href="'.GENRouteLink('assets/theme/'.THEME.'/css/kt-mini.css', $route).'" rel="stylesheet" />  
        
    <!-- AlainKelleter Favicon -->    
    <link rel="icon" type="image/x-icon" href="'.GENRouteLink($favicon, $route).'">    
        
    <title>'.$pageTitle.'</title>    
    ';

    return $html;

}

/**
 * Retourne le code HTML du chargement des fichiers JS
 * 
 * @param string $route
 * @return string
 */
function HTMLJS($route)
{
    if(empty($route)) $route = '/';
    
    $html = '';

    $html .='
    <!-- jQuery -->    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="'.GENRouteLink('vendors/bootstrap5/js/bootstrap.bundle.min.js', $route).'"></script>

    <!-- MDB -->
    <!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"></script>-->
    
    <!-- tinyMCE -->
    <script type="text/javascript" src="'.GENRouteLink('vendors/tinymce/tinymce.min.js', $route).'"></script>
    
    <!-- Alain Kelleter JS -->
    <script src="'.GENRouteLink('assets/theme/'.THEME.'/js/kt-mini.js', $route).'"></script>
    ';

    return $html;

}

/**
 * Retourne le code HTML des news sous la forme d'une liste compacte tabulaire
 * 
 * @param array $array
 * @return string
 */
function HTMLListArticlesWithArray($array)
{
    $id = null;
    $date = null;
    $titre = null;
    $teaser = null;
    $contenu = null;
    $icon = null;
    $position = null;

    $html = '
    <div class="table-responsive">    
    <table class="table">
    <thead>
    <!--
        <tr>
        <th scope="col">#</th>
        <th scope="col">Icône</th>
        <th scope="col">Date</th>
        <th scope="col">Titre</th>
        <th scope="col">Teaser</th>           
        </tr>
    -->
    </thead>
    <tbody>
    ';

    foreach($array as $ligne)
    {
        foreach($ligne as $col => $val)
        {
            if($col == 'id') $id = $val;        
            if($col == 'date') $date = convertDateEnToFr($val);        
            if($col == 'title') $titre = $val;        
            if($col == 'teaser') $teaser = $val;        
            if($col == 'icon') $icon = $val;        
            if($col == 'position') $position = $val;        
        }
        $html .='  
            <tr class="m-3 p-3">                
                <td>'.$position.'</td>
                <td><a class="notUnderline" href="'.PATH_PAGE.'article.php?id='.$id.'">'.HTMLMiniThumb($icon, $_SESSION['route']).'</a></td>
                <td>'.$date.'</td>
                <td><a class="notUnderline" href="'.PATH_PAGE.'article.php?id='.$id.'">'.$titre.'</a></td>
                <td>'. html_entity_decode($teaser).'</td>
            </tr>
        ';          
    }

    $html .= '</tbody>
            </table>
            </div>';
   
    return $html;

}

/**
 * Retourne le code HTML des news sous la forme d'une liste compacte de div
 * 
 * @param array $array
 * @return string
 */
function HTMLListArticlesWithDiv($array)
{
    $id = null;
    $date = null;
    $titre = null;
    $teaser = null;
    $contenu = null;
    $icon = null;
    $position = null;
    
    $html = '';
    
    foreach($array as $ligne)
    {
      
        foreach($ligne as $col => $val)
        {
            if($col == 'id') $id = $val;        
            if($col == 'date') $date = convertDateEnToFr($val);        
            if($col == 'title') $titre = $val;        
            if($col == 'teaser') $teaser = $val;        
            if($col == 'icon') $icon = $val;  
            if($col == 'slug') $slug = $val;
            if($col == 'published') $published = $val;
            if($col == 'position') $position = $val;        
        }
        
        ($published)? $class_row ='div-article' : $class_row ='div-article-nonpublished';
        ($published)? $icon_notpublished ='' : $icon_notpublished ='<i class="mdi mdi-eye-off-outline" data-bs-toggle="tooltip" data-bs-placement="top" title="Not published"></i>';
        
        $html .='              
            <div class="container article kt-box-shadow m-2 p-1">  
                <a class="notUnderline news-card" href="'.PATH_PAGE.'article.php?id='.$id.'">
                    <div class="row '.$class_row.'">                
                        <div class="col-lg-1 d-flex flex-row"><div class="align-self-center justify-content-center">'.$position.' '.$icon_notpublished.'</div></div>
                        <div class="col-lg-2 news-card-ele d-flex flex-row"><div class="align-self-center justify-content-center">'.HTMLMiniThumb($icon,$_SESSION['route']).'<div class="fw-light fs-6 news-date">'.$date.'</div></div></div>                                        <div class="col-lg-2 news-card-ele d-flex flex-row"><div class="align-self-center justify-content-center">'.$titre.'</div></div>
                        <div class="col-lg-7 news-card-ele d-flex flex-row"><div class="align-self-center justify-content-center">'. html_entity_decode($teaser).'</div></div>
                    </div>  
                </a>';
        if(isset($_SESSION['IDENTIFY']) && $_SESSION['IDENTIFY'] == 1 )
        {
        
            $html .='
                <div class="row">
                    <div class="col-12 d-flex flex-row-reverse">                        
                        <a href="{url-edit-article}?id='.$id.'" class="align-self-end justify-content-end me-2"><i class="mdi mdi-mini mdi-application-edit-outline menu-item-mini" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i></a>
                    </div>
                </div>';
        }
        
        $html .='</div>';
        
        
              
    }
    
    return $html;
}

/**
 * Retourne le code HTML d'affiche d'une mini thumb
 */
function HTMLMiniThumb($icon, $route, $class = null)
{
    $html = '';
    $html .= '<img class="'.$class.'" src="'.GENRouteLink(PATH_ICON.$icon, $route).'">';
    return $html;
}


/**
 * Lit et Retourne le code HTML de la Navbar du site
 * 
 * @return string
 */
function HTMLincludeNavbar()
{
    $incl = file_get_contents(ABSPATH.D_APP.DS.'navbar.inc.php');
    return $incl;
}

/**
 * Retourne le code HTML du contenu article
 * 
 * @param array $article
 * @return string
 */
function HTMLContentArticle($article)
{
    $classminithumb = 'img-thumbnail rounded float-start m-2';
    $html = '';

    $html = '
        <div id="art-minithumb">
            '.HTMLMiniThumb($article['icon'], $_SESSION['route'], $classminithumb).'        
            '.html_entity_decode($article['article']).'
        </div>
    ';

    return $html;
}


/**
 *  Retourne le code HTML du Footer
 * 
 * @return string
 */
function HTMLFooter()
{
    $html = '';

    $html = '
        <footer id="generic-footer" class="text-end">
            <p><small>'.FOOTER.'</small></p>      
        </footer>
    ';

    return $html;
}

/**
* Ajoute dans la structure HTML de la navigation
* le code HTML du menu user
* 
* @return string $path
*/
function HTMLMenuUser()
{

    $html = '';

    if(isset($_SESSION['IDENTIFY']) && $_SESSION['IDENTIFY'] == 0 )
        $html = '<ul class="navbar-nav d-flex"><li class=""><a href="{url-signin}" class="colorlink">Hello You</a></li></ul>';
    else{
        if(!isset($_SESSION['firstname'])) $_SESSION['firstname']  = 'John Doe';
        
        $html .= ' 
        <ul class="navbar-nav d-flex me-5">
            <li class="nav-item dropdown me-3">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-account" aria-hidden="true"></i> '.$_SESSION['firstname'].'
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{url-add-article}">Add</a></li>
                <!--<li><a class="dropdown-item" href="#">Manage</a></li>-->
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{url-signout}">Sign out</a></li>
              </ul>
            </li>
        </ul>
        ';        
    }

    return $html;
}

/**
 * Retourne le code HTML de l'affichage des fichiers joints
 * 
 * @param type $jointFiles
 * @return string
 */
function HTMLArticleFiles($jointFiles)
{
    //DEBUG// AKPrintR($jointFiles);
    $html = '';
    if(!empty($jointFiles))    
    {    
        $html .= '<h3 class="titre-fichier-joint mt-5">{trm-fichier-joint}</h3>';
        $html .= '<hr>';
        foreach($jointFiles as $file)
        {
                $html .= '<div class="row">'; 
                $html .= '<div class="col-md-12">'; 
                $html .= HTMLFileRow($file["filename"]);   
                $html .= '</div>';
                $html .= '</div>';
        }
    }    
    return $html;
}

/**
* Retourne le code HTML qui affiche le fichier joint à télécharger sous
* la forme d'une ligne
* 
* @param string $filename
* @param string $pathfile
* @param string $link
*/
function HTMLFileRow($filename)
{

    $size = @filesize(ABSPATH . PATH_FILE . $filename);
    $sizeConverted = fileSizeConvert($size);
    if($sizeConverted == false)
        $sizeConverted = '<span class="sig-error">{trm-erreur-filesize}</span>';

    $html = '
    <div class="files-container m-1">
        <div class="row">
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary col-xs-12 col-sm-12 files-infos" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-title="Filename" data-bs-content="'.$filename.'">Filename</button> 
            </div>
            <div class="col-md-5">
                <span class="files-infos col-xs-12 col-sm-12">{trm-taille}: '.$sizeConverted.'</span>
            </div>
            <div class="col-md-5">
                <a href="'.GENRouteLink(PATH_FILE . $filename, $_SESSION['route']).'" class="btn btn-default btn-down btn-sm col-xs-12 col-sm-12 files-info">{trm-telecharger}</a>
            </div>
        </div>
    </div>
    '; 

    return $html;  
}

/**
* Retourne le code html des boutons radios indiquant 
* le status de publication
* 
* @return $html 
*/
function HTMLAddBtnRadioPublished()
{ 
    $html = '';

    $html .= '
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="published_article" name="published_article" checked>
        <label class="form-check-label" for="published_article">Article published</label>
    </div>
    ';
    
    return $html; 
}

/**
* Retourne le code html des boutons radios indiquant 
* le status de publication dans le form d'édition
* 
* @param type $published
* @return string
*/
function HTMLEditBtnRadioPublished($published)
{

    $html = '';

    if($published){        
        $html .= '
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="published_article" name="published_article" checked>
            <label class="form-check-label" for="published_article">Article published</label>
        </div>
        ';
    }else{
        $html .= '
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="published_article" name="published_article">
            <label class="form-check-label" for="published_article">Article published</label>
        </div>
        ';
    }

    return $html; 
}

/**
 * Retourne le code HTML du menu dédié à l'article
 * 
 * @param type $id
 * @return string
 */
function HTMLMenuArticle($id)
{
    $html = '';    
    if($_SESSION['IDENTIFY'] == 1)
    {
        $html .='
        <div class="mb-2 text-end marginHRMenu">
            <a href="{url-index}"><i class="mdi mdi-home-outline menu-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Homepage"></i></a>
            <a href="{url-edit-article}?id='.$id.'"><i class="mdi mdi-application-edit-outline menu-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i></a>
            <a href="{url-signout}"><i class="mdi mdi-application-export menu-item-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Sign Out"></i></a>
        </div>
        ';    
    }else{
        $html .='
        <div class="mb-2 text-end marginHRMenu">
            <a href="{url-index}"><i class="mdi mdi-home-outline menu-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Homepage"></i></a>
            <a href="{url-signin}"><i class="mdi mdi-application-import menu-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Sign In"></i></a>
        </div>
        ';
    }
    
    return $html;
}

/**
 * Retourne le code HTML du menu dédié à l'article
 * 
 * @param type $id
 * @return string
 */
function HTMLMenuEditArticle()
{
    $html = '';    
    $html .='
        <div class="mb-2 text-end marginHRMenu">
            <a href="{url-index}"><i class="mdi mdi-home-outline menu-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Homepage"></i></a>            
            <a href="{url-add-article}"><i class="mdi mdi-newspaper-plus menu-item-manage" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Article"></i></a>
            <a href="{url-signout}"><i class="mdi mdi-application-export menu-item-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Sign Out"></i></a>
        </div>
        ';        
    
    return $html;
}

/**
 * Retourne le code HTML du menu général présent sur certaines pages
 * 
 * @return string
 */
function HTMLMenuGeneralPage()
{
    $html = '';  
    if($_SESSION['IDENTIFY'] == 1)
    {
        $html .='
        <div class="mb-2 text-end marginHRMenu">
            <a href="{url-index}"><i class="mdi mdi-home-outline menu-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Homepage"></i></a>
            <a href="{url-add-article}"><i class="mdi mdi-newspaper-plus menu-item-manage" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Article"></i></a>
            <a href="{url-signout}"><i class="mdi mdi-application-export menu-item-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Sign Out"></i></a>
        </div>
        ';
    }else{
        $html .='
        <div class="mb-2 text-end marginHRMenu">
            <a href="{url-index}"><i class="mdi mdi-home-outline menu-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Homepage"></i></a>
            <a href="{url-signin}"><i class="mdi mdi-application-import menu-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Sign In"></i></a>
        </div>
        ';
    }
        
    return $html;
}

/**
 * Retourne le code HTML de la liste des Icônes
 * 
 * @return string
 */
function HTMLAddSelectIcon()
{
    $html = '';
    $icons = null;
    
    $icons = loadAllIcons();
    //DEBUG// AKPrintR($icons);    
    
    if(is_array($icons) && !empty($icons))
    {
        $html .='
            <label for="minithumb-article" class="form-label">Icon</label>
            <select class="form-select" id="icon_article" name="icon_article">                        
        ';
        
        foreach($icons as $icon)
        {
            $html .='<option value="'.$icon['filename'].'" >'.$icon['icon'].'</option>';
        }                
        
        $html .='</select>';
    }else
        $html .='<span class="sig-error">Loading of the icon list failed</span>';
   
      
    return $html;
}

/**
 * Retourne le code HTML de la liste des Icônes dans le form d'édition
 * 
 * @return string
 */
function HTMLEditSelectIcon($icon_selected)
{
    $html = '';
    $icons = null;
    
    $icons = loadAllIcons();
    //DEBUG// AKPrintR($icons);    
    
    if(is_array($icons) && !empty($icons))
    {
        $html .='
            <label for="minithumb-article" class="form-label">Icon</label>
            <select class="form-select" id="icon_article" name="icon_article">                        
        ';
        
        foreach($icons as $icon)
        {
            if($icon['filename'] == $icon_selected)
                $html .='<option value="'.$icon['filename'].'" selected>'.$icon['icon'].'</option>';
            else
                $html .='<option value="'.$icon['filename'].'" >'.$icon['icon'].'</option>';
        }                
        
        $html .='</select>';
    }else
        $html .='<span class="sig-error">Loading of the icon list failed</span>';
   
      
    return $html;
}

/**
 * Retourne le code HTML de la liste de sélection des fichiers existants 
 * dans le form d'édition
 * 
 * @param type $article_id
 * @return string
 */
function HTMLExistingFiles($article_id)
{   
    $existingFiles = null;
    $existingFiles = loadJointFiles($article_id);
    
    $html = '';

    if(!empty($existingFiles))
    {
        $html .=' <div class="mb-3 form-group">
                 <label for="teaser-article" class="form-label">Existing files</label>
                     <div class="form-control">';
        foreach($existingFiles as $file)
        {
            $html .='
                     <div class="form-check">
                         <input class="form-check-input" type="checkbox" value="'.$file['id'].'" id="existingfiles_article[]" name="existingfiles_article[]">
                         <label class="form-check-label" for="existingfiles_article">
                           '.$file['filename'].' <a href="'.GENRouteLink(PATH_FILE . $file['filename'], $_SESSION['route']).'" target="_blank" class="colorlink"><i class="mdi mdi-mini mdi-eye-outline menu-item-mini" data-bs-toggle="tooltip" data-bs-placement="top" title="View file"></i></a>
                         </label>
                     </div>';

        }
        $html .='    </div>
                     <span class="min-ele sig-error"><i>Select the files you want to delete</span>    
                 </div>'; 
    }
    
    return $html;
}