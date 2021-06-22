#### Installation du serveur LDAP 


Distribution de travail : Ubuntu 20.04 LTS


### I/ Installation du serveur LDAP.

Liens : 
https://www.instructables.com/Make-Raspberry-Pi-into-a-LDAP-Server/
http://blog.crazypi.com/install-configure-basic-ldap-server-raspberry-pi/
https://openclassrooms.com/fr/courses/1733551-gerez-votre-serveur-linux-et-ses-services/5236036-installez-un-annuaire-ldap


### 1/ Modification du hostname.

```
util01@server:~$ sudo hostnamectl set-hostname college-vouziers.fr
```

```
util01@server:~$ hostname
college-vouziers.fr
```


### 2/ Nommage du hostname. 

Ouvrir : 

```
/etc/hosts
```

Chercher : 

```
127.0.1.1       server
```

Remplacer par : 

```
college-vouziers.fr
```


### 3/ Vérification.

```
util01@server:~$ hostnamectl
   Static hostname: college-vouziers.fr
```


### 4/ Installation des paquets LDAP.

```
util01@server:~$ sudo apt-get install slapd ldap-utils 
```

```
* Configuring slapd
  - Administator password : admin 
```


### 5/ Configuration. 

```
DIT : Directory Information Tree

DC : Domain Component

DN : Distinguished Name
dc=college-vouziers,dc=fr

CN : Common Name
Pour une personne, c’est en général le prénom + le nom de famille

O : Organization name.
```

```
util01@server:~$ sudo dpkg-reconfigure slapd
```

```
* Configuring slapd
  - Omit OpenLDAP server configuration? No
  - DN domain name: college-vouziers.fr
  - Organization name: salleinfo
  - Administrator password: admin
  - Confirm password: admin
  - Do you want to database to be removed when slapd is purged? No
  - Move old database? Yes
```


### 6/ Test.

```
util01@server:~$ sudo ldapsearch -Q -L -Y EXTERNAL -H ldapi:/// -b dc=college-vouziers,dc=fr
version: 1

#
# LDAPv3
# base <dc=college-vouziers,dc=fr> with scope subtree
# filter: (objectclass=*)
# requesting: ALL
#

# college-vouziers.fr
dn: dc=college-vouziers,dc=fr
objectClass: top
objectClass: dcObject
objectClass: organization
o: salleinfo
dc: college-vouziers

# admin, college-vouziers.fr
dn: cn=admin,dc=college-vouziers,dc=fr
objectClass: simpleSecurityObject
objectClass: organizationalRole
cn: admin
description: LDAP administrator

# search result

# numResponses: 3
# numEntries: 2
```


### 7/ Installation de l'outil de gestion.

Lien :
https://kifarunix.com/install-and-setup-phpldapadmin-on-ubuntu-20-04/

```
util01@college-vouziers:~$ sudo apt-get install phpldapadmin
```

- Adresse de l'application : 

```
http://192.168.1.42/phpldapadmin/
```

### 8/ Erreur : 

```
Deprecated: Array and string offset access syntax with curly braces is deprecated in /usr/share/phpldapadmin/lib/functions.php on line 1614
```

Solution : 

Ouvrir :

```
/usr/share/phpldapadmin/lib/functions.php
```

Chercher : 

```
'$' == $rdn{ strlen($rdn) - 1 })
```

Remplacer par : 

```
'$' == $rdn[ strlen($rdn) - 1 ])
```


### 9/ Configuration.

Ouvrir : 

```
/etc/phpldapadmin/config.php
```

Chercher : 

```
$servers->setValue('server','name','My LDAP Server');
```

Remplacer par : 

```
$servers->setValue('server','name','Salle info Server');
```

Rechercher et décommenter : 

```
$servers->setValue('server','port',389);
```

Chercher : 

```
$servers->setValue('server','base',array('dc=example,dc=com'));
```

Remplacer par : 

```
$servers->setValue('server','base',array('dc=college-vouziers,dc=fr'));
```

Chercher :

```
$servers->setValue('login','bind_id','cn=admin,dc=example,dc=com');
```

Remplacer par : 

```
$servers->setValue('login','bind_id','cn=admin,dc=college-vouziers,dc=fr');
```


### 10/ Connexion au serveur LDAP.

Adresse web de l'application :
http://192.168.1.42/phpldapadmin/


DN de connexion: cn=admin,dc=college-vouziers,dc=fr
Mot de passe: admin


### 11/ Création d'un utilisateur 'posixGroup'.

```
- [dc=college-vouziers,dc=fr (1)]
  - [Créer une nouvelle entrée ici]

    - [Générique : Groupe Posix]

       - Groupe : classe
       
       - [Créer un objet]
```
       

### 12/ Genération d'un mot de passe pour un utilisateur.

