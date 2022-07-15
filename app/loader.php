<?php
//-----------------------------------------------------------------------------
// Loading des classes and functions
require_once ABSPATH . D_APP . DS . D_CLAS . DS . 'cSession.php';
require_once ABSPATH . D_APP . DS .  'session.start.php';
require_once ABSPATH . D_APP . DS . D_CLAS . DS . 'cDebug.php';
require_once ABSPATH . D_APP . DS . D_CLAS . DS . 'cConfdb.php';
require_once ABSPATH . D_APP . DS . D_CLAS . DS . 'cDb.php';
require_once ABSPATH . D_APP . DS . D_FCT . DS . 'app.fcts.php';
require_once ABSPATH . D_APP . DS . D_FCT . DS . 'html.fcts.php';
require_once ABSPATH . D_APP . DS . D_CLAS . DS . 'cTemplate.php';


// Parser l'url
$urlParsed = parse_url($_SERVER ['REQUEST_URI']);
//DEBUG//var_dump($urlParsed);

// Récupérer les clés path et query (si existantes) 
    $path = KTFindKey( $urlParsed, 'path' );
    $query = KTFindKey( $urlParsed, 'query' );    
    
// Traiter la route
if (strpos($path, 'page') !== false)
{
    $_SESSION['route'] = 'page';
} else {
    $_SESSION['route'] = null;
}
    
    