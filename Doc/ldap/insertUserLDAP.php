<?php

function removeUnwantedCharacter($str) {
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
    $str = strtr( $str, $unwanted_array );

    return $str;
}

function createLogin($nomComplet) {
    $nomArr = explode(" ", $nomComplet);
    $login = removeUnwantedCharacter(strtolower(substr($nomArr[1], 0, 1)) .  strtolower($nomArr[0]));
  
    return $login;
}

function createFirstname($nomComplet) {
    $nomArr = explode(" ", $nomComplet);
    $firstname = removeUnwantedCharacter(strtolower($nomArr[1]));

    return $firstname;
}

function createLastname($nomComplet) {
    $nomArr = explode(" ", $nomComplet);
    $lastname = removeUnwantedCharacter(strtolower($nomArr[0]));

    return $lastname;
}


function createPassword($clearPassword) {
    $output = shell_exec('slappasswd -s ' . $clearPassword. ' -h \{SSHA\}');

    return trim($output);
}


function createUserLDIF($firstname, $lastname, $login, $password, $uid, $domain, $tld) {

    $fileTmp = <<< EOF
dn: cn=$firstname $lastname,dc=$domain,dc=$tld
cn: $firstname $lastname
givenName: $firstname
gidNumber: 500
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


function insertUserInLDAP($domain, $tld) {
    $output = shell_exec("ldapadd -x -f ldif.tmp -W -D cn=admin,dc=$domain,dc=$tld");

    $lineLog = $output;
    file_put_contents("inldap.log", $lineLog, FILE_APPEND | LOCK_EX);
    
}


unlink("userldap.log");
unlink("inldap.log");

$row = 0;
if (($handle = fopen("eleves_iaca.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    if($row == 0 ) {
        echo "";
    } else {
        $num = count($data);

        $firstname = createFirstname($data[0]);
        $lastname = createLastname($data[0]);
        $login =  createLogin($data[0]);
        $passwordClear = $data[8];
        $password = createPassword($passwordClear);
        $uid = 1005 + $row;
        $domain = "college-vouziers";
        $tld = "fr";

        echo $row . ";" . $uid . ";" . $firstname . ";" . $lastname . ";" . $login  . ";" . $passwordClear . ";";
        echo $password . ";";

        createUserLDIF($firstname, $lastname, $login, $password, $uid, $domain, $tld);
        insertUserInLDAP($domain, $tld);

        echo "\n";
    }
    $row++;
  }
  fclose($handle);
}

?>
