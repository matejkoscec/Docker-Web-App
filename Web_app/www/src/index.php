<?php
$probno = $_POST["value"];

echo $probno;
echo 'aye';

$mysqli = new mysqli('db', getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), 'rtc_auth_data');

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "\n";
