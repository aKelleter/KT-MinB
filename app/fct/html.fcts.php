<?php

/**
 * Retourne le code HTML des balises entêtes des pages du sites
 */
function HTMLHead()
{
    $html = '';

    $html .='
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="vendors/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.css" rel="stylesheet" />

    <link href="assets/theme/akstudio/css/alainkelleter.be.css" rel="stylesheet" />

    <title>{page-title}</title>    
    ';


    return $html;

}

/**
 * Retourne le code HTML du chargement des fichiers JS
 */
function HTMLJS($route=null)
{
    $html = '';

    $html .='
    <!-- Bootstrap Bundle with Popper -->
    <script src="'.GENRouteLink('vendors/bootstrap5/js/bootstrap.bundle.min.js', $route).'"></script>

    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.2.0/mdb.min.js"></script>
    ';

    return $html;

}

/**
 * Retourne le code HTML des news sous la forme d'une liste compacte
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
                <td>'.HTMLMiniThumb($minithumb, false).'</td>
                <td>'.$date.'</td>
                <td>'.$titre.'</td>
                <td><a href="'.PATH_PAGE.'article.php?id='.$id.'">'.$teaser.'</a></td>
            </tr>
        ';
        
    }

    $html .= '</tbody>
            </table>';
    

    return $html;

}

/**
 * Retourne le code HTML d'affiche d'une mini thumb
 */
function HTMLMiniThumb($minithumb, $page = true, $class = null)
{
    
    ($page)?$prepath='..'.DS.'..'.DS : $prepath='';  

    $html = '';

    $html .= '<img class="'.$class.'" src="'.$prepath.PATH_MINITHUMB.$minithumb.'">';

    return $html;
}

/**
* Lit et Retourne le code HTML de la Navbar du site
*/
function HTMLincludeNavbar()
{
    $incl = file_get_contents(ABSPATH.D_APP.DS.'navbar.inc.php');
    return $incl;
}

/**
 * Retourne le code HTML d'un article
 */
function HTMLArticle($article)
{
    $classminithumb = 'img-thumbnail rounded float-start m-2';
    $html = '';

    $html = '
        <div id="art-minithumb">
            '.HTMLMiniThumb($article['minithumb'], true, $classminithumb).'        
            '.$article['article'].'
        </div>
    ';

    return $html;
}

/**
 *  Retourne le code HTML du Footer
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
* @param string $path
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
