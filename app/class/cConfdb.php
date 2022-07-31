<?php

//-----------------------------------------------------------------------------
class ConfDB
{

    // Credentials Databases
    // Plusieurs Credentials sont possibles
    static $databases = array(

        'default' => array(
            'host' => 'localhost',
            'port' => '3306',
            'database' => 'kentaro_alainkelleter_be_miniblog',
            'prefix' => 'ktm_',
            'login' => 'kentaro_alainkelleter_be',
            'password' => 'Tyu567+*24;'
        )
    );
}