Création d'autres images
========================

Il est possible de créer d'autres images Raspberry Pi OS en suivant les points 6/ à 12/ de la documentation 'Installation de serveurs DHCP et NFS'.
Ensuite, il faudra créer des liens symboliques correspondant aux Raspberry Pi qui seront lié à ces images grâce au point 13/.
Il ne faudra pas oublier d'indiquer la configuration de boot en suivant le point 23/.


1/ Depuis un Raspberry Pi, customiser la distribution Raspberry Pi OS.
Ajouter et/ou supprimer les logiciels.


2/ Enlever la carte micro-sd


3/ Insérer la carte micro-sd dans l'ordinateur faisant office de serveur.
Les partitions 'boot' et 'root' sont automatiquement montées.

util01@server:~$ ls -l /media/util01/
total 8
drwxr-xr-x  3 util01 util01 3584 janv.  1  1970 boot
drwxr-xr-x 18 root   root   4096 déc.   2 14:04 rootfs


4/ Création du répertoire de l'image custom de Raspberry Pi OS.

util01@server:~$ sudo mkdir -p /srv/nfs/rpi4-image_custom


5/ Copie des fichiers de l’image custom vers le répertoire réseau spécifique à un Raspberry Pi. 

util01@server:~$ sudo cp -a /media/util01/rootfs/* /srv/nfs/rpi4-image_custom/
util01@server:~$ sudo cp -a /media/util01/boot/* /srv/nfs/rpi4-image_custom/boot/



15/ Suppression des fichiers de démarrage par défauts.

util01@server:~$ sudo rm /srv/nfs/rpi4-image_custom/boot/start4.elf
util01@server:~$ sudo rm /srv/nfs/rpi4-image_custom/boot/fixup4.dat


16/ Téléchargement des fichiers de démarrage dans leurs dernières versions.

util01@server:~$ wget https://github.com/Hexxeh/rpi-firmware/raw/stable/start4.elf 
util01@server:~$ wget https://github.com/Hexxeh/rpi-firmware/raw/stable/fixup4.dat


17/ Copie des nouveaux fichiers de démarrage.

util01@server:~$ sudo cp start4.elf /srv/nfs/rpi4-image_custom/boot/
util01@server:~$ sudo cp fixup4.dat /srv/nfs/rpi4-image_custom/boot/



18/ Configuration du serveur NFS.

Ouvrir :

/etc/exports

ajouter a la fin :

/srv/nfs/rpi4-image *(rw,sync,no_subtree_check,no_root_squash)


19/ Pour un Raspberry 4, création du lien symbolique dont le nom est l'adresse MAC du Raspberry Pi.

util01@server:~$ cd /srv/tftpboot/

L'adresse MAC est en minuscule, les deux-points sont remplacés par un tiret.

util01@server:/srv/tftpboot$ sudo ln -s /srv/nfs/rpi4-image_custom/boot/ dc-a6-32-22-ce-87


22/ Activation du service SSH au démarrage.

util01@server:~$ sudo touch /srv/nfs/rpi4-image_custom/boot/ssh


23/ Configuration du fichier de démarrage du Raspberry Pi

Elle permet d'indiquer quelle image Raspberry OS à utiliser.

Ouvrir : 

/srv/nfs/rpi4-image_custom/boot/cmdline.txt

Remplacer tout par :
# Sur une seule ligne !!!!!

console=serial0,115200 console=tty root=/dev/nfs nfsroot=192.168.2.100:/srv/nfs/rpi4-image_custom,vers=3,proto=tcp rw ip=dhcp rootwait elevator=deadline


20/ Redémarre le serveur


