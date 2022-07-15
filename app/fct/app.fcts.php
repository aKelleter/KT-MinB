<?php

/**
 * Cette fonction charge tous les enregistrements
 * de la table des paramètres (params)
 *
 * @return array
 */
function AKloadParams()
{
    $con = new Model('params');
    $sql = ' SELECT * FROM ' . $con->credentials['prefix'] . $con->table;

    if ($con) {

        $req = $con->db->query($sql);

        $list = array();
        $i = 0;

        foreach ($req as $data) {
            $list [$i] = array(
                'id' => $data ['id'],
                'key' => $data ['key'],
                'value' => $data ['value']
            );
            $i ++;
        }

        //$list = json_encode( $list );

        return $list;
    } else
        return false;
}

/**
 * Retourne le tableau des news
 * 
 * @return array
 */
function AKloadNews()
{
    $con = new Model('content');
    $sql = ' SELECT * FROM ' . $con->credentials['prefix'] . $con->table;

    if ($con)
    {

        $req = $con->db->query($sql);

        $list = array();
        $i = 0;

        foreach ($req as $data) {
            $list [$i] = array(
                'id' => $data ['id'],
                'title' => $data ['title'],
                'date' => $data ['date'],
                'teaser' => $data ['teaser'],
                'article' => $data ['article'],
                'minithumb' => $data ['minithumb']
            );
            $i ++;
        }
        return $list;

    }else
        return false;
}

/**
 * Retourne l'article dont l'ID est passé en paramètre
 * 
 * @return array
 */
function AKloadArticle($id)
{
    $con = new Model('content');
    $sql = 'SELECT * FROM ' . $con->credentials['prefix'] . $con->table . ' WHERE id='.$id;

    if ($con)
    {
        $req = $con->db->query($sql);
        return $req->fetch(PDO::FETCH_ASSOC);

    }else
        return false;
}

/**
* Retourne la valeur du paramètre recherché
* @param array $array
* @param string $key
* @return string or FALSE
*/
function AKFindParam($array, $key) {
    foreach ($array as $param) {
        if (in_array($key, $param)) {
            return $param['value'];
        }
    }

    return FALSE;
}

/**
 * Retourne le hash du mot de passe passé en paramètre
 *
 * @param string $passwd
 * @param integer $cost
 */
function AKHashPasswd($passwd, $cost) {

    $options = ['cost' => $cos];

    $hashPasswd = password_hash($passwd, PASSWORD_BCRYPT, $options);

    return $hashPasswd;
}

/**
 * Met à jour le mot de passe de l'utilisateur dans la DB
 * @param string $userid
 * @param string $passwd
 * @return array
 */
function AKChangePassword($userid, $passwd)
{
    
    try{
        
        $con = new Model('users');
        
        // Mise à jour de la table ressource
        $sql = $con->db->prepare(
        "UPDATE users 
         SET hpasswd = :passwd  
         WHERE id_user = :userid"
        );
    
        $status['request'] = $sql->execute(array(
            "userid" => $userid,
            "passwd" => $passwd
        ));
        
    }catch(PDOException $e) {
         $status['msg'] = "'UPDATE error  : '.$e->getMessage()";
         $sql_upd_res->closeCursor();
         return status;
    }    
       
    if ($status['request']) 
        $status['msg'] = T_('Mot de passe modifié avec succès');   
        
    return $status;
}

/**
* Retourne la valeur de la clé recherchée
*
* @param array $array
* @param string $search
*/
function KTFindKey($array, $search) {
    foreach ($array as $key => $val) {
        if ($key == $search) {
            return $val;
        }
    }

    return false;
}

/**
* Petite fonction qui converti une date au format FR vers EN
*
* @param date $mydate
*/
function convertDateFrToEn($mydate) {
    $array_date_fr = explode('/', $mydate);
    $date_us = $array_date_fr[2] . "-" . $array_date_fr[1] . "-" . $array_date_fr[0];
    return $date_us;
}

/**
* Petite fonction qui converti une date au format EN vers FR
*
* @param date $mydate
*/
function convertDateEnToFr($mydate) {
    @list($annee, $mois, $jour) = explode('-', $mydate);
    return @date('d/m/Y', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
* Retourne la chaîne passée en paramètre en majuscule
* Gère correctement les caractères accentués
* 
* @param string $string
*/
function strtoupperFr($string) {

    $string = strtoupper($string);
    $string = str_replace(
        array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
        array('É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Î', 'Ï', 'Ô', 'Ù', 'Û'), 
        $string 
    ); 
    return $string;

}

/**
* Génération aléatoire d'un nombre
* Par défaut sur 4 chiffres
* 
* @param integer $e
*/
function GENrands($e=4) {

    // Generation number
    $nrand = '';
    for($i=0;$i<$e;$i++)
    {
        $nrand .= mt_rand(1, 9);
    }

    // Return number.
    return $nrand;
}

/**
 * Test le paramètre passé à la fonction selon le type également passé en paramètre
 */
function AKcheckGetParam($param, $type)
{
    $check = false;
    switch ($type)
    {
        case 'NUMERIC':            
            if(is_numeric($param))
                $check = true;
            break;        
    }

    return $check;
}

/**
 * Vérifie si le compte utilisateur existe
 *
 * @param string $user
 */
function AKUserExist($user) {
    
    $con = new Model('users');
   
    $sql =  $con->db->prepare('SELECT status FROM ' . $con->table . ' WHERE login = :login ');    
    $req = $sql->execute(array("login" => $user));    
    $count = $sql->rowCount();

    if ($count == 1) {
        return true;
    } else
        return false;
}

/**
 * Fonction d'identification des utilisateurs
 * 11/04/2022 20:59
 *
 * @param string $user
 * @param string $passwd
 */
function AKIdentUser($user, $passwd)
{
    
    $options = ['cost' => 10 ];
    
    $con = new Model('users');

    $sql =  $con->db->prepare('SELECT * FROM ' . $con->table . ' WHERE login = :login');        
    $req = $sql->execute(array("login" => $user));
    
    $datas = $sql->fetch(PDO::FETCH_ASSOC);    
   
    //DEBUG//var_dump( $datas['passwd']); die();
    
    $samePasswords = password_verify($passwd,  $datas['passwd']);   

    // Si correspondance
    if ($samePasswords == true)
    {
        //DEBUG//echo 'identfication correcte '; die();
        
        // Si l'utilisateur est actif
        if ($datas['status']) 
        {
            $st['stat'] = true;
            $st['msg'] = 'You are identified';
            
            $st['login'] = $datas['login'];
            $st['firstname'] = $datas['firstname'];
            $st['lastname'] = $datas['lastname'];
            $st['email'] = $datas['email'];        
            $st['user_id'] = $datas['id'];
            
        }else{
            $st['stat'] = false;
            $st['msg'] = T_('Sorry, your user account is inactive');           
        }
        
    } else {        
       //DEBUG// echo 'idenitfication incorrecte '; die();
        
        $st['stat'] = false;
        $st['msg'] = T_('Your login and/or your password are incorrect - Please try again');       
    }
    
    return $st;
}


function GENRouteLink($link, $route = null)
{
    $prepath =  '';
    
    if ($route == 'page') {
        $prepath = '..' . DS . '..' . DS;
    } else{
        $prepath = '';
    }
    
    return $prepath.$link;
}



