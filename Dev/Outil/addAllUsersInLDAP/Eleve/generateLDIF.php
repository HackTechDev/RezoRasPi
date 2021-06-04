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
    $firstname = removeUnwantedCharacter(strtolower(trim($nomComplet)));

    return $firstname;
}


/*
 * Create lastname
 */

function createLastname($nomComplet) {
    $lastname = removeUnwantedCharacter(strtolower(trim($nomComplet)));

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

    // Create the user ldif

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

     $fileTmp = $fileTmp . "\n";

    // Log

    //$lineLog = $uid . ";" . $login . "\n";
    //file_put_contents("userldap.log", $lineLog, FILE_APPEND | LOCK_EX);


    // Create the ldif single file

    file_put_contents("user.ldif.tmp", $fileTmp, FILE_APPEND | LOCK_EX);
}


/*
 * Process the pupils file
 */

//unlink("userldap.log");
//unlink("inldap.log");

$row = 0;
if (($handle = fopen("export_eleve_juin2020.txt", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    if($row == 0 ) {
        echo "";
    } else {
        $num = count($data);

        $firstname = createFirstname($data[2]);
        $lastname = createLastname($data[0]);
        $login =  createLogin($data[1]);
        $passwordClear = $data[8];
        $password = createPassword($passwordClear);

        // Replace the following values
        $gid = 500; // Pupils group id
        $uid = 1100 + $row; // User id 
        $domain = "college-vouziers";
        $tld = "fr";

        echo $row . ";" . $uid . ";" . $firstname . ";" . $lastname . ";" . $login  . ";" . $passwordClear . ";";
        echo $password . ";";

        createUserLDIF($firstname, $lastname, $login, $password, $gid, $uid, $domain, $tld);
        
        echo "\n";
    }
    $row++;
  }
  fclose($handle);
}


?>
