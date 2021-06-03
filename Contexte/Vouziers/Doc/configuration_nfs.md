#### Configuration NFS


### 3/ Répertoire home séparé.

Le device /dev/sdb contiendra le home de tous les utilisateurs, il sera donc monté dans le répertoire /srv/nfs/user


- Vérification :

```
technovz@technovz-serveur-rasp:~$ sudo fdisk -l 
...
Disque /dev/sdb : 1,84 TiB, 2000398934016 octets, 3907029168 secteurs
Disk model: ST2000DM001-9YN1
Unités : secteur de 1 × 512 = 512 octets
Taille de secteur (logique / physique) : 512 octets / 4096 octets
taille d'E/S (minimale / optimale) : 4096 octets / 4096 octets
```

```
Disque /dev/sda : 111,81 GiB, 120034123776 octets, 234441648 secteurs
Disk model: Samsung SSD 840 
Unités : secteur de 1 × 512 = 512 octets
Taille de secteur (logique / physique) : 512 octets / 512 octets
taille d'E/S (minimale / optimale) : 512 octets / 512 octets
Type d'étiquette de disque : gpt
Identifiant de disque : 871AE86D-5567-4329-8368-4E20C40A666F

Périphérique   Début       Fin  Secteurs Taille Type
/dev/sda1       2048   1050623   1048576   512M Système EFI
/dev/sda2    1050624 234440703 233390080 111,3G Système de fichiers Linux
```

Le disque /dev/sdb sera le disque home.


- Partitionnement : 

```
technovz@technovz-serveur-rasp:~$ sudo apt-get install gparted
```

```
technovz@technovz-serveur-rasp:~$ sudo gparted
``` 


- Vérification : 

```
technovz@technovz-serveur-rasp:~$ sudo fdisk -l
...
Disque /dev/sdb : 1,84 TiB, 2000398934016 octets, 3907029168 secteurs
Disk model: ST2000DM001-9YN1
Unités : secteur de 1 × 512 = 512 octets
Taille de secteur (logique / physique) : 512 octets / 4096 octets
taille d'E/S (minimale / optimale) : 4096 octets / 4096 octets
Type d'étiquette de disque : dos
Identifiant de disque : 0x806c4245

Périphérique Amorçage Début        Fin   Secteurs Taille Id Type
/dev/sdb1              2048 3907028991 3907026944   1,8T 83 Linux
```


- Identification de l'uuid : 

```
technovz@technovz-serveur-rasp:~$ sudo blkid
...
/dev/sdb1: LABEL="user" UUID="a260f08f-d98b-4d19-8233-1077904fefcb" TYPE="ext4" PARTUUID="806c4245-01"
```


- Sauvegarde du fichier fstab :

```
technovz@technovz-serveur-rasp:~$ sudo cp /etc/fstab /etc/fstab.save
```


- Création du répertoire de montage :

```
technovz@technovz-serveur-rasp:~$ sudo mkdir -p /srv/nfs/user
```


- Ajout du disque de donnée dans le fichier fstab : 

Ouvrir : 

```
/etc/fstab
```

Chercher :

```
UUID=
```


Ajouter après : 

```
UUID=a260f08f-d98b-4d19-8233-1077904fefcb /srv/nfs/user              ext4    errors=remount-ro 0       2
```


- Redémarrer le serveur.


- Vérification : 

```
technovz@technovz-serveur-rasp:~$ df -h
Sys. de fichiers Taille Utilisé Dispo Uti% Monté sur
...
/dev/sdb1          1,8T     68M  1,7T   1% /srv/nfs/user
```


- Configuration du fichier d'exportation de NFS :

Ouvrir : 

```
/etc/exports
```

Ajouter :

```
/srv/nfs/user *(rw,sync,no_subtree_check,no_root_squash)
```


- Démarrage de tous les services.

```
util01@server:~/RASPSERVER$ sudo systemctl start rpcbind
util01@server:~/RASPSERVER$ sudo systemctl start nfs-server
util01@server:~/RASPSERVER$ sudo systemctl restart nfs-kernel-server
```


- Listage du point de montage : 

```
technovz@technovz-serveur-rasp:~/DOC/RezoRasPi$ mount -l
...
/dev/sdb1 on /srv/nfs/user type ext4 (rw,relatime,errors=remount-ro) [user]
...
```


- Exporter :

```
technovz@technovz-serveur-rasp:~/DOC/RezoRasPi$ sudo exportfs -a
```


- Sur un Rasp :

```
pi@raspberry:~ $ sudo su
root@raspberry:/home/pi# 
```

```
root@raspberry:~# mkdir -p /home/user
```


- Visualiser le point de montage de la partition des utilisateurs :

```
pi@raspberry:~ $ showmount -e 192.168.1.100
Export list for 192.168.1.100:
/srv/nfs/user       *
/srv/nfs/rpi4-image *
```

```
CACHIERE@raspberry:~ $ showmount -e 192.168.1.100
Export list for 192.168.1.100:
/srv/nfs/user       *
/srv/nfs/rpi4-image *
```

- Monter le point de montage :

```
root@raspberry:~# mount -t nfs 192.168.1.100:/srv/nfs/user /home/users
root@raspberry:~# ls -l /home/user/
total 16
drwx------ 2 root root 16384 févr.  3 13:05 lost+found
```


- Configuration du point de montage : 

Ouvrir :

```
/etc/fstab
```

Ajouter : 

```
192.168.1.100:/srv/nfs/user /home/user nfs soft,timeo=5,intr,rsize=8192,wsize=8192  0  0
```


- Rédemarrer le client Raspi


- Se connecter avec un utilisateur.


- Vérification :

```
pi@raspberry:~ $ ls -l /home/users/
total 20
drwxr-xr-x 13 BOURSCHEIDT eleve  4096 févr.  3 15:32 BOURSCHEIDT
drwx------  2 root        root  16384 févr.  3 13:05 lost+found
```

