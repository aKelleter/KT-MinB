<?php
  
// Generic parameters  
    define( 'KT_ROOT', getcwd() );
    define( 'DS', DIRECTORY_SEPARATOR );
    define( 'NAME', 'alainkelleter_be' );

// ABSPATH is root folder of App
    if (! defined ( 'ABSPATH' )) define ('ABSPATH', dirname ( __FILE__ ) . DS );


// Directories
    if (! defined ( 'D_APP' )) define ( 'D_APP', 'app' );
    if (! defined ( 'D_CLAS' )) define ( 'D_CLAS', 'class' );
    if (! defined ( 'D_FCT' )) define ( 'D_FCT', 'fct' );
    if (! defined ( 'D_PAGE' )) define ( 'D_PAGE', 'page' );
    if (! defined ( 'D_VIEW' )) define ( 'D_VIEW', 'view' );
    if (! defined ( 'PATH_PAGE' )) define ( 'PATH_PAGE', D_APP.DS.D_PAGE.DS );
  

// Load Config    
    require_once D_APP . DS . 'conf.php';    


// Load Loader    
    require_once D_APP . DS . 'loader.php';
