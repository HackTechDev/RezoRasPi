#### Configuration du bureau des utilisateurs



### 2/ Mettre les icônes sur le bureau de l'utilisateur.

```
root@raspberry:/home/pi# cp -R .local/ /home/users/CACHIERE/
```

```
root@raspberry:/home/pi# cp -R .config/lxpanel/LXDE-pi/ /home/users/CACHIERE/
```

```
root@raspberry:/home/pi# chown 1105:500 .local -R
root@raspberry:/home/pi# chmod 755 .config/ -R
```


### 5/ Reinitialisation du bureau

- Le squelette de bureau est dans le répertoire : 

```
/home/pi/RASP_client/template.tar.gz
```

- Copier le script 'reinitDesktop.sh' le répertoire de squelette de bureau : 

```
/etc/skel/
```


- Modifier le script .bashrc : 

Ouvrir : 

```
/etc/skel/.bashrc
```

Ajouter à la fin : 

```
~/reinitDesktop.sh
```


