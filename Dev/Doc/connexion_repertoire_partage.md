### Configuration de la connexion LDAP vers le répertoire 'home' partagé en NFS


#### I/ Sur le client Raspberri Pi : Création du répertoire partagé.

Liens : 
[https://dadarevue.com/montage-nfs-partage-raspbian-raspberry/](https://dadarevue.com/montage-nfs-partage-raspbian-raspberry/)
[https://centurio.net/2018/10/28/howto-ensure-automatically-mounted-nfs-volume-on-raspbian-stretch/](https://centurio.net/2018/10/28/howto-ensure-automatically-mounted-nfs-volume-on-raspbian-stretch/)


#### 1/ Création du répertoire de montage.

```
pi@ordinateur:~ $ sudo mkdir /home/user
```


#### 2/ Fichier de configuration.

Ouvrir :

```
/etc/fstab
```


Ajouter à la fin : 

```
192.168.1.17:/srv/nfs/user            /home/user          nfs         defaults,_netdev,rw 0 0
```


#### 3/ Test.

```
pi@ordinateur:~ $ sudo mount -a
```

Sur le serveur : Créer un fichier dans le répertoire partagé NFS : /srv/nfs/user/
Sur le client : Vérifier la présence du fichier : /home/user/


#### 4/ Redémarrer le Raspberry Pi.


#### II/ Sur le client Raspberri Pi : Configuration LDAP.


#### 1/ Configuration de LDAP.

[https://github.com/HackTechDev/RezoRasPi/blob/main/Dev/Doc/05_installation_serveur_ldap.md](https://github.com/HackTechDev/RezoRasPi/blob/main/Dev/Doc/05_installation_serveur_ldap.md)
II/ Configuration de la page de connexion du Raspberry Pi.

[https://www.instructables.com/Make-Raspberry-Pi-do-LDAP-Authentication/](https://www.instructables.com/Make-Raspberry-Pi-do-LDAP-Authentication/)


#### 2/ Configuration de la création automatique de 'home'.

Ouvrir : 

```
/etc/pam.d/common-session
```

Chercher : 

```
# end of pam-auth-update config
```

Ajouter avant : 

```
session required pam_mkhomedir.so umask=027 skel=/etc/skel
```


#### 3/ Redémarrer le Raspberry Pi.


#### 4/ Test de connexion avec un utilisateur LDAP.


#### 5/ Pour éteindre, il faut se déconnecter vers la fenêtre de login et cliquer sur le bouton éteindre.



