#### Installation de serveurs DHCP et NFS

Distribution de travail : Ubuntu 20.04 LTS


Objectif : 
Démarrage de plusieurs Raspberry PI 4 sans carte SD depuis un serveur Ubuntu.
Tous les Raspberry Pi 4 utilisent la même image de système d'expoitation Raspberry OS sur le serveur.
Chaque utilisateur dispose de son propre répertoire home sur le serveur. Chaque utilisateur est ainsi identifié (fichier de log qui horodatera la connexion et la déconnexion de chaque utilisateur) et peut accéder à ses fichiers depuis n'importe quel Raspberry.

Le système d'exploitation est stocké dans un répertoire réseau NFS.
Un lien est nommé à partir de l'adresse MAC de chaque Raspberry et pointe vers le répertoire boot de l'image.

Lien :
https://www.virtuallyghetto.com/2020/07/two-methods-to-network-boot-raspberry-pi-4.html


### 1/ Serveur : Configuration réseau.

```
Interface réseau :  enp2s0
@ip : 192.168.2.100

Interface réseau :  wlp4s0
@ip : 192.168.1.42
```

Cette adresse ipv4 est attribuée par le serveur DHCP de la Box Internet.


### 2/ Récupération des informations réseau d'un client Raspberry Pi 4: 

Pour le Raspberry Pi 4 #1 :

```
@MAC = dc:a6:32:22:ce:87
```


### 3/ Mise-à-jour du système d’exploitation Ubuntu.

```
util01@server:~$ sudo apt-get update
util01@server:~$ sudo apt-get upgrade
```


### 4/ Installation des outils de base.

```
util01@server:~$ sudo apt-get install openssh-server openssh-client mc vim screen htop wget links
```


### 5/ Installation des utilitaires pour la configuration de PXE.

```
util01@server:~$ sudo apt-get install nfs-kernel-server dnsmasq kpartx unzip
```


### 6/ Création du répertoire de travail.

```
util01@server:~$ mkdir -p RASPSERVER
util01@server:~$ cd RASPSERVER/
util01@server:~/RASPSERVER$ 
```


### 7/ Téléchargement de l’image de l’OS Raspberry Pi OS daté du 2020-12-02

Lien :
https://www.raspberrypi.org/software/operating-systems/

```
util01@server:~/RASPSERVER$ wget https://downloads.raspberrypi.org/raspios_armhf/images/raspios_armhf-2020-12-04/2020-12-02-raspios-buster-armhf.zip
```


### 8/ Décompression de l’archive de l’image.

```
util01@server:~/RASPSERVER$ unzip 2020-12-02-raspios-buster-armhf.zip 
Archive:  2020-12-02-raspios-buster-armhf.zip
 inflating: 2020-12-02-raspios-buster-armhf.img
```


### 9/ Création des mappes de périphérique à partir de l’image.

```
util01@server:~/RASPSERVER$ sudo kpartx -a -v 2020-12-02-raspios-buster-armhf.img
add map loop6p1 (253:0): 0 524288 linear 7:6 8192
add map loop6p2 (253:1): 0 7225344 linear 7:6 532480
```


10/ Création des répertoires de montage.

```
util01@server:~/RASPSERVER$ mkdir rootmnt
util01@server:~/RASPSERVER$ mkdir bootmnt
```


### 11/ Montage les devices vers les répertoires de montage.

```
util01@server:~/RASPSERVER$ sudo mount /dev/mapper/loop6p1 bootmnt/
util01@server:~/RASPSERVER$ sudo mount /dev/mapper/loop6p2 rootmnt/
```


### 12/ Création du répertoire de l'image de Raspberry Pi OS.

```
util01@server:~/RASPSERVER$ sudo mkdir -p /srv/nfs/rpi4-image
```


### 13/ Copie des fichiers de l’image vers le répertoire réseau spécifique à un Raspberry Pi. 

```
util01@server:~/RASPSERVER$ sudo cp -a rootmnt/* /srv/nfs/rpi4-image/
util01@server:~/RASPSERVER$ sudo cp -a bootmnt/* /srv/nfs/rpi4-image/boot/
```


### 14/ Suppression des fichiers de démarrage par défauts.

```
util01@server:~/RASPSERVER$ sudo rm /srv/nfs/rpi4-image/boot/start4.elf
util01@server:~/RASPSERVER$ sudo rm /srv/nfs/rpi4-image/boot/fixup4.dat
```


### 15/ Téléchargement des fichiers de démarrage dans leurs dernières versions.

