$(document).ready(function() {
	

	// start ajax requests to PHP
	$('#login_form').submit(function(event) {
		$.ajax({
			url: '../core/adminlogin.php',
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

	$('.mh-app_logout').click(function(event) {
		$.ajax({
			url: '../core/adminlogout.php',
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

	// add students request
	$('#add_students').submit(function(event) {
		$.ajax({
			url: '../core/addstudent.php',
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

	// end AJAX requests for PHP

	// tabs admin panel

	$('.admin-item, .admin-subitem').on('click', function(event) {
		var tabNumber = event.target.attributes['data-tab'].nodeValue,
			content = $('.adm-content_out[data-tab="'+ tabNumber +'"]');
		$('.admin-item, .admin-subitem').removeClass('active');
		$(this).addClass('active');
		$('.adm-content_out').fadeOut();
		setTimeout(function(){
			content.fadeIn();
		}, 400);
	});




	/* 
		=== ДОБАВИТЬ СТУДЕНТА ===	
	*/
	// clone for add students
	$('.add-student_more').click(function(){
		$(".addform-row:first").clone().appendTo('.addfrom_out');
	});

	// remove row add students
	$('.add-student_form').on('click', '.remove-this', function(event) {
		event.preventDefault();
		$(this).parent('.addform-row').remove();
	});


	/* 
		=== ОТЧЁТЫ ===
	*/

	// Отчёт по взводам, выезжающие списки

	$('.vz-stat_name').click(function(){
		$(this).toggleClass('active');
		$(this).next('.vz-stat_list').slideToggle();
	});


	/* 
		=== Поиск студентов по фамилии ===
	*/

	// ajax to script for search
	$('.search-submit').click(function(event) {
		var searchInput = $('.search-input').val();
		$('.search-testlist').empty();
		$('.adm-search_res').fadeIn();
		$.ajax({
			url: '../core/searchstudent.php',
			type: 'POST',
			data: {search_student: searchInput},
			success: function(response) {
				$('.res-table_name').text(response[0]['login']);
				$('.res-table_password').text(response[0]['password']);
				$('.res-table_email').text(response[0]['email']);
				$('.res-table_vzvod').text(response[0]['vzvod']);

				if (response.length <= 1) {
					$('.search-testlist').append('<p class="search-empty">Студент пока не выполнил ни одного теста.</p>');
				}

				for (var i = 1; i < response.length; i++) {
					var testData = response[i];
					$('.search-testlist').append('<div class="search-testitem"><p>'+testData["test_name"]+'</p><p>'+formateTestTime(testData)+'</p><p>'+testData["test_date"]+'</p><p>'+testData["test_score"]+'/'+totalScoreTest(testData)+'</p></div>');
				}

			},
			dataType: "json"
		})
		.done(function() {
			console.log('done');
		})
		.fail(function() {
			console.log("error");
		})
		event.preventDefault();
		return false;
	});

	function totalScoreTest(row) {
		var totalSum = +row["test_minscore"] / +row["test_percent"] * 100;
		return totalSum;
	}

	function formateTestTime(row) {
		var timeB = row['test_time'];
		var newTimeMin = '' + Math.floor(timeB / 60) + ' мин ';
		if (Math.floor(timeB / 60) == 0) {
			newTimeMin = '';
		}
		newTimeSec = '' + (timeB % 60) + ' сек';
		newTime = newTimeMin + newTimeSec;
		return newTime;
	}

});