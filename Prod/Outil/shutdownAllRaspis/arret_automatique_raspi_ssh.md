#### Procédure pour le test d'arrêt automatique des Raspberry Pi par ssh.


### I/ Test de l'arrêt du Raspberry Pi par ligne de commande.

### 1/ Allumer le serveur


### 2/ Allumer un Raspberry Pi et se connecter avec l'utilisateur pi

- Lancer un terminal

- Eteindre le Raspberry Pi : 

$ sudo shutdown -h now

Normalement, le mot de passe de l'utilisateur 'pi' n'est pas demandé.


### 3/ Allumer un autre Raspberry Pi


### 4/ Récupérer l'adresse ip du Raspberry Pi

- Visualiser les interfaces réseaux : 

$ ip a


### 5/ Sur le serveur, lancer un terminal

- Se connecter en ssh sur le Raspberry Pi :

$ ssh pi@192.168.1.18

Le mot de passe de l'utilisateur 'pi' est demandé.


### 6/ Test d'extinction de Raspberry Pi via ssh.

$ ssh -t pi@192.168.1.18 'sudo shutdown -h now'

Le mot de passe de l'utilisateur 'pi' est demandé.


### II/ Génération de clés ssh.

### 1/ Sur le serveur, génération de la clé ssh privée et publique. 

$ ssh-keygen


### 2/ Vérification.

Se déplacer dans le répertoire home de 'pi' 

$ ls -l .ssh/


### 3/ Copie de la clé ssh publique pour l'utilisateur sur le Raspberry Pi.

$ ssh-copy-id pi@192.168.1.18

Le mot de passe de l'utilisateur 'pi' est demandé.


### 4/ Test.

$ ssh pi@192.168.1.17

Le mot de passe de l'utilisateur 'pi' n'est plus demandé.


### 5/ Test d'extinction de Raspberry Pi via ssh.

$ ssh -t pi@192.168.1.18 'sudo shutdown -h now'

Le mot de passe de l'utilisateur 'pi' n'est alors plus demandé.


### III/ Lancer du script

$ php ./shutdownAllRaspi.php



