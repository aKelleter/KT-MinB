<?php

$engine->set_var('HTMLHead', HTMLHead());
$engine->set_var('HTMLJS', HTMLJS($_SESSION['route']));
$engine->set_var('HTMLFooter', HTMLFooter());
$engine->set_var('HTMLNavbar', HTMLincludeNavbar());

$engine->set_var('menu-user', HTMLMenuUser());

$engine->set_var('AK', 'AK');
$engine->set_var('title-news', 'News');
$engine->set_var('title-fab-article', 'Encore un fabuleux article à lire');

$engine->set_var('url-signout', GENRouteLink('logout.php', $_SESSION['route']));
$engine->set_var('url-signin', GENRouteLink('login.php', $_SESSION['route']));
$engine->set_var('url-add-article', GENRouteLink(D_APP.DS.D_PAGE.DS.'add-article.php', $_SESSION['route']));