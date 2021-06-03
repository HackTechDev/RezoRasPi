#### Erreur et résolution lors du lancement du serveur DHCP


### 1/ Erreur 1.

```
…
nov. 25 13:32:14 serveurrasp dnsmasq[18085]: dnsmasq: failed to create listening socket for port 53: Adresse déjà utilisée
nov. 25 13:32:14 serveurrasp dnsmasq[18085]: failed to create listening socket for port 53: Adresse déjà utilisée
…
```

Solution : 

Lien : 
https://askubuntu.com/questions/191226/dnsmasq-failed-to-create-listening-socket-for-port-53-address-already-in-use

```
util01@server:~/RASPSERVER$ sudo ss -lp "sport = :domain"
Netid    State     Recv-Q    Send-Q       Local Address:Port          Peer Address:Port    Process                                       
udp      UNCONN    0         0            127.0.0.53%lo:domain             0.0.0.0:*        users:(("systemd-resolve",pid=645,fd=12))
tcp      LISTEN    0         4096         127.0.0.53%lo:domain             0.0.0.0:*        users:(("systemd-resolve",pid=645,fd=13))  
```

```
util01@server:~/RASPSERVER$ sudo systemctl disable systemd-resolved
Removed /etc/systemd/system/multi-user.target.wants/systemd-resolved.service.
Removed /etc/systemd/system/dbus-org.freedesktop.resolve1.service.
```

```
util01@server:~/RASPSERVER$ sudo systemctl mask systemd-resolved
Created symlink /etc/systemd/system/systemd-resolved.service → /dev/null.
```

```
util01@server:~/RASPSERVER$ sudo systemctl stop systemd-resolved
```


### 2/ Erreur 2.

```
déc. 11 13:22:44 server dnsmasq[732]: directory /etc/resolv.conf for resolv-file is missing, cannot poll
déc. 11 13:22:44 server dnsmasq[732]: FAILED to start up
```

Solution : 

Lien :
https://askubuntu.com/questions/1108260/ubuntu-18-04-systemd-resolve-doesnt-read-the-etc-resolv-conf-properly

```
util01@server:~$ sudo rm /etc/resolv.conf
```

Créer : 

```
/etc/resolv.conf
```

Ajouter : 

```
nameserver 127.0.0.1
```
