<?php
require 'vendor/autoload.php';

use Carbon\Carbon;

require "Environment.php";
$env = new Environment();

require "Database.php";
$db = new Database($env);

require "Api.php";
$api = new Api();


// find out now
$staticBegin = new Carbon('2019-03-08 12:00:00');
$staticEnd = new Carbon('2019-03-08 12:00:00');
$begin = $_GET['begin'] ?? $staticDate->subDays(7);

//->subDays(7);
var_dump($begin);
//$begin = $_GET['begin'] ?? Carbon::now()->subDays(7);
//$begin = $_GET['begin'] ?? '2019-03-01 00:00:00';
//$end = $_GET['end'] ?? '2019-03-01 04:00:00';
$end = $_GET['end'] ?? Carbon::now();

printf("Now: %s", Carbon::now());

var_dump($db->getWeather($begin, $end));
//$api->json($db->getWeather());
//echo "hit";


