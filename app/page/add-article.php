<?php
 include '../../boot.php';
?>

<?php
// --------------------------------------------------------------------------------------------
// CONTROLER SECTION
$msg = null;
 
if(isset($_POST['form_name']) && $_POST['form_name'] == 'add-article')
{
    
    if(empty($_FILES)) $_FILES = null;
        
    //DEBUG//  AKPrintR($_POST);  AKPrintR($_FILES);  die();
    
    $rt = addArticleMulti($_POST);
    
    // Message de retour
    $msg = AKMakeDiv($rt['type'], 'alert alert-'.$rt['type'].' text-center', $rt['msg'], 'alert', 'message-fade');
    
    // Préparation de la redirection
    $redirection_url = GENRouteLink('index.php', $_SESSION['route']);    
    // Redirection    
    header("refresh:4; $redirection_url" );    
    
     
    
}

// --------------------------------------------------------------------------------------------
// TEMPLATE SECTION

// --------------------------------------------------------------------------------------------
// Instanciation du moteur de template
$page = 'P-ADD-ARTICLE';
$engine = new Template(ABSPATH . D_APP . DS . D_VIEW . DS);
if($_SESSION['IDENTIFY'] == 1)
    $engine->set_file($page, 'add-article-tpl.html');
else
    $engine->set_file($page, 'noaccess-tpl.html');

// --------------------------------------------------------------------------------------------
// Var Initialization
$pageTitle = 'Alain Kelleter - Add Article';
$engine->set_var('titre-page-add-article', 'Add an article');
$engine->set_var('message', $msg);
$engine->set_var('radiobtn-status', HTMLAddBtnRadioPublished());

// General menu on page
$engine->set_var('menu-general', HTMLMenuGeneralPage());

// Include common constant and var 
include ABSPATH . DS . D_APP . DS . 'common.php';

// --------------------------------------------------------------------------------------------
// DEBUG SECTION

// --------------------------------------------------------------------------------------------+
// Remplacement des variables du template par les valeurs associées
$engine->parse('display', $page);

// Rendu du template
$engine->p('display');
