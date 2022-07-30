<?php

if(!defined('K_DEBUG')) define('K_DEBUG', true);
if(!defined('VERSION')) define('VERSION', 'v0.5.3 - 30/07/2022 22:02');
if(!defined('FOOTER')) define('FOOTER', 'alainkelleter.be - '.VERSION);
if(!defined('THEME')) define('THEME', 'akstudio');
if(!defined('PATH_ICON')) define('PATH_ICON', 'pub' . DS . 'icons' . DS);
if(!defined('PATH_FILE')) define('PATH_FILE', 'pub' . DS . 'files' . DS);
if(!defined('PATH_IMG')) define('PATH_IMG', 'pub' . DS . 'img' . DS);
if(!defined('LOGO')) define('LOGO', 'assets' . DS . 'theme' . DS . 'akstudio' . DS . 'img' . DS . 'logo-ak.png');
if(!defined('ICO')) define('ICO', 'assets' . DS . 'theme' . DS . 'akstudio' . DS . 'ico' . DS);
if(!defined('FAVICON')) define('FAVICON', 'assets' . DS . 'theme' . DS . 'akstudio' . DS . 'img' . DS . 'icon-ak.png');

// Définit la durée d'une session (1h par défaut)
if(!defined('SESSLIFETIME')) define ('SESSLIFETIME', 3600 * 12);

// Configure le niveau d'erreurs en fonction du mode de debugging
if(K_DEBUG) error_reporting(E_ALL | E_STRICT); else error_reporting(0);

// Definit les extensions autorisées pour les fichiers téléversés 
$allowExtensions = array( 'epub', 'zip' , 'rar' , 'tar', 'tar.gz' , 'txt', 'pdf', "psd", 'md', 'odt', 'odf', 'ods', 'png', 'webm', 'webp', 'flac', 'ogg', 'docx', 'xlsx', 'pptx', 'jpg', 'jpeg', "mp3", "mp4" );
if(!defined('ALLOWEXT')) define('ALLOWEXT', serialize($allowExtensions)); 

// Definit la taille maximum des fichiers téléversés (1mo = 1048576)
if(!defined('MAXSIZE')) define( 'MAXSIZE', 1048576 * 64);

// Définit le chemin des images des articles
if(!defined('PATHIMG')) define('PATHIMG', 'pub' . DS . 'img' . DS);

// Définit le chemin des fichiers joints des articles
if(!defined('PATHFILE')) define('PATHFILE', 'pub' . DS . 'files' . DS);

