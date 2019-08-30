<?php 

if (!$_POST) exit('No direct script access allowed');

session_start();

include("../db.php");

$search = htmlspecialchars(trim($_POST['search_student']));

$sql = "SELECT * FROM users WHERE login LIKE '$search%'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$userData = $stmt->fetchAll();
$userId = $userData[0]['id'];

$sql = "SELECT * FROM test_results WHERE test_userid = $userId";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);

$testData = $stmt->fetchAll();

$fullData = array_merge($userData, $testData);

echo json_encode($fullData);
exit();

