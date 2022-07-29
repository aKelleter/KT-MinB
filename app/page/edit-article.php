<?php
 include '../../boot.php';
?>

<?php
// --------------------------------------------------------------------------------------------
// CONTROLER SECTION
$msg = null;
 
if(isset($_POST['form_name']) && $_POST['form_name'] == 'edit-article')
{
    
    
    
    // Message de retour
    $msg = AKMakeDiv($rt['type'], 'alert alert-'.$rt['type'].' text-center', $rt['msg'], 'alert');
    
    // Préparation de la redirection
    $redirection_url = GENRouteLink('index.php', $_SESSION['route']);    
    // Redirection    
    header("refresh:3; $redirection_url" );    
    
     
    
}

// --------------------------------------------------------------------------------------------
// TEMPLATE SECTION

// --------------------------------------------------------------------------------------------
// Instanciation du moteur de template
$page = 'P-EDIT-ARTICLE';
$engine = new Template(ABSPATH . D_APP . DS . D_VIEW . DS);
if($_SESSION['IDENTIFY'] == 1)
    $engine->set_file($page, 'edit-article-tpl.html');
else
    $engine->set_file($page, 'noaccess-tpl.html');

// --------------------------------------------------------------------------------------------
// Var Initialization
$pageTitle = 'Alain Kelleter - Edit Article';
$engine->set_var('titre-page-edit-article', 'Edit an article');
$engine->set_var('message', $msg);
$engine->set_var('radiobtn-status', HTMLAddBtnRadioPublished());

// Include common constant and var 
include ABSPATH . DS . D_APP . DS . 'common.php';

// --------------------------------------------------------------------------------------------
// DEBUG SECTION

// --------------------------------------------------------------------------------------------+
// Remplacement des variables du template par les valeurs associées
$engine->parse('display', $page);

// Rendu du template
$engine->p('display');
