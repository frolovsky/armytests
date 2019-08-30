<?php 

session_start();

if (!$_POST) exit('No direct script access allowed');

include("db.php");

$_SESSION['testsrc'] = $_POST['testSrc'];

?>