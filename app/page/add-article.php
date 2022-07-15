<?php
 include '../../boot.php';
?>

<?php
// --------------------------------------------------------------------------------------------
// CONTROLER SECTION
$msg = null;

// --------------------------------------------------------------------------------------------
// TEMPLATE SECTION

// --------------------------------------------------------------------------------------------
// Instanciation du moteur de template
$page = 'P-ADD-ARTICLE';
$engine = new Template(ABSPATH . D_APP . DS . D_VIEW . DS);
$engine->set_file($page, 'add-article-tpl.html');

// --------------------------------------------------------------------------------------------
// Var Initialization
$engine->set_var('page-title', 'Alain Kelleter - Add Article');
$engine->set_var('titre-page-add-article', 'Add an article');
$engine->set_var('message', $msg);


// Include common constant and var 
include ABSPATH . DS . D_APP . DS . 'common.php';

// --------------------------------------------------------------------------------------------
// DEBUG SECTION

// --------------------------------------------------------------------------------------------+
// Remplacement des variables du template par les valeurs associées
$engine->parse('display', $page);

// Rendu du template
$engine->p('display');
