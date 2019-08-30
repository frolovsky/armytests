$(document).ready(function() {

	// app page tabs
	$('.sidebar-item').on('click', function(event) {
		var tabNumber = $(this).attr('data-tab'),
			content = $('.app-content_out[data-tab="'+ tabNumber +'"]');
		$('.sidebar-item').removeClass('active');
		$(this).addClass('active');
		$('.app-content_out').fadeOut();
		setTimeout(function(){
			content.fadeIn();
		}, 400);
	});

	// ajax request

	// login script
	$('#login_form').submit(function(event) {
		$.ajax({
			url: '../login.php',
			type: 'POST',
			data: $(this).serialize()
		})
		.done(function() {
			location.reload();
		})
		.fail(function() {
			console.log("error");
		})
		event.preventDefault();
		return false;
	});

	// logout script

	$('.mh-app_logout').click(function(event) {
		$.ajax({
			url: '../logout.php',
			type: 'GET'
		})
		.done(function() {
			location.reload();
		})
		.fail(function() {
			console.log("error");
		})
		event.preventDefault();
		return false;
		
	});
	// end test ajax

	$('.close-test').click(function(event) {
		$.ajax({
			url: '../endtest.php',
			type: 'GET'
		})
		.done(function() {
			location.reload();
		})
		.fail(function() {
			console.log("error");
		})
		event.preventDefault();
		return false;
		
	});

	// update profile script

	$('#user_profile').submit(function(event) {
		$.ajax({
			url: '../profile.php',
			type: 'POST',
			data: $(this).serialize()
		})
		.done(function() {
			location.reload();
		})
		.fail(function() {
			console.log("error");
		})
		event.preventDefault();
		return false;
	});


	// test ajax load

	$('.test-card_btn').click(function(event) {
		let testNum = $(this).data('test');
		$.ajax({
			url: '../teststart.php',
			type: 'POST',
			data: {testSrc: ''+testNum+''},
		})
		.done(function() {
			console.log("success");
			location.reload();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});

	if ( $('.app').hasClass('started') ) {
		let testName = $('.test-out').data('test');
		$('.iframe-test').attr('src', 'tests/'+testName+'/index.html');
	}

	// Открываем сохраненную страницу

	var activeTab = readCookie('savedTab');
	if (activeTab == null) {
		activeTab = 1;
	}
	$('.sidebar-control, .app-content_out').removeClass('active');
	$('.sidebar-control[data-tab='+activeTab+']').addClass('active');
	$('.app-content_out[data-tab='+activeTab+']').addClass('active');

	// Скрипты для мобильной версии, боковое меню задвижное

	var clWidth = document.body.clientWidth;
	
	if (clWidth <= 880 ) {

		$('.minifed .app-sidebar').click(function(event){
			if (!event.target.classList.contains('sidebar-control')) {
				$('.app').removeClass('minifed');
				$('.minifed-icon').addClass('opened');
				$('#underlay').fadeIn();
			}
		});

		$('.app-sidebar').on('click', '.opened, .sidebar-control' , function(){
			$('.app').addClass('minifed');
			$('.minifed-icon').removeClass('opened');
			$('#underlay').fadeOut();
		});

		$('#underlay').click(function(){
			$(this).fadeOut();
			$('.app').addClass('minifed');
		});
	}

});