<?php
require '../vendor/autoload.php';

use Carbon\Carbon;

require '../app/Environment.php';
$env = new Environment();

require '../app/Database.php';
$db = new Database($env);

require '../app/Api.php';
$api = new Api();


// find out now
$staticBegin = new Carbon('2019-03-08 12:00:00');
$staticEnd = new Carbon('2019-03-08 12:00:00');
$begin = $_GET['begin'] ?? $staticBegin->subDays(7);
$end = $_GET['end'] ?? $staticEnd;

// get data
$api->json($db->getWeather($begin, $end));

