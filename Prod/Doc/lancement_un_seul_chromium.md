#### Lancement d'une seule aplication Chromium


Objectif :
Lancement d'une seule instance de Chromium grâce un verrou non-concurrentiel via le bouton de lancement de la barre de tâche de LXDE. 


#### 1/ Liens. 

https://qastack.fr/unix/177386/how-can-i-add-applications-to-the-lxpanel-application-launch-bar-via-cli
https://www.baeldung.com/linux/bash-ensure-instance-running


### 2/ Configuration du script de lancement du navigateur Chromium.

Ouvrir : 

/usr/share/applications/lxde-x-www-browser.desktop

Chercher : 

Exec=/usr/bin/x-www-browser %u

Remplacer par : 

Exec=flock -n /var/lock/chromium.lock chromium-browser


Ouvrir : 

/usr/share/raspi-ui-overrides/applications/lxde-x-www-browser.desktop 

Chercher : 

Exec=/usr/bin/x-www-browser %u

Remplacer par : 

Exec=flock -n /var/lock/chromium.lock chromium-browser



### 3/ Redémarrer le Raspberry Pi
