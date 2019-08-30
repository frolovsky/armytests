<?php 
if (!$_POST) exit('No direct script access allowed');

session_start();

include("../db.php");

$logins = $_POST['sname'];
$vzvods = $_POST['svzvod'];
$emails = $_POST['smail'];

$addCount = count($logins);

function generatePass() {
	$pass = '';
	for ($i = 0; $i < 8; $i++) {
		$randNum = mt_rand(0, 9);
		$pass .= $randNum;
	}
	return $pass;
}


for ($i = 0; $i < $addCount; $i++) {
	$login = $logins[$i];
	$vzvod = $vzvods[$i];
	$email = $emails[$i];
	if (!$email || $email == '') {
		$email = 'Не указан';
	}
	$password = generatePass();

	$stmt = $pdo->prepare("INSERT users SET login = :login, vzvod = :vzvod, password = :password, email = :email");
	$stmt->execute([
		'login' => $login,
		'password' => $password,
		'vzvod' => $vzvod,
		'email' => $email
	]);

	// Отправка информации о каждом на почту

	if ($email != 'Не указан') {

		$title	= "Тактическая подготовка";

		$message = "Логин: $login \nПароль: $password";
		mail($email, $title, $message);
	}

}
if ($addCount > 4) {
	$statusMsg = "Было добавлено $addCount человек";
} elseif ($addCount < 5 && $addCount > 1) {
	$statusMsg = "Было добавлено $addCount человека";
} else {
	$statusMsg = "Был добавлен 1 человек";
}

$_SESSION['add_status'] = $statusMsg;
