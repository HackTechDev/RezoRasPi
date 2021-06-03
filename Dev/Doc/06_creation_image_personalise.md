#### Création d'image personalisé


Lien :
https://medium.com/platformer-blog/creating-a-custom-raspbian-os-image-for-production-3fcb43ff3630


Il est possible de créer d'autres images Raspberry Pi OS en suivant les points 6/ à 12/ de la documentation 'Installation de serveurs DHCP et NFS'.
Ensuite, il faudra créer des liens symboliques correspondant aux Raspberry Pi qui seront lié à ces images grâce au point 13/.
Il ne faudra pas oublier d'indiquer la configuration de boot en suivant le point 23/.


### 1/ Depuis un Raspberry Pi, customiser la distribution Raspberry Pi OS.
Ajouter et/ou supprimer les logiciels.


### 2/ Enlever la carte micro-sd


### 3/ Insérer la carte micro-sd dans l'ordinateur faisant office de serveur.
Ne pas effectuer le montage de la carte.


### 4/ Création du répertoire de travail.

```
util01@station66:~$ mkdir -p RASPI_Custom
util01@station66:~$ cd RASPI_Custom/
util01@station66:~/RASPI_Custom$ 
```

### 5/ Détecter les différentes partitions de la carte SD.

```
util01@station01:~/RASPI_Custom$ sudo fdisk -l
...
Disque /dev/mmcblk0 : 14,9 GiB, 15931539456 octets, 31116288 secteurs
Unités : secteur de 1 × 512 = 512 octets
Taille de secteur (logique / physique) : 512 octets / 512 octets
taille d'E/S (minimale / optimale) : 512 octets / 512 octets
Type d'étiquette de disque : dos
Identifiant de disque : 0xc2c0b0d2

Périphérique   Amorçage  Début      Fin Secteurs Taille Id Type
/dev/mmcblk0p1            8192   532479   524288   256M  c W95 FAT32 (LBA)
/dev/mmcblk0p2          532480 31116287 30583808  14,6G 83 Linux
```


### 6/ Clonage de la carte SD.

```
util01@station01:~/RASPI_Custom$ sudo dd if=/dev/mmcblk0 of=raspi_clone.img
31116288+0 enregistrements lus
31116288+0 enregistrements écrits
15931539456 bytes (16 GB, 15 GiB) copied, 477,821 s, 33,3 MB/s

util01@station01:~/RASPI_Custom$ ls -l raspi_clone.img 
-rw-r--r-- 1 root root 15931539456 janv. 12 15:04 raspi_clone.img

util01@station01:~/RASPI_Custom$ ls -lh raspi_clone.img 
-rw-r--r-- 1 root root 15G janv. 12 15:04 raspi_clone.img
```


### 7/ Réduction de la taille de l'image.

Lien : 
https://github.com/Drewsif/PiShrink

- Téléchargement de l'outil :

```
util01@station01:~/RASPI_Custom$ wget  https://raw.githubusercontent.com/Drewsif/PiShrink/master/pishrink.sh
```

- Changement de permission du script :

```
util01@station01:~/RASPI_Custom$ chmod +x pishrink.sh
```

- Installation : 

```
util01@station01:~/RASPI_Custom$ sudo mv pishrink.sh /usr/local/bin
```


- Réduction de l'image : 

```
util01@station01:~/RASPI_Custom$ sudo pishrink.sh raspi_clone.img raspi_clone-shrink.img
pishrink.sh v0.1.2
pishrink.sh: Copying raspi_clone.img to raspi_clone-shrink.img... ...
pishrink.sh: Gathering data ...
Creating new /etc/rc.local
pishrink.sh: Checking filesystem ...
rootfs : 126657/943488 fichiers (0.2% non contigus), 1059495/3822976 blocs
resize2fs 1.44.1 (24-Mar-2018)
pishrink.sh: Shrinking filesystem ...
resize2fs 1.44.1 (24-Mar-2018)
En train de redimensionner le système de fichiers sur /dev/loop0 à 1509173 (4k) blocs.
Début de la passe 2 (max = 16480)
Relocalisation de blocs       XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Début de la passe 3 (max = 117)
Examen de la table d'i-noeuds XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Début de la passe 4 (max = 10436)
Mise à jour des références d'i-noeudsXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Le système de fichiers sur /dev/loop0 a maintenant une taille de 1509173 blocs (4k).

pishrink.sh: Shrinking image ...
pishrink.sh: Shrunk raspi_clone-shrink.img from 15G to 6,1G ...
```

- Vérification :

```
util01@station01:~/RASPI_Custom$ ls -l raspi_clone-shrink.img 
-rw-r--r-- 1 root root 6454202880 janv. 12 15:14 raspi_clone-shrink.img
```

```
util01@station01:~/RASPI_Custom$ ls -lh raspi_clone-shrink.img 
-rw-r--r-- 1 root root 6,1G janv. 12 15:14 raspi_clone-shrink.img
```

- Compression de l'image :

```
util01@station01:~/RASPI_Custom$ gzip -9 raspi_clone-shrink.img 
```

