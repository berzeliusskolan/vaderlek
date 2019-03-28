<?php
// echo PHP_INT_SIZE === 4 ? '32bit' : '64bit';
// phpinfo();
require 'vendor/autoload.php';

use Carbon\Carbon;

require 'Environment.php';
$env = new Environment();

require 'Database.php';
$db = new Database($env);

require 'Api.php';
$api = new Api();

// $c = new Carbon();
// var_dump($c);

// $c1 = Carbon::create(2019,3,8,12,0);
// var_dump($c1);



// find out now
$staticBegin = new Carbon('2019-03-08 12:00:00');
$staticEnd = new Carbon('2019-03-08 12:00:00');
$begin = $_GET['begin'] ?? $staticBegin->subDays(7);
$end = $_GET['end'] ?? $staticEnd;

// $begin = $_GET['begin'] ?? '2019-03-01 00:00:00';
// $end = $_GET['end'] ?? '2019-03-01 04:00:00';
// $end = $_GET['end'] ?? Carbon::now();

// printf("Now: %s", Carbon::now());

var_dump($db->getWeather($begin, $end));
// $api->json($db->getWeather());
//echo "hit";
