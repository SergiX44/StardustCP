#!/usr/bin/env bash

if [ "$EUID" -ne 0 ]
  then echo "You must run the installer as root."
  exit 1
fi

echo "Installing base installer packages..."

apt update >> install.sh.log 2>&1
apt install -y php-cli php-curl php-mysql php-mbstring php-xml php-zip >> install.sh.log 2>&1

echo "Starting installer..."

## TODO: remove, dev purpose
rm .env
rm -rf .root_db
rm rr
##

php artisan auto-install

exit 0