```
util01@server:~/RASPSERVER$ wget https://github.com/Hexxeh/rpi-firmware/raw/stable/start4.elf 
util01@server:~/RASPSERVER$ wget https://github.com/Hexxeh/rpi-firmware/raw/stable/fixup4.dat
```


### 16/ Copie des nouveaux fichiers de démarrage.

```
util01@server:~/RASPSERVER$ sudo cp start4.elf /srv/nfs/rpi4-image/boot/
util01@server:~/RASPSERVER$ sudo cp fixup4.dat /srv/nfs/rpi4-image/boot/
```


### 17/ Configuration du serveur NFS.

Ouvrir :

```
/etc/exports
```

Ajouter à la fin :

```
/srv/nfs/rpi4-image *(rw,sync,no_subtree_check,no_root_squash)
```


### 18/ Pour le Raspberry 4 #1 : Création du lien symbolique avec pour nom l'adresse MAC.

```
util01@server:~/RASPSERVER$ cd /srv/tftpboot/
```

L'adresse MAC est en minuscule, les deux-points sont remplacés par un tiret.

```
util01@server:/srv/tftpboot$ sudo ln -s /srv/nfs/rpi4-image/boot/ dc-a6-32-22-ce-87
```


### 19/ Configuration du serveur DHCP pour la reconnaissance du répertoire de boot à partir de l'adresse MAC.

Liens :
https://stackoverflow.com/questions/40008276/dnsmasq-different-tftp-root-for-each-macaddress/51508180
https://linuxconfig.org/how-to-configure-a-raspberry-pi-as-a-pxe-boot-server#h5-3-specifying-the-ip-range-proxy-mode

Dans l'étude cas, l'interface réseau wifi wlp4s0 : 192.168.1.42
Via cette interface réseau, le serveur est connecté sur internet via la Box


Ouvrir :

```
/etc/dnsmasq.conf 
```

Supprimer tout.

Ajouter :

```
# la plage d'adresses IP ci dessous correspond aux IP qui pourront être attribués à chaque Raspberry. 
# En utilisant ces paramètres, le réseau sera limité à 30 Raspberry.
dhcp-range=192.168.2.50,192.168.2.80,12h

# Correspond à l'adresse IP de l'interface connectée au réseau ayant Internet
dhcp-option=option:router,192.168.1.42
log-dhcp
enable-tftp
tftp-root=/srv/tftpboot
tftp-unique-root=mac
pxe-service=0,"Raspberry Pi Boot"
```


### 21/ Suppression les lignes contenant 'UUID' dans le fichier '/etc/fstab'. de chaque Raspberry

Ouvrir :

```
/srv/nfs/rpi4-image/etc/fstab 
```

Supprimer toutes les lignes incluant 'UUID'.


### 22/ Activation du service SSH au démarrage.

```
util01@server:~/RASPSERVER$ sudo touch /srv/nfs/rpi4-image/boot/ssh
```


### 23/ Configuration du fichier de démarrage du Raspberry Pi

Elle permet d'indiquer quelle image Raspberry OS à utiliser.

Ouvrir : 

```
/srv/nfs/rpi4-image/boot/cmdline.txt
```

Remplacer tout par :
/!\ Sur une seule ligne /!\

```
console=serial0,115200 console=tty root=/dev/nfs nfsroot=192.168.2.100:/srv/nfs/rpi4-image,vers=3,proto=tcp rw ip=dhcp rootwait elevator=deadline
```


### 24/ Vérification de la configuration réseau actuel. 

```
util01@server:~$ ip a
1: lo: <LOOPBACK,UP,LOWER_UP> mtu 65536 qdisc noqueue state UNKNOWN group default qlen 1000
    link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
    inet 127.0.0.1/8 scope host lo
       valid_lft forever preferred_lft forever
    inet6 ::1/128 scope host 
       valid_lft forever preferred_lft forever
2: enp2s0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc fq_codel state UP group default qlen 1000
    link/ether 00:16:d3:65:58:d6 brd ff:ff:ff:ff:ff:ff
    inet 192.168.1.39/24 brd 192.168.1.255 scope global dynamic noprefixroute enp2s0
       valid_lft 85890sec preferred_lft 85890sec
    inet6 fe80::ca9:f6f6:1094:b421/64 scope link noprefixroute 
       valid_lft forever preferred_lft forever
3: wlp4s0: <NO-CARRIER,BROADCAST,MULTICAST,UP> mtu 1500 qdisc mq state DOWN group default qlen 1000
    link/ether 00:1b:77:26:7e:93 brd ff:ff:ff:ff:ff:ff
```


