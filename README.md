# VIRLab

VIRLab aims for the easy implementation of Information Retrieval models and the automation of evaluation.

Please cite the following paper if your are using it someway...

```
@inproceedings{Fang:2014:VWV:2600428.2611178,
 author = {Fang, Hui and Wu, Hao and Yang, Peilin and Zhai, ChengXiang},
 title = {VIRLab: A Web-based Virtual Lab for Learning and Studying Information Retrieval Models},
 booktitle = {Proceedings of the 37th International ACM SIGIR Conference on Research \&\#38; Development in Information Retrieval},
 series = {SIGIR '14},
 year = {2014},
 isbn = {978-1-4503-2257-7},
 location = {Gold Coast, Queensland, Australia},
 pages = {1249--1250},
 numpages = {2},
 url = {http://doi.acm.org/10.1145/2600428.2611178},
 doi = {10.1145/2600428.2611178},
 acmid = {2611178},
 publisher = {ACM},
 address = {New York, NY, USA},
 keywords = {ir models, teaching, virtual lab},
}
```


## Prerequisites 

Apache+MySQL+PHP

__notice: only tested on Ubuntu 14.04_

## Installation 

```
git clone git@github.com:Peilin-Yang/virlab.git
cd virlab/siteconf
sudo ./setup.sh
sudo ./fetch_index.sh
mysql -u <username> -p < virlab.sql
```
Go to your web browser and type `localhost/whoiam.php`
Remember the output and run
`sudo ./permission.sh <output>`



