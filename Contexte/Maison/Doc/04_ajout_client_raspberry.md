#### Ajout d'un Raspberry Pi 4


### 1/ sur le client Raspberry Pi 4 : Récupération des informations techniques.

Raspberry Pi 4 #2 :

C.f. : 
```
1_installation_client_raspberry.txt
```

```
PI_SERIAL = d03114
MAC = dc:a6:32:b7:fe:da
```

### 2/ Pour ce Raspberry 4 #2 : Création du lien symbolique avec pour nom l'adresse MAC.

L'adresse MAC est en minuscule, les deux-points sont remplacés par un tiret.

```
util01@server:~$ cd /srv/tftpboot/
util01@server:/srv/tftpboot$ 
```

```
util01@server:/srv/tftpboot$ sudo ln -s /srv/nfs/rpi4-image/boot/ dc-a6-32-b7-fe-da
```
