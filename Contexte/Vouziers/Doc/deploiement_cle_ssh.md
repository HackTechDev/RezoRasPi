#### Arrêt d'un client Raspberry Pi par ssh


### I/ Copie de la clé ssh du serveur sur un Raspberry Pi déjà initialisé.

https://www.digitalocean.com/community/tutorials/how-to-set-up-ssh-keys-on-ubuntu-1804-fr

Sur le serveur : 

### 1/ Test, avant : 

util01@station66:~$ ssh util01@192.168.1.17
util01@192.168.1.17's password: 
Welcome to Ubuntu 20.04.1 LTS (GNU/Linux 5.8.0-38-generic x86_64)
...
util01@college-vouziers:~$ 


### 2/ Génération de clé ssh. 

$ ssh-keygen


### 3/ Vérification.

util01@station66:~$ ls -l .ssh/
total 56
-rw------- 1 util01 util01  1679 nov.  17  2020 id_rsa
-rw-r--r-- 1 util01 util01   398 nov.  17  2020 id_rsa.pub
-rw------- 1 util01 util01 21344 juin   3 10:38 known_hosts
-rw------- 1 util01 util01 21344 mai   23 23:18 known_hosts.old


### 4/ Copîe de la clé ssh. 

util01@station66:~$ ssh-copy-id util01@192.168.1.17
/usr/bin/ssh-copy-id: INFO: Source of key(s) to be installed: "/home/util01/.ssh/id_rsa.pub"
/usr/bin/ssh-copy-id: INFO: attempting to log in with the new key(s), to filter out any that are already installed
/usr/bin/ssh-copy-id: INFO: 1 key(s) remain to be installed -- if you are prompted now it is to install the new keys
util01@192.168.1.17's password: 

Number of key(s) added: 1

Now try logging into the machine, with:   "ssh 'util01@192.168.1.17'"
and check to make sure that only the key(s) you wanted were added.

util01@station66:~$ 


### 5/ Test, après : 

util01@station66:~$ ssh util01@192.168.1.17
Welcome to Ubuntu 20.04.1 LTS (GNU/Linux 5.8.0-38-generic x86_64)
...
util01@college-vouziers:~$ 


### II/ Copîe de la clé ssh du serveur sur tous les Raspberry Pi via un squelette de 'home'.


### 1/ Automatiquement lors de la création d'un compte utilisateur.

1/ Création du répertoire '.ssh'.

util01@college-vouziers:~$ sudo mkdir -p /srv/nfs/rpi4-image/etc/skel/.ssh


2/ Création du fichier d'autorisation.

util01@college-vouziers:~$ sudo touch /srv/nfs/rpi4-image/etc/skel/.ssh/authorized_keys


3/ Copier le contenu du fichier "/home/util01/.ssh/id_rsa.pub" dans le fichier "/srv/nfs/rpi4-image/etc/skel/.ssh/authorized_keys".


