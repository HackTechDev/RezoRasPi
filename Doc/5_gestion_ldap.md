Lien :
http://uid.free.fr/Ldap/ldap.html
https://www.thegeekstuff.com/2015/02/openldap-add-users-groups/
https://www.learnitguide.net/2017/11/how-to-create-ldap-users-and-groups.html

1/ Création d'utilisateur

Ouvrir : 

```
user.ldif
```

Ajouter : 

```
dn: cn=samuel gondouin,dc=college-vouziers,dc=fr
cn: samuel gondouin
givenName: samuel
gidNumber: 500
homeDirectory: /home/users/sgondouin
sn: gondouin
objectClass: inetOrgPerson
objectClass: posixAccount
objectClass: top
uidNumber: 1000
uid: sgondouin
loginShell: /bin/bash
```

mail: 
userPassword: 

Ajouter l'utilisateur :

```
ldapadd -x -f ut.ldif -W -D cn=admin,dc=college-vouziers,dc=fr
```
