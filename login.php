<?php
 include 'boot.php';
?>

<?php
// --------------------------------------------------------------------------------------------
// CONTROLER SECTION
$msg = null;

if(isset($_POST['action']) && $_POST['action'] == 'login')
{
    $action = $_POST['action'];
    $user = $_POST['login-user'];
    $passwd = $_POST['passwd-user'];

    // Tentative d'identification
    if(!empty($user) && !empty($passwd))
        if(AKUserExist($user))
            $st = AKIdentUser($user, $passwd);
        else{
            $st['stat'] = false;
            $st['msg'] = 'Unknown user';
        }
    else {       
        $st['stat'] = false;
        $st['msg'] = 'Please complete the form';
    }

    // Si identification ok
    if($st['stat'] === true) 
    {
        // Récupération des datas du user
        $_SESSION['login'] =  $st['login'];
        $_SESSION['firstname'] =  $st['firstname'];
        $_SESSION['lastname'] =  $st['lastname'];
        $_SESSION['email'] = $st['email'];
        $_SESSION['status'] = $st['status'];
        $_SESSION['user_id'] = $st['user_id'];

        // Initialiser Identify sur (1) et indiquer ainsi que l'utilisateur est identifié
        $_SESSION['IDENTIFY'] = 1;
        header("location: index.php?msg=Identification ok");

    }else{
        
        // Afficher une div d'alerte avec le message correspondant
        $msg = AKMakeDiv('danger', 'alert alert-danger', $st['msg'], 'alert' );

        // Initialiser Identify sur (0)  et ainsi refuser l'accès aux autres modules a tous les utilisateurs non identifiés.
        $_SESSION['IDENTIFY'] = 0;
        $_SESSION['firstname'] = null;
    } 
    
    
}else{
    $action = null;
    $user = null;
    $passwd = null;
    $msg = null;
    $st = null;

}


// --------------------------------------------------------------------------------------------
// TEMPLATE SECTION

// --------------------------------------------------------------------------------------------
// Instanciation du moteur de template
$page = 'LOGIN';
$engine = new Template(ABSPATH . D_APP . DS . D_VIEW . DS);
$engine->set_file($page, 'login-tpl.html');

// --------------------------------------------------------------------------------------------
// Var Initialization
$pageTitle = 'Alain Kelleter - Who is here / Sign in';
$engine->set_var('titre-page-login', 'Who is here ?');
$engine->set_var('message', $msg);


// Include common constant and var 
include ABSPATH . DS . D_APP . DS . 'common.php';

// --------------------------------------------------------------------------------------------
// DEBUG SECTION

// --------------------------------------------------------------------------------------------+
// Remplacement des variables du template par les valeurs associées
$engine->parse('display', $page);

// Rendu du template
$engine->p('display');
