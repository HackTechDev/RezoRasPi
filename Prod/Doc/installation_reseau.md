#### Installation réseau


### 1/ Demander à l'administrateur réseau du collègue de mettre l'adresse ip fixe, ici @ip:10.108.39.173, pour la carte réseau du serveur.

Il faudra lui fournir l'adresse MAC de la carte réseau : 

```
technovz@technovz-serveur-rasp:~$ ip a
...
3: enp5s0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc fq_codel state UP group default qlen 1000
    link/ether ec:10:69:81:ec:10 brd ff:ff:ff:ff:ff:ff
    inet 10.108.39.68/24 brd 10.108.39.255 scope global dynamic noprefixroute enp5s0
       valid_lft 64779sec preferred_lft 64779sec
    inet6 fe80::e03:b113:89d4:876f/64 scope link noprefixroute 
       valid_lft forever preferred_lft forever

```

ici : ec:10:69:81:ec:10


Pour la carte réseau' enp5s0', mettre en adresse ipv4 manuel avec les paramètres suivants : 
@ipv4 : 10.108.39.173
Masque de sous-réseau : 255.255.255.0
Route par défaut : 10.108.39.1
DNS : 10.108.39.2

Soit : 
```
technovz@technovz-serveur-rasp:~$ ip a
...
3: enp5s0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc fq_codel state UP group default qlen 1000
    link/ether ec:10:69:81:ec:10 brd ff:ff:ff:ff:ff:ff
    inet 10.108.39.173/24 brd 10.108.39.255 scope global noprefixroute enp5s0
       valid_lft forever preferred_lft forever
    inet6 fe80::e03:b113:89d4:876f/64 scope link noprefixroute 
       valid_lft forever preferred_lft forever
```
