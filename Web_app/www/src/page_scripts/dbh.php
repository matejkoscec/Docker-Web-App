<?php

$conn = new mysqli('db', 'root', getenv('MYSQL_ROOT_PASSWORD'), 'information_schema');

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}

$conn->query("CREATE DATABASE IF NOT EXISTS auth_data;");

$conn = new mysqli('db', 'root', getenv('MYSQL_ROOT_PASSWORD'), 'auth_data');

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}

$conn->query("CREATE TABLE IF NOT EXISTS accounts ( id int PRIMARY KEY AUTO_INCREMENT NOT NULL, user varchar(20) NOT NULL, userPassword LONGTEXT NOT NULL, entry varchar(10) NOT NULL );");

$conn->query("CREATE TABLE IF NOT EXISTS time_set ( id int PRIMARY KEY AUTO_INCREMENT NOT NULL, option_name varchar(50) NOT NULL, time_string TINYTEXT NOT NULL, ring_enable TINYTEXT NOT NULL);");

$conn->query("CREATE TABLE IF NOT EXISTS eeprom_mirror ( id int PRIMARY KEY AUTO_INCREMENT NOT NULL, option_name varchar(50) NOT NULL, time_string TINYTEXT NOT NULL, ring_enable TINYTEXT NOT NULL);");

$conn->query("CREATE TABLE IF NOT EXISTS active_setting ( id int NOT NULL, option_name varchar(50) NOT NULL, time_string TINYTEXT NOT NULL, ring_enable TINYTEXT NOT NULL);");

$conn->query("CREATE TABLE IF NOT EXISTS settings_by_date ( date_active DATE PRIMARY KEY NOT NULL, option_name varchar(50) NOT NULL, time_string TINYTEXT NOT NULL, ring_enable TINYTEXT NOT NULL);");

$conn->query("CREATE TABLE IF NOT EXISTS arduino_command ( eeprom_action varchar(1) PRIMARY KEY NOT NULL, option_name varchar(50) NOT NULL, time_string TINYTEXT NOT NULL, ring_enable TINYTEXT NOT NULL);");

