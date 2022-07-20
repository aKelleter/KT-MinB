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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />-->
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
function HTMLNews($array)
{
    $id = null;
    $date = null;
    $titre = null;
    $teaser = null;
    $contenu = null;
    $minithumb = null;

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
        }
        $html .='  
            <tr>                
                <td>'.$id.'</td>
                <td>'.HTMLMiniThumb($minithumb,$_SESSION['route']).'</td>
                <td>'.$date.'</td>
                <td>'.$titre.'</td>
                <td><a href="'.PATH_PAGE.'article.php?id='.$id.'">'.$teaser.'</a></td>
            </tr>
        ';       
    }

    $html .= '</tbody>
            </table>
            </div>';
   
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
            '.$article['article'].'
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
        $html = '<li><a href="{url-signin}">Hello You</a></li>';
    else{
        if(!isset($_SESSION['firstname'])) $_SESSION['firstname']  = 'John Doe';
        
        $html .= '
            
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-user"></i> <span class="">'.$_SESSION['firstname'].'</span> <span class="caret">
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{url-add-article}">Add</a></li>
            <li><a class="dropdown-item" href="#">Manage</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{url-signout}">Sign out</a></li>
          </ul>
        </li>';        
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
        case 'ALERT':
            $div = '<div class="';           
            $div .= $classes.' text-center"';
            $div .= 'id="'.$id.'" ';
            $div .= ' role="'.$role.'">';
            $div .= $message;
            if(isset($innerSpan)) $div .= '<span id="submsg">'.$innerSpan.'</span>';
            $div .= '</div>';

        case 'SUCCESS':
            $div = '<div class="';
            $div .= $classes.' text-center"';
            $div .= 'id="'.$id.'" ';
            $div .= ' role="'.$role.'">';
            $div .= $message;
            if(isset($innerSpan)) $div .= '<span id="submsg">'.$innerSpan.'</span>';
            $div .= '</div>';

        case 'WARNING':
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