```
util01@college-vouziers:~$ slappasswd
New password: mot2passe
Re-enter new password: mot2passe
{SSHA}ZvPro77ltcs1b2bbsz5xjGbxmvOj9X+E
```

       
13/ Création d'un utilisateur posixAccount.

```
- dc=college-vouziers,dc=fr (2)
  - [Créer une nouvelle entrée ici]
  
    - [Générique : Compte Utilisateur]

      - Nom commun : samuelgondouin
      - Prénom : samuel
      - GID : classe
      - Répertoire personnel : /home/users/sgondouin
      - Nom de famille : gondouin
      - login shell: /bin/sh
      - Mot de passe : {SSHA}ZvPro77ltcs1b2bbsz5xjGbxmvOj9X+E
      - [ssha]
      - ID utilisateur : sgondouin
      
    - [Valider]
```
 

### 14/ Confirmation de la création de l'utilisateur.

```
util01@college-vouziers:~$ ldapsearch -H ldapi:/// -Y EXTERNAL -b "dc=college-vouziers,dc=fr" "(&(objectclass=posixAccount)(uid=samuelgondouin))" -LLL -Q
util01@college-vouziers:~$ ldapsearch -H ldapi:/// -Y EXTERNAL -b "dc=college-vouziers,dc=fr" "(&(objectclass=posixGroup)(cn=samuelgondouin))" -LLL -Q
```


### 15/ Test. 

```
util01@college-vouziers:~$ sudo ldapsearch -Q -L -Y EXTERNAL -H ldapi:/// -b dc=college-vouziers,dc=fr
version: 1

#
# LDAPv3
# base <dc=college-vouziers,dc=fr> with scope subtree
# filter: (objectclass=*)
# requesting: ALL
#

# college-vouziers.fr
dn: dc=college-vouziers,dc=fr
objectClass: top
objectClass: dcObject
objectClass: organization
o: salleinfo
dc: college-vouziers

# admin, college-vouziers.fr
dn: cn=admin,dc=college-vouziers,dc=fr
objectClass: simpleSecurityObject
objectClass: organizationalRole
cn: admin
description: LDAP administrator

# classe, college-vouziers.fr
dn: cn=classe,dc=college-vouziers,dc=fr
gidNumber: 500
cn: classe
objectClass: posixGroup
objectClass: top

# samuel gondouin, college-vouziers.fr
dn: cn=samuel gondouin,dc=college-vouziers,dc=fr
cn: samuel gondouin
givenName: samuel
gidNumber: 500
homeDirectory: /home/users/sgondouin
sn: gondouin
objectClass: inetOrgPerson
objectClass: posixAccount
objectClass: top
uidNumber: 1000
uid: sgondouin
loginShell: /bin/bash

# search result

# numResponses: 5
# numEntries: 4
```


### 16/ Vérification de l'annuaire dans le système de fichier.

```
util01@college-vouziers:~$ sudo su
root@college-vouziers:/home/util01# ls -l /var/lib/ldap/
total 112
-rw------- 1 openldap openldap 110592 déc.  29 22:42 data.mdb
-rw------- 1 openldap openldap   8192 janv.  8 00:34 lock.mdb
```


### II/ Configuration de la page de connexion du Raspberry Pi.

Lien :
https://www.instructables.com/Make-Raspberry-Pi-a-Multi-User-Desktop/


### 1/ A partir du serveur, connexion sur un Raspberry Pi.

```
util01@college-vouziers:~$ ssh pi@192.168.2.64
...
pi@raspberrypi:~ $ 
```


### 2/ Configuration de la page de login : Désactivation de la connexion automatique de l'utilisateur par défaut 'pi'.

Ouvrir : 

```
/etc/lightdm/lightdm.conf
```

Chercher :

```
autologin-user=pi
```

Commenter : 

```
#autologin-user=pi
```


### 3/ Configuration de la page de login : Activation de la liste des utilisateurs.

Ouvrir : 

```
/etc/lightdm/lightdm.conf
```

Chercher :

```
greeter-hide-users=
```

Remplacer par : 

```
greeter-hide-users=true
```


### 4/ Activation d'un mot de passe pour l'utilisateur 'root' et de l'utilisateur 'pi'.

Lien :
https://www.instructables.com/Give-User-Shutdown-and-Reboot-Privileges-in-Raspbe/

```
pi@raspberrypi:~ $ sudo passwd root
New password: adminm2p
Retype new password: adminm2p
passwd: password updated successfully
```

```
pi@raspberrypi:~ $ sudo passwd pi
New password: pim2p
Retype new password: pim2p 
passwd: password updated successfully
```


### III/ Installation du client LDAP sur le Raspberry Pi.

Lien : 
https://www.instructables.com/Make-Raspberry-Pi-do-LDAP-Authentication/


Sur le Raspberry Pi.

### 1/ Changement du 'hostname'.

Ouvrir : 

```
/etc/hostname
```

Supprimer tout.

Remplacer par : 

