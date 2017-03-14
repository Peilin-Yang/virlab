<?php
if (!defined('DBHOST')) define('DBHOST','db.eecis.udel.edu');
if (!defined('DBUSER')) define('DBUSER','dbdbadmin');
if (!defined('DBPASS')) define('DBPASS','ChangeME');
if (!defined('DBNAME')) define('DBNAME','virlab');
if (!defined('DBPORT')) define('DBPORT',3306);
if (!defined('MyUserGroupID')) define('MyUserGroupID', '1');
if (!defined('passsalt')) define('passsalt', "labvir");

if (!defined('UploadPath')) define('UploadPath','upload/');
if (!defined('BuildIndexProgram')) define('BuildIndexProgram','source/BuildIndexCmd');
if (!defined('IndexPath')) define('IndexPath','index/');
if (!defined('UserDataPath')) define('UserDataPath','users/');
if (!defined('RetrievalProgramDirect')) define('RetrievalProgramDirect','source/retrieval-flexible-web');
?>
