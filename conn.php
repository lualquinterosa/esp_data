<?php
// $db_host = "localhost";
// $db_user = "id20142550_rafflegenerator";
// $db_pass = "Yp]bTE[N0e0IqUmQ";
// $db_name = "id20142550_dbloteria";

/*
$db_host = "localhost";

// REPLACE with your Database name
$db_name = "id20142550_dbloteria";
// REPLACE with Database user
$db_user = "id20142550_rafflegenerator";
// REPLACE with Database user password
$db_pass = "Yp]bTE[N0e0IqUmQ";*/

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'esp_data';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if(mysqli_connect_errno()){
	echo 'Error, no se pudo conectar a la base de datos: '.mysqli_connect_error();
}   
?>