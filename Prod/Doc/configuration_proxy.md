#### Pour le serveur et pour les clients : Configuration du Proxy


L'adresse ip du proxy est : 192.168.224.254


### 1/ Pour Apt.

Lien :
https://www.serverlab.ca/tutorials/linux/administration-linux/how-to-set-the-proxy-for-apt-for-ubuntu-18-04/


Créer :

```
/etc/apt/apt.conf.d/proxy.conf
```

Ajouter :

```
Acquire::http::Proxy "http://user:password@proxy.server:port/";
Acquire::https::Proxy "https://user:password@proxy.server:port/";
Acquire::ftp::Proxy "ftp://user:password@proxy.server:port/";
```

Soit 

```
Acquire::http::Proxy "http://192.168.224.254:3128/";
Acquire::https::Proxy "https://192.168.224.254:3128/";
Acquire::ftp::Proxy "ftp://192.168.224.254:3128/";
```


#### 2/ Pour Git.
 
Lien :
https://gist.github.com/evantoli/f8c23a37eb3558ab8765


```
git config --global http.proxy http://192.168.224.254:3128
```


### Pour un client Raspi : Configuration proxy

Depuis le serveur, ouvrir :

```
/srv/nfs/rpi4-image/etc/environment
```

Ajouter :

```
export http_proxy="http://192.168.224.254:3128"
export https_proxy="https://192.168.224.254:3128"
export no_proxy="localhost, 127.0.0.1"
```


Depuis le client, lancer visudo :

```
pi@raspberrypi:~ $ sudo visudo
```

Ajouter :

```
Defaults        env_keep+="http_proxy https_proxy no_proxy"
```

