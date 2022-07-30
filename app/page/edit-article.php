<?php
 include '../../boot.php';
?>

<?php
// --------------------------------------------------------------------------------------------
// CONTROLER SECTION
$msg = null;
$id = null;
$article = null;
$checkID = false;

if(isset($_GET['id']) && !empty($_GET['id']))
{   
    $checkID = AKcheckGetParam($_GET['id'], 'NUMERIC');
    if($checkID)
    {
        $id = $_GET['id'];
        $article = loadArticle($id); 
        if($article != null && $article != false)
            $jointFiles = loadJointFiles($id);
        else{
            // Message drôle d'article
            $feedback = "Information : This article does not exist";
            $msg = AKMakeDiv('info', 'alert alert-info text-center mt-2', $feedback, 'alert', 'message-fade');
        }
        
    }else{
        // Message drôle d'article
        $feedback = "Hazard : It's a weird article";
        $msg = AKMakeDiv('danger', 'alert alert-danger text-center mt-2', $feedback, 'alert', 'message-fade');
    }

}else{
    // Message Oups Houston we have a problem
    $feedback = "Ouch : The application encountered a problem while loading the article";
    $msg = AKMakeDiv('info', 'alert alert-info text-center mt-2', $feedback, 'alert', 'message-fade');
}

if(isset($_POST['form_name']) && $_POST['form_name'] == 'edit-article')
{
    

    
}
    
    // Message de retour
    //$msg = AKMakeDiv($rt['type'], 'alert alert-'.$rt['type'].' text-center', $rt['msg'], 'alert');
    
    // Préparation de la redirection
    $redirection_url = GENRouteLink('index.php', $_SESSION['route']);    
    // Redirection    
    //header("refresh:3; $redirection_url" );    
    
     
    


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

$engine->set_var('data-title', html_entity_decode($article['title']));
$engine->set_var('data-teaser', html_entity_decode($article['teaser']));
$engine->set_var('data-article', html_entity_decode($article['article']));


// Menu Edit Article
$engine->set_var('menu-edit-article', HTMLMenuEditArticle());

// Include common constant and var 
include ABSPATH . DS . D_APP . DS . 'common.php';

// --------------------------------------------------------------------------------------------
// DEBUG SECTION

// --------------------------------------------------------------------------------------------+
// Remplacement des variables du template par les valeurs associées
$engine->parse('display', $page);

// Rendu du template
$engine->p('display');
