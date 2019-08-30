<?php

if (!$_POST) exit('No direct script access allowed');

session_start();

include("db.php");

$error_msg = '';

$login = trim($_POST['login']);
$password = trim($_POST['password']);

$sql = $pdo->query("SELECT * FROM users WHERE login='$login'");
$curpas = $sql->fetch()['password'];

$error_msg = '';

// Проверка на то есть ли вообще это СУЩЕСТВО В БАЗЕ ДАННЫХ
$sql = $pdo->query("SELECT * FROM users WHERE login='$login'");
$nologin = $sql->fetch(PDO::FETCH_ASSOC);

if (!$nologin) {
	$error_msg = 'Пользователя не существует.';
	$_SESSION['error'] = $error_msg;
	exit();
} elseif  ($curpas != $password) {
	$error_msg = 'Вы ввели неверный пароль.';
	$_SESSION['error'] = $error_msg;
	exit();
} else {
	$_SESSION['voenkaf'] = md5($curpas);
	$_SESSION['voenlog'] = $login;
	unset($_SESSION['error']); 
}


