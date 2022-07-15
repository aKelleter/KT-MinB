<?php
 include '../../boot.php';
?>

<?php
// --------------------------------------------------------------------------------------------
// CONTROLER SECTION
$msg = null;
$id = null;
$article = null;

if(isset($_GET['id']) && !empty($_GET['id']))
{
    if(AKcheckGetParam($_GET['id'], 'NUMERIC'))
    {
        $id = $_GET['id'];
        $article = AKloadArticle($id);
    }
        
}else
    $msg = "Ouch ! L'application a rencontré un problème lors du chargement de l'article";

// --------------------------------------------------------------------------------------------
// TEMPLATE SECTION

// --------------------------------------------------------------------------------------------
// Instanciation du moteur de template
$page = 'P-ARTICLE';
$engine = new Template(ABSPATH . D_APP . DS . D_VIEW . DS);
$engine->set_file($page, 'article-tpl.html');

// --------------------------------------------------------------------------------------------
// Var Initialization
$engine->set_var('page-title', 'Alain Kelleter - Lecture de l\'article');
$engine->set_var('message', $msg);
$engine->set_var('article-date', convertDateEnToFr($article['date']));
$engine->set_var('article-title', $article['title']);
$engine->set_var('HTMLArticle', HTMLArticle($article));

// Include common constant and var 
include ABSPATH . DS . D_APP . DS . 'common.php';

// --------------------------------------------------------------------------------------------
// DEBUG SECTION

// --------------------------------------------------------------------------------------------+
// Remplacement des variables du template par les valeurs associées
$engine->parse('display', $page);

// Rendu du template
$engine->p('display');