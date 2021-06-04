#!/bin/sh

ldapadd -x -f user.ldif -W -D cn=admin,dc=technovz-serveur-rasp
