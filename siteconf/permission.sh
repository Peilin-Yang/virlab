#!/bin/bash

mkdir -p /var/www/virlab/public_html/index/
chown -R $1 /var/www/virlab/public_html/index/
chmod -R 755 /var/www/virlab/public_html/index/

mkdir -p /var/www/virlab/public_html/upload/
chown -R $1 /var/www/virlab/public_html/upload/
chmod -R 755 /var/www/virlab/public_html/upload/

mkdir -p /var/www/virlab/public_html/users/
chown -R $1 /var/www/virlab/public_html/users/
chmod -R 755 /var/www/virlab/public_html/users/

mkdir -p /var/www/virlab/public_html/out/
chown -R $1 /var/www/virlab/public_html/out/
chmod -R 755 /var/www/virlab/public_html/out/