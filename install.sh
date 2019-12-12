#!/usr/bin/env bash

if [ "$EUID" -ne 0 ]
  then echo "You must run the installer as root."
  exit 1
fi

echo "Installing base installer packages..."

apt update
apt install -y php-cli php-curl php-mysql php-mbstring php-xml php-zip

echo "Starting installer..."

php artisan auto-install

exit 0
