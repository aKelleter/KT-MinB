<?php


/**
* Retourne la valeur du paramètre recherché
* @param array $array
* @param string $key
* @return string or FALSE
*/
function AKFindParam($array, $key) 
{
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
function AKHashPasswd($passwd, $cost) 
{

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
        $status['msg'] = 'Mot de passe modifié avec succès';   
        
    return $status;
}

/**
* Retourne la valeur de la clé recherchée
*
* @param array $array
* @param string $search
*/
function AKFindKey($array, $search) 
{
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
function convertDateFrToEn($mydate) 
{
    $array_date_fr = explode('/', $mydate);
    $date_us = $array_date_fr[2] . "-" . $array_date_fr[1] . "-" . $array_date_fr[0];
    return $date_us;
}

/**
* Petite fonction qui converti une date au format EN vers FR
*
* @param date $mydate
*/
function convertDateEnToFr($mydate) 
{
    @list($annee, $mois, $jour) = explode('-', $mydate);
    return @date('d/m/Y', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
* Retourne la chaîne passée en paramètre en majuscule
* Gère correctement les caractères accentués
* 
* @param string $string
*/
function strtoupperFr($string)
{

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
function GENrands($e=4)
{

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
function AKUserExist($user)
{
    
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
    
     $con = new Model('users');

    $sql =  $con->db->prepare('SELECT * FROM ' . $con->table . ' WHERE login = :login');        
    $req = $sql->execute(array("login" => $user));
    
    $db = $sql->fetch(PDO::FETCH_ASSOC);    
   
    //DEBUG//var_dump( $datas['passwd']); die();
    
    $samePasswords = password_verify($passwd,  $db['passwd']);   

    // Si correspondance
    if ($samePasswords == true)
    {
        //DEBUG//echo 'identfication correcte '; die();
        
        // Si l'utilisateur est actif
        if ($db['status']) 
        {
            $st['stat'] = true;
            $st['msg'] = 'You are identified';
            
            $st['login'] = $db['login'];
            $st['firstname'] = $db['firstname'];
            $st['lastname'] = $db['lastname'];
            $st['email'] = $db['email'];        
            $st['user_id'] = $db['id'];
            
        }else{
            $st['stat'] = false;
            $st['msg'] = 'Sorry but your user account is deactivated';           
        }
        
    } else {        
       //DEBUG// echo 'idenitfication incorrecte '; die();
        
        $st['stat'] = false;
        $st['msg'] = 'Your login and/or your password are incorrect - Please try again';       
    }
    
    return $st;
}

/**
 * Génère un lien valide en fonction de la route passée en paramètre
 * 
 * @param string $link
 * @param string $route
 * @return string
 */
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

/**
* Upload dans le filesystem un fichier
* 
* @param integer $index
* @param string $name
* @param string $destination
* @param octet $maxsize
* @param array $extensions
* 
* @return boolean
*/
function AKUploadFile( $index, $name, $destination, $maxsize = false, $extensions = false ) {

    // Test si le fichier est correctement uploadé
    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return false;

    // Test la taille limite
    if ($maxsize !== false AND $_FILES[$index]['size'] > $maxsize) return false;

    // Test de l'extension
    $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
    if ($extensions !== false AND !in_array($ext, $extensions)) return false;

    // Téléversement
    if(move_uploaded_file($_FILES[$index]['tmp_name'], ABSPATH.$destination.$name))
        return true;
    else
        return true;

}  

/**
* Upload dans le filesystem un fichier image
* 
* @param file $file
* @return string filename
*/
function AKuploadPicture( $file )
{

    $imgName = basename($_FILES["picture-art"]["name"]);
    try {

        // Instanciation de l'objet image avec le fichier passé en paramètre
        $img = new abeautifulsite\SimpleImage($_FILES["picture-art"]["tmp_name"]);

        // Enregistre l'image dans son format original dans le sous-répertoire original
        $img->save(D_PRIVATE.DS.D_DATAS.DS.D_IMG.DS.D_ORIGINAL.DS.$imgName);

        // Enregistre l'image dans un format thumbnail dans le sous-répertoire thumb
        $img->best_fit(350, 235);
        $img->save(D_PRIVATE.DS.D_DATAS.DS.D_IMG.DS.D_THUMB.DS.$imgName);

    } catch(Exception $e) {
        return false;
    }        
    return $imgName;

}

/**
* Retourne les éléments d'un tableau dans des badges
* 
* @param array $array
* @return string html
*/
function AKarrayInline($array)
{

    $html = NULL;
    foreach($array as $key => $value) {
        $html .= '<span class="badge wBlack">'.$value.'</span>, ';
    }

    // Suppression des deux derniers caractères (espace + virgule) de la chaîne
    $html = substr($html, 0, -2);

    return $html;

}

/**
* Filtre une chaîne de caractères
* Supprime les balises html et php
* et les espaces de début et de fin de chaîne
*
* @param   string $str.
* @return  string filtered
*/
function AKFiltre($str) 
{ 

    // strip_tags / rtrim / ltrim
    $str = strip_tags($str);
    $str = rtrim($str);
    $str = ltrim($str);
    return $str;
}


/**
*  Remplace caractères accentués d'une chaine 
* 
* @param string $str
* @return string
*/
function AKremoveAccents($str)
{
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
        'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã',
        'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
        'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ',
        'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę',
        'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī',
        'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ',
        'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ',
        'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 
        'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 
        'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ',
        'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');

    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O',
        'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c',
        'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
        'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D',
        'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
        'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K',
        'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
        'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S',
        's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W',
        'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i',
        'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
    return str_replace($a, $b, $str);
}

/**
* Retourne une chaîne de caractère sous la forme d'un slug
* 
* @param string $text
* @return string
*/
function AKSlugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // remove accents
    $text = AKremoveAccents($text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text))
    {
        return 'n-a';
    }

    return $text;
}

/**
* Convertit octets en taille de fichier lisible par l'homme  
* 
* @param mixed $bytes
* @return $result
*/
function fileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    if(isset($result))
        return $result;
    else
        return false;
}

/**
* Fonction de sécurité lors d'envoi de données en POST
* Retourne true si ok, false si un problème est détecté
*              
* @return bool
*/
function AKRefererControl()
{
    
    // Vérifier le protocol
    
    if(isset($_SERVER['HTTPS'])) {
        if($_SERVER['HTTPS'] == 'on')
            $nbcar = 8;
        else
            $nbcar = 7;         
    }else
        $nbcar = 7;
    
    // SI le referrer ne correspond pas on vide le $_POST
    if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' && substr($_SERVER['HTTP_REFERER'], $nbcar, strlen($_SERVER['SERVER_NAME'])) != $_SERVER['SERVER_NAME'])
    {
        $_POST = array();
        return false;             
    }else
        return true;
}

/**
 * print_r() préformaté
 * 
 * @param array $ar
 * @param string $type
 */
function AKPrintR($ar, $tag = null, $type = 'PRINTR') 
{
    switch ($type)
    {
        case 'PRINTR':
                echo '<pre class="w-undernav-msg w-frame"><p><u>AK DEBUGGING</u></p><i>' . $tag . '</i> : <br>';
                print_r($ar);
                echo '</pre>';
                break;
        case 'VARDUMP':
                echo '<pre class="w-undernav-msg w-frame"><p><u>AK DEBUGGING</u></p><i>' . $tag . '</i> : <br>';
                var_dump($ar);
                echo '</pre>';
                break;
    }
}

/**
 *  Vérifie que toutes les valeurs d'un tableau sont à true
 * 
 * @param type $array
 * @return boolean
 */
function AKAllIsTrue($array) 
{
    $size = count($array);
    $cpt = 0;

    foreach($array as $val)
        if($val) $cpt++;

    if($size == $cpt)
        return true;
    else 
        return false;
}

/**
 *  Vérifie que toutes les valeurs d'un tableau sont à null
 * 
 * @param type $array
 * @return boolean
 */
function AKAllIsNull($array) 
{
    $size = count($array);
    $cpt = 0;

    foreach($array as $val)
        if($val == null) $cpt++;

    if($size == $cpt)
        return true;
    else 
        return false;
}

/**
 *  Vérifie qu'une des valeurs d'un tableau sont à null
 * 
 * @param type $array
 * @return boolean
 */
function AKAllIsOneIsFalse($array) 
{
    $cpt = 0;

    foreach($array as $val)
        if($val == false) $cpt++;

    if($cpt > 0)
        return true;
    else 
        return false;
}
