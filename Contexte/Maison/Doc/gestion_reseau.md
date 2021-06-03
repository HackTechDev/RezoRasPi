#### Gestion Réseau


### 1/ Listage des Raspberry Pi en réseau.

```
util01@college-vouziers:~$ cat /var/lib/misc/dnsmasq.leases
1613604673 dc:a6:32:b7:fe:da 192.168.2.75 ordinateur 01:dc:a6:32:b7:fe:da
```


### 2/ Déconnexion manuelle de l'interface graphique par ligne de commande.

```
util01@college-vouziers:~$ ssh sgondouin@192.168.2.75
pi@192.168.2.75's password:
sgondouin@ordinateur:~ $ 
```

```
sgondouin@ordinateur:~ $ pkill -9 -f lxsession
```


### 3/ Connexion sur un Raspberry Pi sans mot de passe.

- Sur le serveur, génération de la clé publique : 

```
util01@college-vouziers:~$ ssh-keygen -t rsa
Generating public/private rsa key pair.
Enter file in which to save the key (/home/util01/.ssh/id_rsa): 
Enter passphrase (empty for no passphrase): 
Enter same passphrase again: 
Your identification has been saved in /home/util01/.ssh/id_rsa
Your public key has been saved in /home/util01/.ssh/id_rsa.pub
The key fingerprint is:
SHA256:5zw3tEw82Jd8Woks6xBeNwSjFoiV0Dm2Z+DNCWBKspQ util01@college-vouziers.fr
The key's randomart image is:
+---[RSA 3072]----+
| o.. +*.+. o     |
|.E+ o. X  o o    |
| . .  o Bo.  .   |
|       o.* +o....|
|        S.oo*=+.o|
|        .+o+++.+ |
|         o+.= .  |
|          oo .   |
|           .     |
+----[SHA256]-----+
```

- Copie de la clé publique sur un utilisateur de Raspberry Pi :

```
util01@college-vouziers:~$ ssh-copy-id -i .ssh/id_rsa.pub pi@192.168.2.75
/usr/bin/ssh-copy-id: INFO: Source of key(s) to be installed: ".ssh/id_rsa.pub"
/usr/bin/ssh-copy-id: INFO: attempting to log in with the new key(s), to filter out any that are already installed
/usr/bin/ssh-copy-id: INFO: 1 key(s) remain to be installed -- if you are prompted now it is to install the new keys
pi@192.168.2.75's password: 

Number of key(s) added: 1

Now try logging into the machine, with:   "ssh 'pi@192.168.2.75'"
and check to make sure that only the key(s) you wanted were added.
```

- Connexion : 

```
util01@college-vouziers:~$ ssh pi@192.168.2.75
Linux ordinateur.college-vouziers.fr 5.4.79-v7l+ #1373 SMP Mon Nov 23 13:27:40 GMT 2020 armv7l

The programs included with the Debian GNU/Linux system are free software;
the exact distribution terms for each program are described in the
individual files in /usr/share/doc/*/copyright.

Debian GNU/Linux comes with ABSOLUTELY NO WARRANTY, to the extent
permitted by applicable law.
Last login: Wed Feb 17 13:17:21 2021

Wi-Fi is currently blocked by rfkill.
Use raspi-config to set the country before use.

pi@ordinateur:~ $ 
```

- Copier le fichier 'authorized_keys' pour un utilisateur :

```
sgondouin@ordinateur:/home/users/sgondouin $ mkdir .ssh
sgondouin@ordinateur:/home/users/sgondouin $ cp /home/pi/.ssh/authorized_keys /home/users/sgondouin/.ssh/
```

- Déconnexion : 

```
sgondouin@ordinateur:/home/users/sgondouin $ exit
logout
Connection to 192.168.2.75 closed.
```


- Test : 

```
util01@college-vouziers:~$ ssh sgondouin@192.168.2.75
Linux ordinateur.college-vouziers.fr 5.4.79-v7l+ #1373 SMP Mon Nov 23 13:27:40 GMT 2020 armv7l

The programs included with the Debian GNU/Linux system are free software;
the exact distribution terms for each program are described in the
individual files in /usr/share/doc/*/copyright.

Debian GNU/Linux comes with ABSOLUTELY NO WARRANTY, to the extent
permitted by applicable law.
Last login: Wed Feb 17 13:41:24 2021 from 192.168.2.100

Wi-Fi is currently blocked by rfkill.
Use raspi-config to set the country before use.

sgondouin@ordinateur:~ $ 
```


