<?php
require 'vendor/autoload.php';

use Carbon\Carbon;

require 'Environment.php';
$env = new Environment();

require 'Database.php';
$db = new Database($env);

require 'Api.php';
$api = new Api();


// find out now
$staticBegin = new Carbon('2019-03-08 12:00:00');
$staticEnd = new Carbon('2019-03-08 12:00:00');
$begin = $_GET['begin'] ?? $staticBegin->subDays(7);
$end = $_GET['end'] ?? $staticEnd;

// get data
$api->json($db->getWeather($begin, $end));

