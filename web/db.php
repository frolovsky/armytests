<?php 

define('DB_HOST', 'localhost');
define('DB_NAME', 'a0252265_kafedra');
define('DB_USER', 'a0252265_remont');
define('DB_PASSWORD', 'VR9Ixcvm');
$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD);

?>