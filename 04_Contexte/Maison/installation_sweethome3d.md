### Installation de SweetHome3D version 5.3

Distribution de travail : Raspberry Pi OS du 2020-12-02
2020-12-02-raspios-buster-armhf.zip

#### Problème

La version installé par défaut de SweetHome3d est la version 6.4.2
Elle ne fonctionne pas très bien sur une des dernières version de Raspberry OS, il y a des problèmes graphiques : carré noir au niveau des menus, etc.
Il faut donc installé une version antérieur, soit la verson 5.3


#### Installation de SweetHome 3d version 6.4

```
pi@ordinateur:~ $ sudo apt-get install sweethome3d
```


#### Création du répertoire de travail

```
pi@ordinateur:~ $ mkdir SW3D
pi@ordinateur:~ $ cd SW3D/
pi@ordinateur:~/SW3D $ 
```

#### Téléchargement de la version 5.3

```
pi@ordinateur:~/SW3D $ wget http://raspbian.raspberrypi.org/raspbian/pool/main/s/sweethome3d/sweethome3d_5.3+dfsg-2_all.deb
```


#### Décompression de l'archive Debian

```
pi@ordinateur:~/SW3D $ ar x sweethome3d_5.3+dfsg-2_all.deb
pi@ordinateur:~/SW3D $ xz -d data.tar.xz
pi@ordinateur:~/SW3D $ tar xvf data.tar
```


#### Installation

```
pi@ordinateur:~/SW3D $ sudo cp usr/share/sweethome3d/* /usr/share/sweethome3d/
```


#### Lancement de SweetHome3d

```
pi@ordinateur:~/SW3D $ sweethome3d
```
