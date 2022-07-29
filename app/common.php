<?php

$engine->set_var('HTMLHead', HTMLHead($_SESSION['route'], $pageTitle, FAVICON));
$engine->set_var('HTMLJS', HTMLJS($_SESSION['route']));
$engine->set_var('HTMLFooter', HTMLFooter());
$engine->set_var('HTMLNavbar', HTMLincludeNavbar());
$engine->set_var('menu-user', HTMLMenuUser());

$engine->set_var('site-logo', '<img src="'.GENRouteLink(LOGO, $_SESSION['route']).'" />');
$engine->set_var('AK', 'Alain Kelleter');
$engine->set_var('title-news', 'News');
$engine->set_var('title-fab-article', 'Encore un fabuleux article à lire');
$engine->set_var('trm-fichier-joint', 'Attachements');
$engine->set_var('trm-erreur-filesize', 'File Size - Error');
$engine->set_var('trm-taille', 'Size');
$engine->set_var('trm-telecharger', 'Download');

$engine->set_var('url-index', GENRouteLink('index.php', $_SESSION['route']));
$engine->set_var('url-signout', GENRouteLink('logout.php', $_SESSION['route']));
$engine->set_var('url-signin', GENRouteLink('login.php', $_SESSION['route']));
$engine->set_var('url-add-article', GENRouteLink(D_APP.DS.D_PAGE.DS.'add-article.php', $_SESSION['route']));
$engine->set_var('url-edit-article', GENRouteLink(D_APP.DS.D_PAGE.DS.'edit-article.php', $_SESSION['route']));
