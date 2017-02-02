#!/bin/bash

a2dissite *default
mkdir -p /var/www/virlab
mkdir -p /var/www/virlab/public_html
mkdir -p /var/www/virlab/log
mkdir -p /var/www/virlab/backups

cp virlab.conf /etc/apache2/sites-available/virlab.conf
cp -rf ../* /var/www/virlab/public_html/
cp -rf users /var/www/virlab/public_html/

a2ensite virlab.conf
service apache2 restart
