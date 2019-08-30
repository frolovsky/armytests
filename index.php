<?php 
	session_start();

	// Вывод ошибок при авторизации

	if ($_SESSION['error']) {
		$errorMode = true;
	}

	if ($_SESSION['voenkaf'] && $_SESSION['voenlog']) {
		include("db.php");
		include("functions.php");

		$login = $_SESSION['voenlog'];

		$sql = $pdo->query("SELECT * FROM users WHERE login='$login'");
		$curpas = md5($sql->fetch()['password']);
		if ($_SESSION['voenkaf'] != $curpas) {
			exit();
		} else {
			$appRole = 'app';
		}

		// Данные аккаунта (с 06.05.2019 берем только ID)

		$sql = $pdo->prepare("SELECT id FROM users WHERE login='$login'");
		$sql->execute();
		$id = $sql->fetchAll(PDO::FETCH_ASSOC);

		$id = $id[0]['id'];
		$_SESSION['id'] = $id;

		// Личные данные профиля

		$sql = $pdo->prepare("SELECT user_id FROM profile WHERE user_id = $id");
		$sql->execute();
		$createStatus = $sql->fetch(PDO::FETCH_ASSOC);
		$createStatus = $createStatus['user_id'];
		$profileInfo = 'clean';

		// Если данных нет, то создаем новую таблицу
		if ( is_null($createStatus) ) {
			$sql = 'INSERT INTO profile SET 
				user_id = :uid
			';
			$query = $pdo->prepare($sql);
			$query->execute([
				'uid' => $id
			]);
		} else {
			$sql = $pdo->prepare("SELECT profile_name, profile_surname, profile_vzvod, profile_group FROM profile WHERE user_id = $id");
			$sql->execute();
			$profileData = $sql->fetch(PDO::FETCH_ASSOC);

			$profileInfo = 'got';
			// Личные данные из БД в $profileData

			foreach ($profileData as $key => $value) {
				if ($value == '' || !$value) {
					$profileInfo = 'clean';
				}
			}
			
		}

		// Режим прохождения теста

		if ( $_SESSION['testsrc'] ) {
			$testMode = 'started';
		}
		
	} 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<?php if (isset($_SESSION['voenlog'])): ?>
	<title>Личный кабинет</title>
	<?php else: ?>
	<title>Авторизация</title>
	<?php endif; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/style.css">
	<link href="img/favicon.png" rel="icon">
