#### Configuration Arduino


### 1/ Lancement de l'IDE Arduino en root via un utilisateur normal.

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


- Modification des permissions : 

```
cachiere@raspberry:~ $ sudo usermod -a -G dialout $USER  
cachiere@raspberry:~ $ sudo chmod a+rw /dev/ttyACM0
```

