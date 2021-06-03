#!/bin/sh

ldapadd -x -f e.ldif -W -D cn=admin,dc=technovz-serveur-rasp
ldapadd -x -f p.ldif -W -D cn=admin,dc=technovz-serveur-rasp
