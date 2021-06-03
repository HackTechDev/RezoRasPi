Gestion LDAP
============


### 1/ Création d'un fichier ldif.

Créer : 

```
user.ldif
```

Ajouter : 

```
dn: cn=solomon kane,dc=college-vouziers,dc=fr
cn: solomon kane
givenName: solomon
gidNumber: 500
homeDirectory: /home/users/skane
sn: kane
objectClass: inetOrgPerson
objectClass: posixAccount
objectClass: top
uidNumber: 1002
uid: skane
loginShell: /bin/bash
userPassword: {SSHA}WSieWXegemQath5FClQFl1qBNgS0w4L/
```


### 2/ Ajouter l'utilisateur.

```
ldapadd -x -f user.ldif -W -D cn=admin,dc=college-vouziers,dc=fr
```


### 3/ Rechercher tous les utilisateurs / Lister tous les utilisateurs.

```
ldapsearch -Q -L -Y EXTERNAL -H ldapi:/// -b dc=college-vouziers,dc=fr
```


### 4/ Supprimer un utilisateur.

```
ldapdelete -x -D cn=admin,dc=college-vouziers,dc=fr -W "cn=solomon kane,dc=college-vouziers,dc=fr"
```
