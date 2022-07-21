<?php

//-----------------------------------------------------------------------------
// Lancement des sessions
new Session();

//  Si consultation et pas d'utilisateur identifié
if(empty($_SESSION['IDENTIFY']))
    $_SESSION['IDENTIFY'] = 0;