</head>
<body>
	<header class="main-header">
		<div class="container">
		<?php if (!$appRole || $appRole != 'app'): ?>
			<h1 class="mh-title">Тесты по тактической подготовке</h1>
		<?php endif; ?>
		<?php if ($appRole && $appRole == 'app'): ?>
			<h1 class="mh-title">Тесты по тактической подготовке</h1>
			<div class="mh-app">
				<p class="mh-app_login">Вы вошли как, <?php echo $login ?>.</p>
				<button class="mh-app_logout btn-style form-submit">Выйти</button>
			</div>
		<?php endif; ?>
		</div>
	</header>
	<main>
	<?php if (!$appRole || $appRole != 'app'): ?>
		<section class="auth-section">
			<div class="container">
				<div class="auth">
					<div class="auth-controls">
						<div class="auth-control br-ac active" data-tab="1">Авторизация</div>
					</div>
					<div class="auth-out">
						<form action="" method="post" data-tab="1" class="auth-form active" id="login_form">
							<p class="auth-desc">Введите ваши логин и пароль, чтобы войти в Личный кабинет.</p>
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
							<p class="auth-desc auth-desc_min">
								Для получения логина и пароля подойдите к преподавателю.
							</p>
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
		
		<!-- Панель с тестами у авторизованного пользователя -->

		<?php if ($appRole && $appRole == 'app'): ?>
		<section class="app minifed <?php if ($testMode && $testMode == 'started'): ?>started<?php endif; ?>">
			<aside class="app-sidebar">
				<ul class="sidebar-list">
					<li class="sidebar-item sidebar-control" data-tab="1">Мой профиль</li>
					<li class="sidebar-item sidebar-control" data-tab="2">Доступные тесты</li>
					<li class="sidebar-item sidebar-control" data-tab="3">Пройденные тесты</li>
					<li class="sidebar-item sidebar-control" data-tab="4">Информация о сервисе</li>
					<li class="sidebar-item sidebar-control" data-tab="5">Политика конфиденциальности</li>
				</ul>
				<div class="sidebar-testcontrol">
					<p class="sidebar-item close-test">Вернуться в личный кабинет</p>
				</div>
				<div class="minifed-icon">
					<span></span>
				</div>
			</aside>
			<div class="app-content">
				<div class="app-content_out active" data-tab="1">
					<h2 class="content_title">Мой профиль</h2>
					<p class="content_desc">Для того чтобы разблокировать доступ к тестам, необходимо полностью заполнить ваш профиль.</p>
					<div class="content_out">
						<?php if ($profileInfo == 'clean'): ?>
							<form action="" method="post" class="app-form" id="user_profile">
								<div class="form-row">
									<label class="auth-label">
										<span class="auth-label_name">Ваше имя: </span>
										<input required type="text" class="auth-input" name="name" maxlength="20" placeholder="Петр">
									</label>
								</div>	
								<div class="form-row">
									<label class="auth-label">
										<span class="auth-label_name">Ваша фамилия: </span>
										<input required type="text" class="auth-input" name="surname" maxlength="32" placeholder="Петров">
									</label>
								</div>
								<div class="form-row">
									<label class="auth-label">
										<span class="auth-label_name">Взвод: </span>
										<input required type="text" class="auth-input" name="vzvod" maxlength="32" placeholder="Введите номер взвода">
									</label>
								</div>
								<div class="form-row">
									<label class="auth-label">
										<span class="auth-label_name">Группа: </span>
										<input required type="text" class="auth-input" name="group" maxlength="12" placeholder="ХТП-116">
									</label>
								</div>
								<div class="form-row">
									<input type="submit" class="btn-style form-submit" name="profile_save" value="Сохранить">
								</div>
							</form>
						<?php else: ?>
							<form action="" method="post" class="app-form" id="user_profile">
								<div class="form-row">
									<label class="auth-label">
										<span class="auth-label_name">Ваше имя: </span>
										<input type="text" class="auth-input" name="name" maxlength="20" value="<?php echo $profileData['profile_name'] ?>">
									</label>
								</div>	
								<div class="form-row">
									<label class="auth-label">
										<span class="auth-label_name">Ваша фамилия: </span>
										<input type="text" class="auth-input" name="surname" maxlength="32" value="<?php echo $profileData['profile_surname'] ?>">
									</label>
								</div>
								<div class="form-row">
									<label class="auth-label">
										<span class="auth-label_name">Взвод: </span>
										<input type="text" class="auth-input" name="vzvod" maxlength="32" value="<?php echo $profileData['profile_vzvod'] ?>">
									</label>
								</div>
								<div class="form-row">
									<label class="auth-label">
										<span class="auth-label_name">Группа: </span>
										<input type="text" class="auth-input" name="group" maxlength="12" value="<?php echo $profileData['profile_group'] ?>">
									</label>
								</div>
								<div class="form-row">
									<input type="submit" class="btn-style form-submit" name="profile_save" value="Изменить">
								</div>
							</form>
							<p class="reg-info reg-complete">Ваш профиль заполнен, теперь вы можете приступать к тестам!</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="app-content_out" data-tab="2">
					<h2 class="content_title">Доступные тесты</h2>
					<p class="content_desc">Выбирите один тест ниже, чтобы начать его проходить. Подробную информацию и ответы на часто задаваемые вопросы, смотрите во вкладке «Информация о сервисе».</p>
					<div class="content_avtest">
						<?php $testArray = parseTest(); ?>
						<?php for($i = 0; $i < count($testArray); $i++) { ?>
							<?php $testOut = $testArray[$i] ?>
						<div class="test-card" data-test="<?php echo $testOut['testpath'] ?>">
							<figure class="test-card_figure">
								<img src="<?php echo $testOut['image'] ?>" alt="<?php echo $testOut['title'] ?>" class="test-card_img">
								<figcaption class="test-card_name"><?php echo $testOut['title'] ?></figcaption>
							</figure>
							<div class="test-card_content">
								<div class="test-card_info">
									<p class="test-card_text">Количество вопросов: <span>10</span>.</p>
									<p class="test-card_time">Время прохождения: <span>10 мин.</span></p>
								</div>
								<button class="test-card_btn btn-style form-submit" data-test="<?php echo $testOut['testpath'] ?>" type="button">Пройти</button>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="app-content_out" data-tab="3">
					<?php 
						$sql = $pdo->prepare("SELECT * FROM test_results WHERE test_userid = $id");
						$sql->execute();
						$resultTests = $sql->fetchAll();
						$testCount = count($resultTests);
					?>
					<h2 class="content_title">Пройденные тесты</h2>
					<p class="content_desc">Ниже представлены результаты тестов. На данный момент сервис находится в разработке, пока на данной странице вы можете видеть только конечный результат.</p>
					<div class="completed-test_list">
						<?php for($i = ($testCount - 1); $i >= 0; $i--) { ?>
							<?php 
								$testImage = 'tests/' . $resultTests[$i]['test_path'] . '/thumbnail.jpg';
								if ($resultTests[$i]['test_path'] == '') {
									$testImage = 'img/def-thumb.jpg';
								}
							 ?>
						<div class="completed-test <?php echo checkTestRight($resultTests[$i])['card-list']; ?>">
							<img src="<?php echo $testImage ?>" alt="test name" class="completed-test_image">
							<div class="completed-test_info">
								<p class="completed-test_name"><?php echo $resultTests[$i]['test_name']; ?></p>
								<div class="cti-norm">
									<p class="cti-norm_result">Результат выполнения : <span class="status_color"><?php echo $resultTests[$i]['test_score']; ?></span>/<?php echo totalScoreSum($resultTests[$i]) ?>.</p>
									<p class="cti-norm_time">Время выполнения : <span class="status_color"><?php echo formateTestTime($resultTests[$i]) ?></span>.</p>
									<p class="cti-norm_status"><span class="status_color"><?php echo checkTestRight($resultTests[$i])['card-status']; ?>.</span></p>
								</div>
								<div class="cti-controls">
									<button class="cti-controls_btn btn-style form-submit">Сообщить об ошибке</button>
									<p class="cti-data">Дата прохождения теста : <span><?php echo $resultTests[$i]['test_date']; ?></span></p>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="app-content_out" data-tab="4">
					<h2 class="content_title">Информация о сервисе</h2>
					<p class="content-text">Сервис разработан для студентов, которые так или иначе связаны с предметом «Тактическая подготовка». Все тесты для Вас составляет преподаватель, по прохождении теста, преподаватель получает результат и заносит его в журнал.</p>
					<p class="content-text">На каждый тест отдводится определенное количество времени, если Вы не успеете в предоставленный промежуток - тест считается непройденным. Для повторного прохождения теста обратитесь к преподавателю (для этого необходимо знать номер аккаунта).</p>
					<p class="content-text">При обнуружении ошибок в работе сайта, сообщайте преподавателю или пишите на почту support@tacticalpg.ru</p>
				</div>
				<div class="app-content_out" data-tab="5">
					<h2 class="content_title">Политика конфиденциальности</h2>
					<p class="content_desc">Обработку ваших персональных данных осуществляется в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных». Ваши данные не разглашаются третьим лицам и видны только преподавателю.</p>
				</div>
				<div class="app-content_out test-out" data-test="<?php echo $_SESSION['testsrc'] ?>">
					<iframe src="" frameborder="0" class="iframe-test" id="mainFrame"></iframe>
				</div>
			</div>
			<div class="underlay" id="underlay"></div>
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
	<script src="js/main.js"></script>
	
	<script>
		var iframe = document.getElementsByTagName('iframe')[0],
		iframeDoc = iframe.contentWindow.document;

		iframe.onload = function() {
			var iframeDoc = iframe.contentWindow.document;
			setTimeout(function(){
				var loginInput = iframeDoc.querySelector('.field-view__text-input');
				loginInput.focus();
				loginInput.value = '<?php echo $_SESSION['voenlog']; ?>';
				loginInput.setAttribute('value', '<?php echo $_SESSION['voenlog']; ?>');
			},1000);	
		}

		// Меняем куку активного окна
		var tabSelectors = document.querySelectorAll('.sidebar-control');

		for (var i=0; i < tabSelectors.length; i++) {
			var targetTab = tabSelectors[i];
			targetTab.addEventListener('click',function(){
				var savedTab = this.getAttribute('data-tab');
				document.cookie = 'savedTab='+savedTab;
			});
		}

		// Функция чтения куки
		function readCookie(name) {
			var name_cook = name+"=";
			var spl = document.cookie.split(";");
			for(var i=0; i<spl.length; i++) {
				var c = spl[i];
				while(c.charAt(0) == " ") {
					c = c.substring(1, c.length);
				}
				if(c.indexOf(name_cook) == 0) {
					return c.substring(name_cook.length, c.length);
				}
			}
			return null;	
		}
	</script>
	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
		(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
			m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
		(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

		ym(53598136, "init", {
			clickmap:true,
			trackLinks:true,
			accurateTrackBounce:true
		});
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/53598136" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
</body>
</html>