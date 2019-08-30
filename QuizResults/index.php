<?php
    session_start();
    header('Access-Control-Allow-Origin: *');

    if ($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        echo "POST request expected";
        return;
    }

    define('DB_HOST', 'localhost');
    define('DB_NAME', 'a0252265_kafedra');
    define('DB_USER', 'a0252265_remont');
    define('DB_PASSWORD', 'VR9Ixcvm');
    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);

    // $XMLres = $_POST['dr'];
    $testName = $_POST['qt'];
    $testTime = $_POST['ut'];
    $testMaxTime = $_POST['tl'];
    $testScore = $_POST['sp'];
    $testNeedScore = $_POST['ps'];
    $testPercent = $_POST['psp'];
    $testUser = $_SESSION['voenlog'];
    $testUserId = $_SESSION['id'];
    $testDate = date('Y/m/d');
    $testPath = $_SESSION['testsrc'];

    $sql = 'INSERT INTO test_results SET
    test_name = :test_name,
    test_time = :test_time,
    test_maxtime = :test_maxtime,
    test_score = :test_score,
    test_minscore = :test_needscore,
    test_percent = :test_percent,
    test_user = :test_user,
    test_userid = :test_userid,
    test_date = :test_date,
    test_path = :test_path
    ';

    $query = $pdo->prepare($sql);
    $query->execute([
        'test_name' => $testName,
        'test_time' => $testTime,
        'test_maxtime' => $testMaxTime,
        'test_score' => $testScore,
        'test_needscore' => $testNeedScore,
        'test_percent' => $testPercent,
        'test_user' => $testUser,
        'test_userid' => $testUserId,
        'test_date' => $testDate,
        'test_path' => $testPath
    ]);

    error_reporting(E_ALL && E_WARNING && E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    require_once 'includes/common.inc.php';

    $requestParameters = RequestParametersParser::getRequestParameters($_POST, !empty($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : null);
    _log($requestParameters);

    try
    {
        $quizResults = new QuizResults();
        $quizResults->InitFromRequest($requestParameters);
        $generator = QuizReportFactory::CreateGenerator($quizResults, $requestParameters);
        $report = $generator->createReport();

        $dateTime = date('Y-m-d_H-i-s');
        $resultFilename = dirname(__FILE__) . "/result/quiz_result_{$dateTime}.txt";
        @file_put_contents($resultFilename, $report);

        echo "OK";
    }
    catch (Exception $e)
    {
        error_log($e);

        echo "Error: " . $e->getMessage();
    }

    function _log($requestParameters)
    {
        $logFilename = dirname(__FILE__) . '/log/quiz_results.log';
        $event       = array('ts' => date('Y-m-d H:i:s'), 'request_parameters' => $requestParameters, 'ts_' => time());
        $logMessage  = json_encode($event);
        $logMessage .= ',' . PHP_EOL;
        @file_put_contents($logFilename, $logMessage, FILE_APPEND);
    }

 