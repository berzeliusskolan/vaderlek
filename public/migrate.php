<?php

require "../app/Environment.php";
$env = new Environment();
require "../app/Database.php";
$db = new Database($env);

echo $db->migrate();
