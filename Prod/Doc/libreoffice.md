### LibreOffice

#### Problème : 

Lors de l'enregistrement d'un fichier : 
```
Erreur lors de l'enregistrement du document Sans nom1:
Erreur générale.
Erreur d'entrée/sortie générale.
```

#### Solution : 

Liens : 
https://forum.ubuntu-fr.org/viewtopic.php?id=643851
https://forum.ubuntu-fr.org/viewtopic.php?id=1620011


Ouvrir: 

```
/usr/bin/soffice
```


Chercher et décommenter : 

```
# STAR_PROFILE_LOCKING_DISABLED=1
# export STAR_PROFILE_LOCKING_DISABLED
```

Chercher et commenter : 

```
SAL_ENABLE_FILE_LOCKING=1
export SAL_ENABLE_FILE_LOCKING
```


