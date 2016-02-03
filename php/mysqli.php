<?php
$config = parse_ini_file('./config.ini');

$mysqli = new mysqli($config['ip'], $config['username'], $config['password'], $config['dbname'], 3306);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>