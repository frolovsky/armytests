<?php

include("db.php");

if (isset($_POST['do_reg'])) {
	$login = trim($_POST['login']);
	$password = trim($_POST['password']);
	$email = trim($_POST['email']);
	$datereg = date('Y-m-d H:i:s');

	$sql = 'INSERT INTO users SET 
		login = :login,
		password = :password,
		email = :email,
		date_reg = :datereg
	';
	$query = $pdo->prepare($sql);
	$query->execute([
		'login' => $login,
		'password' => $password,
		'email' => $email,
		'datereg' => $datereg
	]);


	// mail to user
	// $title 	   = "Тактическая подготовка - ваши данные.";

	// $text = "Добро пожаловать на сайт для тестирования по предмету «Тактическая подготовка». Ваши данные для входа:"
	// $message = "$text \nЛогин: $login \nПароль: $password";

	// mail($email, $title, $message);
}