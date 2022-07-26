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
    <!-- Font Awesome -->
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />-->
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />-->    
    <!-- Material Design Icon CSS -->
    <!--<link href="'.GENRouteLink('assets/theme/akstudio/css/mdi.css', $route).'" rel="stylesheet" />-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css">
    <!-- AlainKelleter CSS -->
    <link href="'.GENRouteLink('assets/theme/akstudio/css/alainkelleter.be.css', $route).'" rel="stylesheet" />  
        
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
    <script src="'.GENRouteLink('assets/theme/akstudio/js/alainkelleter.be.js', $route).'"></script>
    ';

    return $html;

}

/**
 * Retourne le code HTML des news sous la forme d'une liste compacte
 * 
 * @param array $array
 * @return string
 */
function HTMLListArticlesLikeArray($array)
{
    $id = null;
    $date = null;
    $titre = null;
    $teaser = null;
    $contenu = null;
    $minithumb = null;
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
            if($col == 'minithumb') $minithumb = $val;        
            if($col == 'position') $position = $val;        
        }
        $html .='  
            <tr class="m-3 p-3">                
                <td>'.$position.'</td>
                <td><a class="notUnderline" href="'.PATH_PAGE.'article.php?id='.$id.'">'.HTMLMiniThumb($minithumb,$_SESSION['route']).'</a></td>
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

function HTMLListArticlesWithDiv($array)
{
    $id = null;
    $date = null;
    $titre = null;
    $teaser = null;
    $contenu = null;
    $minithumb = null;
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
            if($col == 'minithumb') $minithumb = $val;        
            if($col == 'position') $position = $val;        
        }
        $html .='              
            <div class="container kt-box-shadow m-2 p-1">                 
                <div class="row div-article">                
                    <div class="col-md-1">'.$position.'</div>
                    <div class="col-md-2"><a class="notUnderline" href="'.PATH_PAGE.'article.php?id='.$id.'">'.HTMLMiniThumb($minithumb,$_SESSION['route']).'</a></div>
                    <div class="col-md-2">'.$date.'</div>
                    <div class="col-md-2"><a class="notUnderline" href="'.PATH_PAGE.'article.php?id='.$id.'">'.$titre.'</a></div>
                    <div class="col-md-5">'. html_entity_decode($teaser).'</div>
                </div>               
            </div>            
        ';          
    }
    
    return $html;
}

/**
 * Retourne le code HTML d'affiche d'une mini thumb
 */
function HTMLMiniThumb($minithumb, $route, $class = null)
{
    $html = '';
    $html .= '<img class="'.$class.'" src="'.GENRouteLink(PATH_ICON.$minithumb, $route).'">';
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
 * Retourne le code HTML d'un article
 * 
 * @param array $article
 * @return string
 */
function HTMLArticle($article)
{
    $classminithumb = 'img-thumbnail rounded float-start m-2';
    $html = '';

    $html = '
        <div id="art-minithumb">
            '.HTMLMiniThumb($article['minithumb'], $_SESSION['route'], $classminithumb).'        
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
        $html = '<ul class="navbar-nav d-flex"><li class=""><a href="{url-signin}">Hello You</a></li></ul>';
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
                <li><a class="dropdown-item" href="#">Manage</a></li>
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
* Retourne une div formatée en fonction des données reçues
* La variable innerSpan permet d'inclure une balise de type span qui
* permet d'afficher un message secondaire
*
* @param string $type  (ALERT, SUCCESS, WARNING)
* @param string $classes
* @param string $message
* @param string $role
* @param string $id
* @param string $innerSpan
*/
function AKMakeDiv( $type, $classes, $message, $role = null, $id = null, $innerSpan = null )
{
    Switch($type)
    {
        case 'danger':
            $div = '<div class="';           
            $div .= $classes.' text-center"';
            $div .= 'id="'.$id.'" ';
            $div .= ' role="'.$role.'">';
            $div .= $message;
            if(isset($innerSpan)) $div .= '<span id="submsg">'.$innerSpan.'</span>';
            $div .= '</div>';

        case 'success':
            $div = '<div class="';
            $div .= $classes.' text-center"';
            $div .= 'id="'.$id.'" ';
            $div .= ' role="'.$role.'">';
            $div .= $message;
            if(isset($innerSpan)) $div .= '<span id="submsg">'.$innerSpan.'</span>';
            $div .= '</div>';

        case 'warning':
            $div = '<div class="';
            $div .= $classes.' text-center"';
            $div .= 'id="'.$id.'" ';
            $div .= ' role="'.$role.'">';
            $div .= $message;
            if(isset($innerSpan)) $div .= '<span id="submsg">'.$innerSpan.'</span>';
            $div .= '</div>';
            
        case 'info':
            $div = '<div class="';
            $div .= $classes.' text-center"';
            $div .= 'id="'.$id.'" ';
            $div .= ' role="'.$role.'">';
            $div .= $message;
            if(isset($innerSpan)) $div .= '<span id="submsg">'.$innerSpan.'</span>';
            $div .= '</div>';
    }


    return $div;
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
function HTMLFileRow($filename){

    $size = @filesize(ABSPATH . PATH_FILE . $filename);
    $sizeConverted = fileSizeConvert($size);
    if($sizeConverted == false)
        $sizeConverted = '<span class="sig-error">{trm-erreur-filesize}</span>';

    $html = '
    <div class="files-container m-1">
        <div class="row">
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary col-xs-12 col-sm-12 files-infos" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-title="Filename" data-bs-content="'.$filename.'">Filename <i class="mdi mdi-file-outline"></i></button> 
            </div>
            <div class="col-md-5">
                <span class="files-infos col-xs-12 col-sm-12">{trm-taille}: '.$sizeConverted.'</span>
            </div>
            <div class="col-md-5">
                <a href="'.GENRouteLink(PATH_FILE . $filename, $_SESSION['route']).'" class="btn btn-default btn-down btn-sm col-xs-12 col-sm-12">{trm-telecharger}</a>
            </div>
        </div>
    </div>
    '; 

    return $html;  
}