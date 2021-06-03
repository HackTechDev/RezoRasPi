#### Gestion système


### 1/ Depuis un poste Raspberry Pi, impossible de faire un mise-à-jour du système.

Solution : 

Changer l'adresse du dépôt principal.

Ouvrir : 

```
etc/apt/sources.list.d/raspi.list
```

Remplacer tout par : 

```
deb http://raspbian.42.fr/debian/ buster main
```


### 2/ Depuis le serveur, création de l'archive de sauvegarde de l'image Raspberry OS.

- Aller dans le répertoire de l'image :

```
util01@college-vouziers:~$ cd /srv/nfs/
```


- Listage des images Raspberry OS disponible :

```
util01@college-vouziers:/srv/nfs$ ls -l
total 8
drwxr-xr-x 18 root root 4096 déc.  14 18:51 rpi4-image
drwxr-xr-x 18 root root 4096 janv. 12 18:11 rpi4-image_custom
```

- Création de l'archive de l'archive :

```
util01@college-vouziers:/srv/nfs$ sudo tar cvf rpi4-image_210201.tar rpi4-image
```

- Vérification : 

```
util01@college-vouziers:/srv/nfs$ ls *.tar
rpi4-image_210201.tar
```


### 3/ Depuis un poste Raspberry Pi, erreur de hostname : 

```
sudo: unable to resolve host (none)
```

Solution :

Ouvrir : 

```
/etc/hosts
```
Ajouter :

```
127.0.1.1 raspberry.technovz-serveur-rasp 
```

Supprimer : 

```
127.0.1.1 raspberry
```

 
### 5/ Executer une commande d'administration avec un utilisteur normal sans être administrateur.

Lien : https://unix.stackexchange.com/questions/276474/how-can-i-execute-any-command-as-a-normal-user-without-sudo

```
pi@ordinateur:~ $ sudo su
root@ordinateur:/home/pi# visudo 
```

Ajouter : 

```
ecachier ALL=(root) NOPASSWD: /usr/bin/apt-get
```

Ouvrir : 

```
.bashrc
```

Ajouter à la fin :

```
alias miseajour='sudo apt-get'
```

Action : 

```
$ miseajour update
```


### 2/ Paramétrage pour que les élèves ne voient pas les dossiers des autres users

Depuis un client Raspberry Pi.

- Par défaut l'accès au répertoire des éléves s'effectue correctement :

```
util01@college-vouziers:~$ ssh sgondouin@192.168.2.75
sgondouin@ordinateur:~ $ 
```

```
sgondouin@ordinateur:~ $ pwd
/home/users/sgondouin
```

```
sgondouin@ordinateur:~ $ cd ..
sgondouin@ordinateur:/home/users $
```

```
sgondouin@ordinateur:/home/users $ ls -l
total 8
drwxr-xr-x 14 jbraquet  classe 4096 Jan 15 10:52 jbraquet
drwxr-xr-x 14 sgondouin classe 4096 Jan 15 10:41 sgondouin
```

```
sgondouin@ordinateur:/home/users/jbraquet $ ls
Desktop  Documents  Downloads  Music  Pictures  Public  Templates  Videos
sgondouin@ordinateur:/home/users/jbraquet $ 
```

- Changement de permission sous l'utilisateur administateur : 

```
sgondouin@ordinateur:~ $ sudo su
root@ordinateur:/home/pi# 
```

```
root@ordinateur:/home/pi# cd ..
root@ordinateur:/home# 
```

```
root@ordinateur:/home# cd users/
root@ordinateur:/home/users# 
root@ordinateur:/home/users# ls -l
total 8
drwxr-xr-x 14 jbraquet classe 4096 Jan 15 10:52 jbraquet
drwxr-xr-x 14 pi       classe 4096 Jan 15 10:41 sgondouin
```

- Changement de permission.

Liens: 
https://fr.wikipedia.org/wiki/Chmod
https://www.leshirondellesdunet.com/chmod-et-chown

```
root@ordinateur:/home/users# chmod 700 jbraquet/
root@ordinateur:/home/users# chmod 700 sgondouin/
```

- Listage des répertoires avec les nouvelles permission.

```
root@ordinateur:/home/users# ls -l
total 8
drwx------ 14 jbraquet classe 4096 Jan 15 10:52 jbraquet
drwx------ 14 pi       classe 4096 Jan 15 10:41 sgondouin
```

- Test : 

```
sgondouin@ordinateur:/home/users $ cd jbraquet/
-bash: cd: jbraquet/: Permission denied
```

