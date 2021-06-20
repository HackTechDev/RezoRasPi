### Création d'un répertoire partagé en NFS


#### I/ Sur le serveur.

Lien : 
[https://www.windows8facile.fr/linux-creer-partage-nfs/](https://www.windows8facile.fr/linux-creer-partage-nfs/)


#### 1/ Création du répertoire partagé.

```
util01@college-vouziers:~$ sudo mkdir -p /srv/nfs/user
```


#### 2/ Changement de permission. 

```
util01@college-vouziers:~$ sudo chmod 755 /srv/nfs/user/
```


#### 3/ Configuration de l'export NFS.

Ouvrir : 

```
/etc/exports
```

Ajouter : 

```
/srv/nfs/user *(rw,sync,no_subtree_check,no_root_squash)
```


#### 4/ Redémarrage des services NFS.

```
util01@college-vouziers:~$ sudo systemctl start rpcbind
util01@college-vouziers:~$ sudo systemctl start nfs-server
util01@college-vouziers:~$ sudo systemctl restart nfs-kernel-server
```


#### 5/ Visualisation du répertoire partagé.

```
util01@college-vouziers:/srv/nfs/user$ showmount -e
Export list for college-vouziers.fr:
/srv/nfs/user              *
/srv/nfs/rpi4-image_custom *
/srv/nfs/rpi4-image        *
```
