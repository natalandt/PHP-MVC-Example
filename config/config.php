<?php

/** Configuration Variables **/
define ('DEV_ENV',true);

define('DB_NAME', 'mvc');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'localhost');

define('BASE_PATH','http://localhost/');

define('PAGINATE_LIMIT', '50');

/** Set default controller and action **/
$default['controller'] = 'home';
$default['action'] = 'index';