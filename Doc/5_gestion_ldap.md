Lien :
http://uid.free.fr/Ldap/ldap.html
https://www.thegeekstuff.com/2015/02/openldap-add-users-groups/
https://www.learnitguide.net/2017/11/how-to-create-ldap-users-and-groups.html

1/ Générartion d'un mot de passe : 

util01@college-vouziers:~$ PASSWORD=`slappasswd -s supermotdepassesecret -h \{SSHA\}`
util01@college-vouziers:~$ echo $PASSWORD
{SSHA}kNwFpvBkDgVZEoR47sUA/4vW/PSDItir


1/ Création d'utilisateur

Ouvrir : 

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
userPassword: 
```


2/ Ajouter l'utilisateur :

```
util01@college-vouziers:~$ ldapadd -x -f user.ldif -W -D cn=admin,dc=college-vouziers,dc=fr
Enter LDAP Password: 
adding new entry "cn=solomon kane,dc=college-vouziers,dc=fr"

```



