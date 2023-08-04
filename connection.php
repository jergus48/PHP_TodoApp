<?php

$dbhost="localhost:5000";
$dbuser="Jergus Snahnican";
$dbpass="2ie3-.9L9FwNX5j";
$dbname="phptry_db";

if (!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname)) {
    die("failed to connect database");
}

date_default_timezone_set('Europe/Bratislava');
// https://stackoverflow.com/questions/49178288/call-to-undefined-function-mysqli-connect-on-php-for-windows