```
sgondouin@ordinateur:/home/users $ cd sgondouin/
sgondouin@ordinateur:~ $ 
```


### 3/ Changement de timezone.

Depuis un client Raspberry Pi.

/!\ La connexion internet doit être active /!\

Lien :
https://linuxize.com/post/how-to-set-or-change-timezone-in-linux/
https://raspberrytips.com/time-sync-raspberry-pi/


```
root@ordinateur:/home/pi# date
Fri Jan 15 11:25:55 GMT 2021
```

```
root@ordinateur:/home/pi# cat /etc/timezone 
Europe/London
```

```
root@ordinateur:/home/pi# timedatectl 
               Local time: Fri 2021-01-15 12:02:12 GMT
           Universal time: Fri 2021-01-15 12:02:12 UTC
                 RTC time: n/a
                Time zone: Europe/London (GMT, +0000)
System clock synchronized: yes
              NTP service: active
          RTC in local TZ: no
```

```
root@ordinateur:/home/pi# timedatectl set-timezone Europe/Paris
```

```
root@ordinateur:/home/pi# timedatectl
               Local time: Fri 2021-01-15 13:04:06 CET
           Universal time: Fri 2021-01-15 12:04:06 UTC
                 RTC time: n/a
                Time zone: Europe/Paris (CET, +0100)
System clock synchronized: yes
              NTP service: active
          RTC in local TZ: no
root@ordinateur:/home/pi# 
```

```
root@ordinateur:/home/pi# date
Fri Jan 15 13:04:32 CET 2021
```

Ouvrir : 
```
/etc/systemd/timesyncd.conf
```

Chercher :

```
#NTP=
```

Remplacer par : 

```
NTP=0.fr.pool.ntp.org 1.fr.pool.ntp.org 2.fr.pool.ntp.org 3.fr.pool.ntp.org
```

Chercher et décommenter : 

```
#FallbackNTP=0.debian.pool.ntp.org 1.debian.pool.ntp.org 2.debian.pool.ntp.org 3.debian.pool.ntp.org
```

```
root@ordinateur:/home/pi# timedatectl set-ntp true
```

```
root@ordinateur:/home/pi# systemctl daemon-reload
```

```
root@ordinateur:/home/pi# service systemd-timesyncd status
● systemd-timesyncd.service - Network Time Synchronization
   Loaded: loaded (/lib/systemd/system/systemd-timesyncd.service; enabled; vendor preset: enabled)
  Drop-In: /usr/lib/systemd/system/systemd-timesyncd.service.d
           └─disable-with-time-daemon.conf
   Active: active (running) since Fri 2021-01-15 13:14:24 CET; 13s ago
     Docs: man:systemd-timesyncd.service(8)
 Main PID: 1348 (systemd-timesyn)
   Status: "Synchronized to time server for the first time 194.57.169.1:123 (0.fr.pool.ntp.org)."
    Tasks: 2 (limit: 4915)
   CGroup: /system.slice/systemd-timesyncd.service
           └─1348 /lib/systemd/systemd-timesyncd

Jan 15 13:14:24 ordinateur.college-vouziers.fr systemd[1]: Starting Network Time Synchronization...
Jan 15 13:14:24 ordinateur.college-vouziers.fr systemd-timesyncd[1348]: /etc/systemd/timesyncd.conf:1: Assignment outside of section. Ignoring.
Jan 15 13:14:24 ordinateur.college-vouziers.fr systemd[1]: Started Network Time Synchronization.
Jan 15 13:14:24 ordinateur.college-vouziers.fr systemd-timesyncd[1348]: Synchronized to time server for the first time 194.57.169.1:123 (0.fr.pool.ntp.org).
```


### 5/ Arrêt automatique à 18h.

Depuis le serveur.

Créer : 

```
/srv/nfs/rpi4-image/etc/cron.d/shutdown_raspberrypi
```

Ajouter : 

```
0 18 * * * root /sbin/poweroff
```


### 6/ Activation des log de Dnsmasq.

Lien : 
https://itectec.com/superuser/how-to-log-all-dns-requests-made-through-openwrt-router/


Depuis le serveur.

Ouvrir : 

```
/etc/dnsmasq.conf
```

Ajouter : 

```
    log-dhcp
    log-queries
    log-facility=/var/log/dnsmasq.log
```
    

```
util01@college-vouziers:~$ sudo systemctl restart dnsmasq
```

```
util01@college-vouziers:~$ sudo systemctl status dnsmasq.service


Test :

Redemarrer un Raspberry Pi

Se diriger vers un site web.




