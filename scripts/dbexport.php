<?php

//define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once(ROOT . '/config/config.php');

// Stores backup of database in /db-backup/mvc-current_date.sql
$backupFile = ROOT . '/db-backup/' . DB_NAME . '-' . date("-YmdHis") . '.sql';
$command = 'mysqldump --opt -h' . DB_HOST . ' -u' . DB_USER . ' -p' . DB_PASSWORD . ' ' . DB_NAME . ' > ' . $backupFile;
system($command);

