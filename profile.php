<?php 

if (!$_POST) exit('No direct script access allowed');

session_start();

include("db.php");


$name = trim($_POST['name']);
$surname = trim($_POST['surname']);
$vzvod = trim($_POST['vzvod']);
$group = trim($_POST['group']);

$sql = $pdo->prepare("SELECT id FROM users WHERE login = :login");
$sql->execute(['login' => $_SESSION['voenlog']]);
$accountId = $sql->fetch(PDO::FETCH_ASSOC);
$accountId = $accountId['id'];

$sql = "UPDATE profile SET profile_name=?, profile_surname=?, profile_vzvod=?, profile_group=? WHERE user_id=?";
$stmt= $pdo->prepare($sql);
$stmt->execute([$name, $surname, $vzvod, $group, $accountId]);


