#### Supprimer tous les utilisateurs


### Rechercher tous les utilisateur

ldapsearch -o ldif-wrap=no -Q -LL -Y EXTERNAL -H ldapi:/// -b dc=college-vouziers,dc=fr >  userFound.txt


### Chercher les lignes correspondant au utilisaterus

cat userFound.txt | grep "dn: cn" > userFound1.txt


### Avec vi, pour le fichier 'userFound1.txt ', supprimer le début de chaque ligne : 

:1,$s/dn: //g


### Supprimer les utilisateurs de la liste

ldapdelete -x -D cn=admin,dc=college-vouziers,dc=fr -W -r -f userFound1.txt
