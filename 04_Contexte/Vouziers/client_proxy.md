Pour un client Raspi : Configuration proxy
==========================================


Ouvrir :

/srv/nfs/rpi4-image/etc/environment


Ajouter : 

export http_proxy="http://10.108.39.1:3128"
export https_proxy="https://10.108.39.1:3128"
export no_proxy="localhost, 127.0.0.1"

