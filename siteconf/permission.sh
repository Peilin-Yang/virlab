#!/bin/bash

chown -R $1 /var/www/virlab/public_html/upload/
chmod -R 755 /var/www/virlab/public_html/upload/

chown -R $1 /var/www/virlab/public_html/users/
chmod -R 755 /var/www/virlab/public_html/users/

chown -R $1 /var/www/virlab/public_html/out/
chmod -R 755 /var/www/virlab/public_html/out/
