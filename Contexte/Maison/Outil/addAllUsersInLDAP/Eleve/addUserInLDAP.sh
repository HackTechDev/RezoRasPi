#!/bin/sh

ldapadd -x -f user.ldif -W -D cn=admin,dc=college-vouziers,dc=fr
