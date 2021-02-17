#!/bin/sh

# /!\ To test /!\

# Get the current user
USER="$(whoami)"
GROUP=eleve

# Arduino

sudo usermod -a -G dialout $USER
sudo chmod a+rw /dev/ttyACM0

# Desktop

#Copy desktop client template archive
cp /home/pi/RASP_client.tar.gz .

#Uncompress the archive in the current home user
tar xvfe RASP_client.tar.gz

# Permissions
chown $USER:$GROUP .local -R
chmod 755 .config/ -R

# Add Internet proxy
