<?php

require "Environment.php";
$env = new Environment();
require "Database.php";
$db = new Database($env);

echo $db->migrate();
