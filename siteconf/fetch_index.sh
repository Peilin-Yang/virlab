#!/bin/bash

wget https://s3.amazonaws.com/virlab/index.tar.gz
tar xfz index.tar.gz
mv index /var/www/virlab/public_html/