- Vérification : 

```
util01@station01:~/RASPI_Custom$ ls -l raspi_clone-shrink.img.gz 
-rw-r--r-- 1 util01 util01 2720512314 janv. 12 15:14 raspi_clone-shrink.img.gz
```

```
util01@station01:~/RASPI_Custom$ ls -l raspi_clone-shrink.img.gz 
-rw-r--r-- 1 util01 util01 2720512314 janv. 12 15:14 raspi_clone-shrink.img.gz
```

- Décompression de l'image : 

```
util01@college-vouziers:~/RASPI_Custom$ gzip -d raspi_clone-shrink.img.gz 
```


### 8/ Création des mappes de périphérique à partir de l’image.

```
util01@college-vouziers:~/RASPI_Custom$ sudo kpartx -a -v raspi_clone-shrink.img
add map loop9p1 (253:0): 0 524288 linear 7:9 8192
add map loop9p2 (253:1): 0 12073385 linear 7:9 532480
```


### 9/ Création des répertoires de montage.

```
util01@college-vouziers:~/RASPI_Custom$ mkdir rootmnt
util01@college-vouziers:~/RASPI_Custom$ mkdir bootmnt
```


### 10/ Montage les devices vers les répertoires de montage.

```
util01@college-vouziers:~/RASPI_Custom$ sudo mount /dev/mapper/loop9p1 bootmnt/
util01@college-vouziers:~/RASPI_Custom$ sudo mount /dev/mapper/loop9p2 rootmnt/
```


### 11/ Création du répertoire de l'image custom de Raspberry Pi OS.

```
util01@college-vouziers:~/RASPI_Custom$ sudo mkdir -p /srv/nfs/rpi4-image_custom
```


### 12/ Copie des fichiers de l’image vers le répertoire réseau spécifique à un Raspberry Pi. 

```
util01@college-vouziers:~/RASPI_Custom$ sudo cp -a rootmnt/* /srv/nfs/rpi4-image_custom/
util01@college-vouziers:~/RASPI_Custom$ sudo cp -a bootmnt/* /srv/nfs/rpi4-image_custom/boot/
```


### 13/ Suppression des fichiers de démarrage par défauts.

```
util01@college-vouziers:~/RASPI_Custom$ sudo rm /srv/nfs/rpi4-image_custom/boot/start4.elf
util01@college-vouziers:~/RASPI_Custom$ sudo rm /srv/nfs/rpi4-image_custom/boot/fixup4.dat
```


### 14/ Téléchargement des fichiers de démarrage dans leurs dernières versions.

```
util01@college-vouziers:~/RASPI_Custom$ wget https://github.com/Hexxeh/rpi-firmware/raw/stable/start4.elf 
util01@college-vouziers:~/RASPI_Custom$ wget https://github.com/Hexxeh/rpi-firmware/raw/stable/fixup4.dat
```


### 15/ Copie des nouveaux fichiers de démarrage.

```
util01@college-vouziers:~/RASPI_Custom$ sudo cp start4.elf /srv/nfs/rpi4-image_custom/boot/
util01@college-vouziers:~/RASPI_Custom$ sudo cp fixup4.dat /srv/nfs/rpi4-image_custom/boot/
```


### 16/ Configuration du serveur NFS.

Ouvrir :

```
/etc/exports
```

ajouter a la fin :

```
/srv/nfs/rpi4-image *(rw,sync,no_subtree_check,no_root_squash)
```


### 17/ Pour un Raspberry 4, création du lien symbolique dont le nom est l'adresse MAC du Raspberry Pi.

```
util01@college-vouziers:~/RASPI_Custom$ cd /srv/tftpboot/
```

L'adresse MAC est en minuscule, les deux-points sont remplacés par un tiret.

```
util01@server:/srv/tftpboot$ sudo ln -s /srv/nfs/rpi4-image_custom/boot/ dc-a6-32-b7-fd-78
```


### 18/ Suppression les lignes contenant 'UUID' dans le fichier '/etc/fstab'. de chaque Raspberry

Ouvrir :

```
/srv/nfs/rpi4-image/etc/fstab 
```

Supprimer toutes les lignes incluant 'UUID'.


### 19/ Activation du service SSH au démarrage.

```
util01@college-vouziers:~/RASPI_Custom$ sudo touch /srv/nfs/rpi4-image_custom/boot/ssh
```


### 20/ Configuration du fichier de démarrage du Raspberry Pi

Elle permet d'indiquer quelle image Raspberry OS à utiliser.

Ouvrir : 

```
/srv/nfs/rpi4-image_custom/boot/cmdline.txt
```

Remplacer tout par :
/!\ Sur une seule ligne !!!!!  /!\

```
console=serial0,115200 console=tty root=/dev/nfs nfsroot=192.168.2.100:/srv/nfs/rpi4-image_custom,vers=3,proto=tcp rw ip=dhcp rootwait elevator=deadline
```


### 21/ Redémarrer le serveur


### 22/ Redémarrer le Raspberry Pi.

