<?php 


// Функция проверки теста сдан/не сдан. Просто присваивает классы, вернее определяет массив в котором указаны классы и выводящиеся данные.
function checkTestRight($row) {
	$testStyles = array('card-list' => 'good','card-status' => 'Пройден');
	if ($row['test_score'] < $row['test_minscore']) {
		$testStyles = array('card-list' => 'bad','card-status' => 'Не пройден');
	}
	return $testStyles;
}

// Функция подсчета максимального количества баллов.
function totalScoreSum($row) {
	$totalScoreSum = $row['test_minscore'] / $row['test_percent'] * 100;
	return $totalScoreSum;
}

// Функция форматирования времени прохождения теста
function formateTestTime($row) {
	$timeB = $row['test_time'];
	$newTimeMin = '' . floor($timeB / 60) . ' мин ';
	if (floor($timeB / 60) == 0) {
		$newTimeMin = '';
	}
	$newTimeSec = '' . ($timeB % 60) . ' сек';
	$newTime = $newTimeMin . $newTimeSec;
	return $newTime;
}

// Парсим доступные тесты и их данные
function parseTest() {
	$mainDir = __DIR__ . '/tests';
	$testArray = scandir($mainDir);
	$tests = array();
	foreach ($testArray as $key => $value) {
		if (strlen($value) > 3) {
			array_push($tests, $value);
		}
	}
	
	$totalTests = count($tests);
	$testsData = array();

	for ($i=0; $i < $totalTests; $i++) { 
		$value = $tests[$i];
		
		$testDir = $mainDir . '/' . $value;

		$testsData[$i]['image'] = 'tests/'. $value . '/thumbnail.jpg';
		$testFile = file_get_contents($testDir . '/index.html');
		preg_match_all('#<title>(.+?)</title>#su', $testFile, $res);
		$testsData[$i]['title'] = $res[1][0];
		$testsData[$i]['testpath'] = $value; 
	}	
	return $testsData;
}