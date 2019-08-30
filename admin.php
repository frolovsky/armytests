<?php 

session_start();

// Вывод ошибок при авторизации

	if ($_SESSION['error']) {
		$errorMode = true;
	}
	if ($_SESSION['adminkaf'] && $_SESSION['adminlog']) {
		include("db.php");
		include("functions.php");

		$login = $_SESSION['adminlog'];

		$sql = $pdo->query("SELECT * FROM admins WHERE login='$login'");
		$curpas = md5($sql->fetch()['password']);
		if ($_SESSION['adminkaf'] != $curpas) {
			exit();
		} else {
			$appRole = 'allowed';
		}
	} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Панель администратора</title>
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<link rel="stylesheet" href="css/style.css">
	<link href="img/favicon.png" rel="icon">
</head>
<body>
	<header class="main-header">
		<div class="container">
		<?php if (!$appRole || $appRole != 'allowed'): ?>
			<h1 class="mh-title">Панель администратора</h1>
		<?php endif; ?>
		<?php if ($appRole && $appRole == 'allowed'): ?>
			<h1 class="mh-title">Панель администратора</h1>
			<div class="mh-app">
				<p class="mh-app_login">Вы вошли как, <?php echo $login ?>.</p>
				<button class="mh-app_logout btn-style form-submit">Выйти</button>
			</div>
		<?php endif; ?>
		</div>
	</header>
	<main>
		<?php if (!$appRole || $appRole != 'allowed'): ?>
		<section class="auth-section">
			<div class="container">
				<div class="auth">
					<div class="auth-controls">
						<div class="auth-control br-ac active">Авторизация</div>
					</div>
					<div class="auth-out">
						<form action="" method="post" class="auth-form active" id="login_form">
							<p class="auth-desc">Введите ваши логин и пароль, чтобы войти в Панель администратора.</p>
							<div class="form-row">
								<label class="auth-label al-user">
									<span class="auth-label_name">Логин</span>
									<input type="text" class="auth-input" name="login" maxlength="20" required>
								</label>
							</div>	
							<div class="form-row">
								<label class="auth-label al-pass">
									<span class="auth-label_name">Пароль</span>
									<input type="password" class="auth-input" name="password" maxlength="32" required>
								</label>
							</div>
							<div class="form-row">
								<input type="submit" class="btn-style form-submit" name="do_auth" value="Войти">
							</div>
						</form>
						<?php if ($errorMode): ?>
						<div class="error-auth">
							<p class="error-text">
								<?php echo $_SESSION['error'] ?>
							</p>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>
		<?php if ($appRole && $appRole == 'allowed'): ?>
			<section class="admin-panel">
				<div class="container">
					<ul class="admin-list">
						<li class="admin-item active" data-tab="1">Главная</li>
						<li class="admin-item subparent" data-tab="2-1">
							Отчеты
							<ul class="admin-sublist">
								<li class="admin-subitem" data-tab="2-1">По студентам</li>
								<li class="admin-subitem" data-tab="2-2">По взводам</li>
								<li class="admin-subitem" data-tab="2-3">По тестам</li>
							</ul>
						</li>
						<li class="admin-item" data-tab="3">Добавить студента</li>
						<li class="admin-item" data-tab="4">Управление тестами</li>
						<li class="admin-item" data-tab="5">Найти студента</li>
					</ul>
					<div class="adm-content">
						<div class="adm-content_out active" data-tab="1">
							<div class="last-tests">
								<p class="content_title">10 последних пройденных тестов</p>
								<?php 
								$sql = $pdo->prepare("SELECT * FROM (SELECT * FROM test_results ORDER BY id DESC LIMIT 10) t ORDER BY id");
								$sql->execute();
								$resultTests = $sql->fetchAll();
								$testCount = count($resultTests);
								?>
								<table class="last-table">
									<thead class="table-header">
										<tr>
											<th class="th-num">№</th>
											<th class="th-name">Студент</th>
											<th class="th-test">Тест</th>
											<th class="th-points">Баллы</th>
											<th class="th-time">Время</th>
											<th class="th-total">Статус</th>
										</tr>
									</thead>
									<tbody class="table-body">
										<?php for($i = 0; $i < $testCount; $i++) { ?>
											<?php $curTest = $resultTests[$i] ?>
											<tr>
												<td><?php echo $i + 1 ?></td>
												<td><?php echo $curTest['test_user'] ?></td>
												<td><?php echo $curTest['test_name'] ?></td>
												<td><?php echo $curTest['test_score'] ?></td>
												<td><?php echo formateTestTime($curTest) ?></td>
												<td class="td-status"><?php echo checkTestRight($curTest)['card-status'] ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="adm-content_out" data-tab="2-1">
							<p class="content_title">Статистика всех студентов</p>
							<?php 
								$sql = "SELECT * FROM users";
								$stmt = $pdo->prepare($sql);
								$stmt->execute();
								$users = $stmt->fetchAll();

							?>
							<table class="last-table students">
								<thead class="table-header">
									<tr>
										<th class="th-num">ID</th>
										<th class="th-name">Студент</th>
										<th class="th-vzvod">Взвод</th>
										<th class="th-test">Пройденные тесты</th>
										<th class="th-res">Результаты</th>
									</tr>
								</thead>
								<tbody class="table-body">
									<?php for ($i = 0; $i < count($users); $i++) { ?>
									<?php
									 	$user = $users[$i]; 
									 	$uid = $user['id'];

									 	$sql = 'SELECT * FROM test_results WHERE test_userid = ?';
									 	$stmt = $pdo->prepare($sql);
									 	$stmt->execute([$uid]);

									 	$userTestInfo = $stmt->fetchAll();
									?>
									<tr>
										<td class="td-id"><?php echo $user['id'] ?></td>
										<td><?php echo $user['login'] ?></td>
										<td class="td-vzvod"><?php echo $user['vzvod'] ?></td>
										<td class="td-count"><?php echo count($userTestInfo) ?></td>
										<td class="td-res">
											<?php if(count($userTestInfo) == 0): ?>
												Нет результатов
											<?php elseif(count($userTestInfo > 0)): ?>
											<div class="outres">
												<?php for($j = 0; $j < count($userTestInfo); $j++) { ?>
													<?php $testInfo = $userTestInfo[$j]; ?>
												<div class="outres-row">
													<p class="outres-name"><?php echo $testInfo['test_name'] ?></p>
													<p class="outres-score"><?php echo $testInfo['test_score'] . '/' . totalScoreSum($testInfo) ?></p>
													<p class="outres-date"><?php echo $testInfo['test_date'] ?></p>
													<p class="outres-status"><?php echo checkTestRight($testInfo)['card-status'] ?></p>
												</div>
											<?php } ?>
											</div>
											<?php endif; ?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="adm-content_out" data-tab="2-2">
							<p class="content_title">Отчёт по взводам</p>
							<p class="content_desc">Здесь вы можете посмотреть общую статистику по отдельному взводу. Можете посмотреть усредненную статистику и определить лучший взвод.</p>
							<?php  
							$sql = "SELECT DISTINCT vzvod FROM users";
							$stmt = $pdo->prepare($sql);
							$stmt->execute();
							$vzvods = $stmt->fetchAll();

							$countVzvod = count($vzvods);
							?>
							<p class="content_desc text-bold">Всего взводов : <?php echo $countVzvod; ?></p>
							<?php for($i = 0; $i < $countVzvod; $i++) { ?>
								<div class="vz-stat">
									<p class="vz-stat_name">Взвод: <?php echo $vzvods[$i]['vzvod'] ?></p>
									<div class="vz-stat_list">
										<?php 
										$userVzvod = $vzvods[$i]['vzvod'];
										$sql = "SELECT * FROM users WHERE vzvod = ?";
										$stmt = $pdo->prepare($sql);
										$stmt->execute([$userVzvod]);
										$users = $stmt->fetchAll();

										?>
										<div class="vz-stat_info">
											<p class="content_title-min">Информация по взводу</p>
											<div class="vz-stat_info-table">
												<p class="vz-stat_info-row">Всего студентов: <?php echo count($users) ?></p>
											</div>
										</div>
										<table class="last-table students">
											<thead class="table-header">
												<tr>
													<th class="th-num">ID</th>
													<th class="th-name">Студент</th>
													<th class="th-vzvod">Взвод</th>
													<th class="th-test">Пройденные тесты</th>
													<th class="th-res">Результаты</th>
												</tr>
											</thead>
											<tbody class="table-body">
												<?php for ($j = 0; $j < count($users); $j++) { ?>
													<?php
													$user = $users[$j]; 
													$uid = $user['id'];

													$sql = 'SELECT * FROM test_results WHERE test_userid = ?';
													$stmt = $pdo->prepare($sql);
													$stmt->execute([$uid]);

													$userTestInfo = $stmt->fetchAll();
													?>
													<tr>
														<td class="td-id"><?php echo $user['id'] ?></td>
														<td><?php echo $user['login'] ?></td>
														<td class="td-vzvod"><?php echo $user['vzvod'] ?></td>
														<td class="td-count"><?php echo count($userTestInfo) ?></td>
														<td class="td-res">
															<?php if(count($userTestInfo) == 0): ?>
																Нет результатов
																<?php elseif(count($userTestInfo > 0)): ?>
																	<div class="outres">
																		<?php for($k = 0; $k < count($userTestInfo); $k++) { ?>
																			<?php $testInfo = $userTestInfo[$k]; ?>
																			<div class="outres-row">
																				<p class="outres-name"><?php echo $testInfo['test_name'] ?></p>
																				<p class="outres-score"><?php echo $testInfo['test_score'] . '/' . totalScoreSum($testInfo) ?></p>
																				<p class="outres-date"><?php echo $testInfo['test_date'] ?></p>
																				<p class="outres-status"><?php echo checkTestRight($testInfo)['card-status'] ?></p>
																			</div>
																		<?php } ?>
																	</div>
																<?php endif; ?>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>	

								<?php } ?>
							</div>
						<div class="adm-content_out" data-tab="2-3">
							<p class="content_title">Отчёт по тестам</p>
							<p class="content_desc">Страница находится в разработке. Будет показывать какой тест вызвал у студентов проблемы.</p>
						</div>
						<div class="adm-content_out" data-tab="3">
							<p class="content_title">Добавить студента</p>
							<p class="content_desc">Чтобы внести студента, пожалуйста заполните всё поля. После чего вы можете добавить ещё одного студента, нажав на иконку «Плюсика», повится ещё одно поле, таким образом вы можете вводить данные нескольких студентов одновременно. Пароли геренируются автоматически и высылаются на почту студента или преподавателя.<br>
							Если вы не укажите электронную почту, то данные для входа студента будут высланы Вам на почту.</p>
							<div class="add-student">
								<form action="" method="post" class="add-student_form" id="add_students">
									<div class="add-student_control">
										<button type="button" class="add-student_btn form-submit add-student_more">Добавить ещё 1 поле</button>
										<button type="submit" class="add-student_btn form-submit add-student_do" title="При нажатии на кнопку вы вносите всех студентов ниже в БД">Добавить в базу данных</button>
									</div>
									<div class="addfrom_out">
										<div class="addform-row">
											<input required type="text" name="sname[]" placeholder="Логин (Фамилия И.О.)" class="addform-input addform-input_name">
											<input required type="text" name="svzvod[]" placeholder="Взвод (номер)" class="addform-input addform-input_vzvod">
											<input type="text" name="smail[]" placeholder="Электронная почта (необязательно)" class="addform-input addform-input_mail">
											<div class="remove-this" title="Удалить поле"></div>
										</div>
									</div>
								</form>
								<?php if ($_SESSION['add_status']): ?>
									<div class="add-status">
										<p class="add-status-text">
											<?php echo $_SESSION['add_status'] ?>
										</p>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div class="adm-content_out" data-tab="4">
							<p class="content_title">Управление тестами</p>
							<p class="content_desc">Функция находится в разработке.</p>
						</div>
						<div class="adm-content_out" data-tab="5">
							<p class="content_title">Поиск по студенту</p>
							<p class="content_desc">Для поиска укажите фамилию студента. После этого нажмите на кнопку «Найти». Необязательно указывать полностью всё фамилию и инициалы.</p>
							<div class="adm-search">
								<form action="" method="post" class="search-form">
									<div class="search-form_row">
										<input type="text" class="search-input auth-input" name="search_student" placeholder="Фамилия студента">
										<button class="form-submit search-submit" type="button">Найти</button>
									</div>
								</form>
								<div class="adm-search_res">
									<p class="content_title-min">Результат поиска</p>
									<div class="res-table">
										<div class="res-table_account">
											<p class="res-table_text">Логин студента: <span class="res-table_name"></span></p>
											<p class="res-table_text">Пароль студента: <span class="res-table_password"></span></p>
											<p class="res-table_text">Электронная почта: <span class="res-table_email"></span></p>
											<p class="res-table_text">Взвод: <span class="res-table_vzvod"></span></p>
										</div>
										<div class="res-table_tests">
											<p class="content_title-min res-table_tests-title">Тесты</p>
											<div class="search-testlist">
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</main>
	<footer class="main-footer">
		<div class="container">
			<p class="mf-copyright">2019 &copy; Создано студентами МИРЭА</p>
		</div>
	</footer>
	<script
	src="https://code.jquery.com/jquery-3.4.0.min.js"
	integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg="
	crossorigin="anonymous"></script>
	<script src="js/admin.js"></script>
</body>
</html>