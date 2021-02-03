Documentation spécifique pour la salle de technologie du collègue de Vouziers
=============================================================================


4/ Lancement de l'IDE Arduino en root via un utilisateur normal.



5/ 

Lien : https://unix.stackexchange.com/questions/276474/how-can-i-execute-any-command-as-a-normal-user-without-sudo

```
pi@raspberry:~ $ sudo su
root@raspberry:/home/pi# visudo 
```

Ajouter : 

```
cachiere ALL=(root) NOPASSWD: /opt/arduino-1.8.5/arduino
```


Action : 
# Sous l'utilisateur cachiere
```
cachiere@raspberry:~ $ sudo /opt/arduino-1.8.5/arduino
```
