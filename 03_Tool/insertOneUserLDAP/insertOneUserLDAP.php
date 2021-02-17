<?php

/*
 * Removed characters with accent
 */

function removeUnwantedCharacter($str) {
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
    $str = strtr( $str, $unwanted_array );

    return $str;
}


/*
 * Create a login
 */

function createLogin($nomComplet) {
    $login = strtolower(removeUnwantedCharacter($nomComplet));
  
    return $login;
}


/*
 * Create firstname
 */

function createFirstname($nomComplet) {
    $firstname = removeUnwantedCharacter(strtolower($nomComplet));

    return $firstname;
}


/*
 * Create lastname
 */

function createLastname($nomComplet) {
    $nomArr = explode(" ", $nomComplet);
    $lastname = removeUnwantedCharacter(strtolower($nomArr[0]));

    return $lastname;
}


/*
 * Create encrypted password from clear text
 */

function createPassword($clearPassword) {
    $output = shell_exec('slappasswd -s ' . $clearPassword. ' -h \{SSHA\}');

    return trim($output);
}


/*
 * Create a user ldif file
 */

function createUserLDIF($firstname, $lastname, $login, $password, $gid, $uid, $domain, $tld) {

    $fileTmp = <<< EOF
dn: cn=$firstname $lastname,dc=$domain,dc=$tld
cn: $firstname $lastname
givenName: $firstname
gidNumber: $gid
homeDirectory: /home/users/$login
sn: $lastname
objectClass: inetOrgPerson
objectClass: posixAccount
objectClass: top
uidNumber: $uid
uid: $login
loginShell: /bin/bash
userPassword: $password
EOF;

    $lineLog = $uid . ";" . $login . "\n";
    file_put_contents("userldap.log", $lineLog, FILE_APPEND | LOCK_EX);

    file_put_contents("ldif.tmp", $fileTmp);
}

/*
*
*/

function getLastUID() {
    $output = shell_exec("ldapsearch -Q -L -Y EXTERNAL -H ldapi:/// -b dc=college-vouziers,dc=fr | grep 'uidNumber' | sort -k2 -r");
    $lineLog = explode(PHP_EOL,$output);
    $uid = explode(" ", $lineLog[0]);
    return $uid[1];
}

/*
 * Insert a ldif file in the LDAP
 */

function insertUserInLDAP($domain, $tld) {
    $output = shell_exec("ldapadd -x -f ldif.tmp -W -D cn=admin,dc=$domain,dc=$tld");

    $lineLog = $output;
    file_put_contents("inldap.log", $lineLog, FILE_APPEND | LOCK_EX);
    
}


/*
 * Process one pupils
 * php -f insertOneUserLDAP.php firstname=Kane lastname=Solomon login=skane password=Mot2Passe
 */

unlink("userldap.log");
unlink("inldap.log");

parse_str(implode('&', array_slice($argv, 1)), $_GET);

$firstname = strtolower(createFirstname($_GET['firstname']));
$lastname = strtolower(createLastname($_GET['lastname']));
$login =  strtolower(createLogin($_GET['login']));
$passwordClear = $_GET['password'];
$password = createPassword($passwordClear);

// Replace the following values
$gid = 500; // Pupils group id
$uid = getLastUID() + 1; // User id 
$domain = "college-vouziers";
$tld = "fr";

echo $uid . ";" . $firstname . ";" . $lastname . ";" . $login  . ";" . $passwordClear . ";" . $password . "\n";

createUserLDIF($firstname, $lastname, $login, $password, $gid, $uid, $domain, $tld);
insertUserInLDAP($domain, $tld);


?>
