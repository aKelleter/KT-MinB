<?php

//-----------------------------------------------------------------------------
Class Session
{
    function __construct()
    {
        // Vérifier l'existence d'uen session ou non et lancer en fonction du résutlat
        if(!self::isSessionStarted()) {
            session_name(NAME);
            session_start();
        } 

    }
 
    /**
    * Intialise une variablde de session et sa valeur
    * 
    * @param string $varName
    * @param string $varValue
    */
    public function setVar($varName, $varValue)
    {
        if(!empty(session_id()))
            $_SESSION[$varName] = $varValue;
    }

    /**
    * Retourne la variable de session passée en paramètre
    * 
    * @param string $varName
    */
    public function getVar($varName)
    {
        return  $_SESSION[$varName];
    }

    /**
    * Détruit une variable de session
    * 
    * @param string $varName
    */
    public function unsetVar($varName)
    {
        unset($_SESSION[$varName]);
    }

    /**
    * Vérifie l'existence d'une session
    * 
    * @return bool
    */
    function isSessionStarted()
    {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }

} // End Class Session

