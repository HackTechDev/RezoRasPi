#!/bin/sh

ldapadd -x -f eleve.ldif -W -D cn=admin,dc=technovz-serveur-rasp
ldapadd -x -f prof.ldif -W -D cn=admin,dc=technovz-serveur-rasp
