#### Procédure pour la génération des ldif et l'ajout dans l'annuaire

### Installation de unaccent

$ sudo apt install unaccent


### Génération du fichier ldif

./generateLDIF.sh


### Supprimer les accents

./removeUserAccent.sh


### Ajouter tous les users dans LDAP

./addUserInLDAP.sh
