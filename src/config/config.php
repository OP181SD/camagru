<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Europe/Paris');

define('APP_NAME', 'Camagru');
define('BASE_URL', 'http://localhost:8000');

define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASSWORD'));