Pour le serveur : Configuration du Proxy
========================================

1/ Pour Apt.

Lien :
https://www.serverlab.ca/tutorials/linux/administration-linux/how-to-set-the-proxy-for-apt-for-ubuntu-18-04/


Créer :

```
/etc/apt/apt.conf.d/proxy.conf
```

Ajouter :

```
Acquire::http::Proxy "http://user:password@proxy.server:port/";
Acquire::https::Proxy "http://user:password@proxy.server:port/";
```

Soit 

```
Acquire::http::Proxy "http://192.168.224.254:3128/";
Acquire::https::Proxy "http://192.168.224.254@proxy.server:3128/";
```


2/ Pour Git.
 
Lien :
https://gist.github.com/evantoli/f8c23a37eb3558ab8765


```
git config --global http.proxy http://192.168.224.254:3128
```
