#### Installation du client Raspberry Pi 4

Distribution de travail : Raspbian GNU/Linux 10 (buster)

Liens :
https://www.virtuallyghetto.com/2020/07/two-methods-to-network-boot-raspberry-pi-4.html
https://linuxhit.com/raspberry-pi-pxe-boot-netbooting-a-pi-4-without-an-sd-card/


Procédure suivante à effectuer une fois pour initialiser la carte afin qu'elle boot sur le serveur.


### 1/ Mettre-à-jour le système d’exploitation Raspberri Pi OS.

```
pi@client:~ $ sudo apt-get update
pi@client:~ $ sudo apt-get upgrade
```


### 2/ Création du répertoire de travail.

```
pi@client:~ $ mkdir -p BOOTROM 
pi@client:~ $ cd BOOTROM/
pi@client:~/BOOTROM $ 
```


### 3/ Téléchargement de l’EEPROM.

Ficher : pieeprom-2020-11-24

Utiliser uniquement cette version de l'EEPROM.

```
pi@client:~/BOOTROM $ wget https://github.com/raspberrypi/rpi-eeprom/raw/master/firmware/beta/pieeprom-2020-11-24.bin
```

Nous avons constatés que les EEPROM récentes en beta ne pouvaient pas booter directement sur le réseau.


### 4/ Création d'une copie de travail.

```
pi@client:~/BOOTROM $ cp pieeprom-2020-11-24.bin new-pieeprom.bin
```


### 5/ Extraction du fichier bootconf.txt.

```
pi@client:~/BOOTROM $ sudo rpi-eeprom-config new-pieeprom.bin > bootconf.txt
```


### 6/ Activation l’ordre de démarrage.

Liens : 
https://www.raspberrypi.org/documentation/hardware/raspberrypi/bcm2711_bootloader_config.md

```
Value 	Mode 		Description
0x1 	SD CARD 	SD card 
0x2 	NETWORK 	Network boot
```

Ouvrir :

```
bootconf.txt
```

Ajouter à la fin : 

```
BOOT_ORDER=0xf21
```


### 7/ Récréation de l’EEPROM.

/!\ Procédures suivantes à effectuer pour chaque client Raspberry Pi 4 /!\

```
pi@client:~/BOOTROM $ sudo rpi-eeprom-config --out netboot-pieeprom.bin --config bootconf.txt new-pieeprom.bin
```


### 8/ Flashage du Raspberry Pi avec la nouvelle EEPROM.

```
pi@client:~/BOOTROM $ sudo rpi-eeprom-update -d -f netboot-pieeprom.bin
BCM2711 detected
Dedicated VL805 EEPROM detected
*** INSTALLING ./netboot-pieeprom.bin  ***
BOOTFS /boot
EEPROM update pending. Please reboot to apply the update.
```


### 9/ Récupération du numéro de série. 

```
pi@client:~/BOOTROM $ cat /proc/cpuinfo | grep Serial | awk -F ': ' '{print $2}' | tail -c 8
1b0f235
```


### 10/ Récupération de l’adresse MAC. 

```
pi@client:~/BOOTROM $ ip addr show eth0 | grep ether | awk '{print $2}'
dc:a6:32:22:ce:87
```


### 11/ Arrêter le Raspberry Pi.


### 12/ Enlever la carte SD.

Elle sera alors utilisé pour flasher les autres Raspberry Pi.


### 13/ Répéter les opérations 8/ à 11/ pour chaque Raspberry. 
