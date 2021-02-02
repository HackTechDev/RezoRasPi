Documentation spécifique pour la salle de technologie du collègue de Vouziers
=============================================================================


1/ Depuis un poste Raspberry Pi, impossible de faire un mise-à-jour du système.

Solution : 

Changer l'adresse du dépôt principal.

Ouvrir : 

etc/apt/sources.list.d/raspi.list

Remplacer tout par : 

deb http://raspbian.42.fr/debian/ buster main


2/ Depuis le serveur, création de l'archive de sauvegarde de l'image Raspberry OS.

- Aller dans le répertoire de l'image :

```
util01@college-vouziers:~$ cd /srv/nfs/
```

- Listage des images Raspberry OS disponible :

```
util01@college-vouziers:/srv/nfs$ ls -l
total 8
drwxr-xr-x 18 root root 4096 déc.  14 18:51 rpi4-image
drwxr-xr-x 18 root root 4096 janv. 12 18:11 rpi4-image_custom
```

- Création de l'archive de l'archive :

```
util01@college-vouziers:/srv/nfs$ sudo tar cvf rpi4-image_210201.tar rpi4-image
```

- Vérification : 

```
util01@college-vouziers:/srv/nfs$ ls *.tar
rpi4-image_210201.tar
```


3/ Depuis un poste Raspberry Pi, erreur de hostname : 

```
sudo: unable to resolve host (none)
```

Solution :

Ouvrir : 

```
/etc/hosts
```
Ajouter :

```
127.0.1.1 raspberry.technovz-serveur-rasp 
```

Supprimer : 

```
127.0.1.1 raspberry
```


4/ Lancement de l'IDE Arduino en root via un utilisateur normal.

- Lancement de Arduino : 

```
sgondouin@ordinateur:~ $ arduino
```


- La fenêtre "Arduino Permission Checker" s'affiche : 

```
You need to be added to the "dialout"
group to upload code to an Arduino
microcontroller over the USB or 
serial ports.
Click "Add" below to be added.
You must log out and log in again 
befor  any group changes
will take effect.
```

Cliquer sur le bouton [Add].

- Se déloguer et se reloguer.

 




