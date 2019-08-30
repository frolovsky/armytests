<?php 

define('DB_HOST', 'localhost');
define('DB_NAME', 'kafedra');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD);

?>