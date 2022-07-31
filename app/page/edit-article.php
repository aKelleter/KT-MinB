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
$rt = null;

// Affichage de l'article
if(isset($_GET['msg']) && !empty($_GET['msg']))
    $msg = AKMakeDiv($_GET['type'], 'alert alert-'.$_GET['type'].' text-center border-info', $_GET['msg'], 'alert', 'message-fade');

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
    
    $redirection_url = GENRouteLink('index.php', $_SESSION['route']);    
    header("refresh:3; $redirection_url" );   
    
}

// Mise à jour de l'article
if(isset($_POST['form_name']) && $_POST['form_name'] == 'edit-article')
{
    
    if(empty($_FILES)) $_FILES = null;

        //DEBUG// AKPrintR($_POST);  AKPrintR($_FILES);  //die();

        $rt = updateArticle($_POST);
        
        if(is_array($rt))
        {
            // Message de retour
            $msg = AKMakeDiv($rt['type'], 'alert alert-'.$rt['type'].' text-center', $rt['msg'], 'alert', 'message-fade');

            // Préparation de la redirection
            $redirection_url = GENRouteLink('app/page/edit-article.php?id='.$_POST['id_article'].'&msg='.$rt['msg'].'&type='.$rt['type'], $_SESSION['route']);    
            // Redirection    
            //header("refresh:3; $redirection_url" );   
            header("location: $redirection_url");
        }

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


if($checkID)
{
    $engine->set_var('url-view-article', GENRouteLink('app/page/article.php?id='.$_GET['id'], $_SESSION['route']));
    $engine->set_var('data-title', html_entity_decode($article['title']));
    $engine->set_var('radiobtn-published-edit', HTMLEditBtnRadioPublished($article['published']));
    $engine->set_var('select-icon-edit', HTMLEditSelectIcon($article['icon']));
    $engine->set_var('data-teaser', html_entity_decode($article['teaser']));
    $engine->set_var('data-article', html_entity_decode($article['article']));
    $engine->set_var('data-id', $article['id']);
}


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
