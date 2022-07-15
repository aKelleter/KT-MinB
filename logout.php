<?php
 include 'boot.php';
?>
<?php

// Logout ---------------------------------------------------------
setcookie(session_name(), '', 100);
session_unset();
session_destroy();
$_SESSION = array(); 

// Nouvelle Session  ------------------------------------------
new Session();
$_SESSION['IDENTIFY'] = 0;

// Parser l'url ----------------------------------------------------
$urlParsed = parse_url($_SERVER ['REQUEST_URI']);

// Récupérer les clés path et query (si existantes) 
$path = KTFindKey( $urlParsed, 'path' );
    
// Traiter la route
if (strpos($path, 'page') !== false)
{
    $_SESSION['route'] = 'page';
} else {
    $_SESSION['route'] = null;
}

// Redirection ---------------------------------------------------
header("location: index.php");

