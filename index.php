<?php    include 'boot.php';?><?php// --------------------------------------------------------------------------------------------// CONTROLER SECTION$msg = null; if(isset($_GET['msg']) && !empty($_GET['msg']))    $msg = AKMakeDiv('info', 'alert alert-info text-center', $_GET['msg'], 'alert', 'message-index');$params = loadParams();$news = loadAllArticles();//DEBUG////AKPrintR($_SESSION);// --------------------------------------------------------------------------------------------// TEMPLATE SECTION// --------------------------------------------------------------------------------------------// Instanciation du moteur de template$page = 'INDEX';$engine = new Template(ABSPATH . D_APP . DS . D_VIEW . DS);$engine->set_file($page, 'index-tpl.html');// --------------------------------------------------------------------------------------------// Var Initialization$pageTitle = 'Alain Kelleter - I\'M ALIVE';$engine->set_var('message', $msg);$engine->set_var('title', AKFindParam($params, 'title'));$engine->set_var('subtitle', AKFindParam($params, 'subtitle'));$engine->set_var('author', AKFindParam($params, 'author'));$engine->set_var('lastupdate-db', AKFindParam($params, 'lastupdate'));if($_SESSION['IDENTIFY'] == 1)    $engine->set_var('menu-general', HTMLMenuGeneralPage());if(!empty($news))    $engine->set_var('HTMLNews', HTMLListArticlesWithDiv($news));else    $engine->set_var('HTMLNews', 'There are no posts here yet :)');// Include common constant and var include ABSPATH . DS . D_APP . DS . 'common.php';// --------------------------------------------------------------------------------------------// DEBUG SECTION// --------------------------------------------------------------------------------------------+// Remplacement des variables du template par les valeurs associées$engine->parse('display', $page);// Rendu du template$engine->p('display');