```
ordinateur.college-vouziers.fr
```


### 2/ Installation des paquets client de LDAP.

```
pi@raspberrypi:~ $ sudo apt-get install libnss-ldapd
```

```
- Configuring nslcd
  - LDAP server URI: ldap://192.168.1.42
  - LDAP server search base: dc=college-vouziers,dc=fr
  
- Configuring libnss-ldapd
  - name services to configure: <ne rien sélectionner>
```
  

### 3/ Configuration de  Name Service LDAP Connection Daemon (nslcd).

Ouvrir : 

```
/etc/nslcd.conf
```

Chercher :

```
uri ldap
```

Remplacer par :

```
uri ldap://192.168.1.42
```

Chercher : 
 
```
base dc=
```

Remplacer par : 

```
base dc=college-vouziers,dc=fr  
```
  
  
### 4/ Configuration de Name Service Switch.

Ouvrir : 

```
/etc/nsswitch.conf
```

Supprimer tout.

Remplacer par : 

```
passwd:         compat ldap
group:          compat ldap
shadow:         compat ldap
hosts:          files dns
networks:       files
protocols:      db files
services:       db files
ethers:         db files
rpc:            db files
netgroup:       nis  
```
  
  
### 5/ Configuration de Pluggable Authentication Modules (PAM).

```
pi@raspberrypi:~ $ sudo pam-auth-update
```

```
- PAM configuration
  - Unix Authentication : *
    LDAP Autnetication : *
    Register user sessions in the systemd control group hierarchy : *
    Create home directory on login
    Register check to see if default password has been changed when SSh is enabled  
```


### 6/ Arrêt et redémarrage des services.

```
pi@raspberrypi:~ $ sudo service nslcd stop
pi@raspberrypi:~ $ sudo service nslcd start
```

```
pi@raspberrypi:~ $ sudo service nscd stop
pi@raspberrypi:~ $ sudo service nscd start
```


### 7/ Test de connexion.

```
pi@raspberrypi:~ $ su - sgondouin
Password: 
Creating directory '/home/users/sgondouin'.

Wi-Fi is currently blocked by rfkill.
Use raspi-config to set the country before use.
```


### 8/ Redémarrage du client Raspberry Pi.


### 9/ Test de la connexion avec un utilisateur.

Soit : 

```
Utilisateur : sgondouin
Mot de passe : mot2passe
```

```
sgondouin@raspberrypi:~ $ pwd
/home/users/sgondouin
```


### IV/ Activation des logs du serveur.

Liens : 
http://tutoriels.meddeb.net/openldap-log-2/
https://medium.com/@Dylan.Wang/how-to-enable-openldap-log-file-on-ubuntu-5abd4e44a8c


### 1/ Création du fichier de configuration pour l'activation des log.

Créer : 

```
add_slapdlog.ldif
```

Ajouter : 

```
dn: cn=config
changeType: modify
replace: olcLogLevel
olcLogLevel: stats
```


### 2/ Execution du fichier de configuration add_slapdlog.ldif

```
util01@college-vouziers:~$ sudo ldapmodify -Y external -H ldapi:/// -f add_slapdlog.ldif
```


### 3/ Fichier de configuration de Syslog.

Ouvrir : 

```
/etc/rsyslog.d/10-slapd.conf
```

Ajouter :

```
$template slapdtmpl,"[%$DAY%-%$MONTH%-%$YEAR% %timegenerated:12:19:date-rfc3339%] %app-name% %syslogseverity-text% %msg%\n"
local4.*    /var/log/slapd.log;slapdtmpl
```


### 4/ Rédemarrage du service syslog.

```
util01@college-vouziers:~$ sudo service rsyslog restart
```


### 5/ Test.

- Log pour une recherche : 

```
util01@college-vouziers:~$ sudo ldapsearch -Q -L -Y EXTERNAL -H ldapi:/// -b dc=college-vouziers,dc=fr
```

```
util01@college-vouziers:~$ sudo cat /var/log/slapd.log
...
[30-12-2020 07:52:26] slapd debug  conn=1016 op=2 SRCH base="dc=college-vouziers,dc=fr" scope=2 deref=0 filter="(&(objectClass=posixAccount)(uid=util01))"
...
[30-12-2020 07:52:26] slapd debug  conn=1017 fd=19 closed
...
```


- Log pour une connexion : 

```
util01@college-vouziers:~$ sudo cat /var/log/slapd.log
...
[30-12-2020 07:55:36] slapd debug  conn=1024 op=8 SRCH base="dc=college-vouziers,dc=fr" scope=2 deref=0 filter="(&(objectClass=posixAccount)(uid=jbraquet))"
...
[30-12-2020 07:59:37] slapd debug  conn=1020 op=20 SRCH base="dc=college-vouziers,dc=fr" scope=2 deref=0 filter="(&(objectClass=shadowAccount)(uid=sgondouin))"
...
```
