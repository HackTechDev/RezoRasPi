#!/bin/sh

# Compress with hidden files:
# tar cvfz template.tar.gz template/. 

# Get the current user
USER="$(whoami)"
GROUP=500

# Arduino

sudo usermod -a -G dialout $USER
sudo chmod a+rw /dev/ttyACM0

# Uncompress the Desktop archive in the current home user
tar -xvf template.tar.gz --strip 1

# Permissions
chown $USER:$GROUP .local -R
chmod 755 .config/ -R
