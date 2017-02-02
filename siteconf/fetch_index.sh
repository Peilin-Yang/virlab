#!/bin/bash

wget https://s3.amazonaws.com/virlab/index.tar.gz
tar xfz index.tar.gz
rm index.tar.gz
cp -r index /var/www/virlab/public_html/
