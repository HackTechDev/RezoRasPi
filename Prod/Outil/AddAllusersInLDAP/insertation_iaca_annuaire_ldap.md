#### Insertion d'utilisateur IACA dans l'annuaire LDAP


### Cleaner le fichier

Supprimer la 1er ligne qui est l'entête
Supprimer les "
Remplacer les , par ;


### Récupérer le dernier id insérer dans l'annuaire
 
ldapsearch -Q -L -Y EXTERNAL -H ldapi:/// -b dc=technovz-serveur-rasp | grep 'uidNumber' | sort -k2 -r


### Ajout d'un utilisateur sans mot de passe