### 25/ Activer une adresse IP statique pour l’interface réseau : enp2s0.

Lien :
https://www.howtoforge.com/linux-basics-set-a-static-ip-on-ubuntu

Ouvrir : 

```
/etc/netplan/00-installer-config.yaml
```

Ajouter : 

```
network:
 version: 2
 renderer: networkd
 ethernets:
   enp2s0:
     dhcp4: no
     dhcp6: no
     addresses: [192.168.2.100/24]
     gateway4: 192.168.2.1
     nameservers:
       addresses: [8.8.8.8,8.8.4.4]
```

Action : 
#Appliquer la configuration réseau

```
util01@server:~$ sudo netplan apply
```

Vérification :

```
util01@server:~$ ip a
...
2: enp2s0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc fq_codel state UP group default qlen 1000
    link/ether 00:16:d3:65:58:d6 brd ff:ff:ff:ff:ff:ff
    inet 192.168.2.100/24 brd 192.168.2.255 scope global noprefixroute enp2s0
       valid_lft forever preferred_lft forever
    inet6 fe80::216:d3ff:fe65:58d6/64 scope link 
       valid_lft forever preferred_lft forever
...
```


### 26/ Activation de tous les services.

```
util01@server:~/RASPSERVER$ sudo systemctl enable dnsmasq
Synchronizing state of dnsmasq.service with SysV service script with /lib/systemd/systemd-sysv-install.
Executing: /lib/systemd/systemd-sysv-install enable dnsmasq
util01@server:~/RASPSERVER$ 
```

```
util01@server:~/RASPSERVER$ sudo systemctl enable rpcbind
Synchronizing state of rpcbind.service with SysV service script with /lib/systemd/systemd-sysv-install.
Executing: /lib/systemd/systemd-sysv-install enable rpcbind
util01@server:~/RASPSERVER$ 
```

```
util01@server:~/RASPSERVER$ sudo systemctl enable nfs-server
util01@server:~/RASPSERVER$ sudo systemctl enable nfs-kernel-server
```


### 27/ Démarrage de tous les services.

```
util01@server:~/RASPSERVER$ sudo systemctl start rpcbind
util01@server:~/RASPSERVER$ sudo systemctl start nfs-server
util01@server:~/RASPSERVER$ sudo systemctl restart nfs-kernel-server
```


### 28/ Lancement du service DHCP 'dnsmasq'.

```
util01@server:~/RASPSERVER$ sudo killall dnsmasq
```

```
util01@server:~/RASPSERVER$ sudo systemctl start dnsmasq
```
ou 

```
util01@server:~/RASPSERVER$ sudo systemctl restart dnsmasq
```

```
util01@server:~/RASPSERVER$ sudo systemctl status dnsmasq.service
```

Erreur possible : 
7_resolution_erreurs_DHCP.md


### 29/ Visualisation des logs. 

```
util01@server:~/RASPSERVER$ tail -f /var/log/syslog
```


### 30/ Allumer l'un des Raspberry Pi 4 du réseau 192.168.2.0/24

Après quelques secondes, le bureau s'affiche.


### 31/ Affichage des interfaces réseaux de ce Raspberry Pi 4.

```
pi@raspberrypi:~ $ ip a
1: lo: <LOOPBACK,UP,LOWER_UP> mtu 65536 qdisc noqueue state UNKNOWN group default qlen 1000
    link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
    inet 127.0.0.1/8 scope host lo
       valid_lft forever preferred_lft forever
    inet6 ::1/128 scope host 
       valid_lft forever preferred_lft forever
2: eth0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc mq state UP group default qlen 1000
    link/ether dc:a6:32:22:ce:87 brd ff:ff:ff:ff:ff:ff
    inet 192.168.2.64/24 brd 192.168.2.255 scope global dynamic eth0
       valid_lft 42900sec preferred_lft 37500sec
    inet6 fe80::dea6:32ff:fe22:ce87/64 scope link 
       valid_lft forever preferred_lft forever
3: wlan0: <BROADCAST,MULTICAST> mtu 1500 qdisc noop state DOWN group default qlen 1000
    link/ether dc:a6:32:22:ce:88 brd ff:ff:ff:ff:ff:ff
```


### 32/ Connexion ssh depuis le serveur vers ce raspberry Pi 4.

```
util01@server:~$ ssh pi@192.168.2.64
pi@192.168.2.64's password: 
Linux raspberrypi 5.4.79-v7l+ #1373 SMP Mon Nov 23 13:27:40 GMT 2020 armv7l
...
pi@raspberrypi:~ $ 
```



