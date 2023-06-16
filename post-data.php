<?php
/*
  Rui Santos
  Complete project details at https://RandomNerdTutorials.com
  
  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.
  
  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
*/

$servername = "localhost";

// REPLACE with your Database name
$dbname = "id20142550_dbloteria";
// REPLACE with Database user
$username = "id20142550_rafflegenerator";
// REPLACE with Database user password
$password = "Yp]bTE[N0e0IqUmQ";

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

$api_key = $value1 = $value2 = $value3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $code = test_input($_POST["code"]);
        $date = test_input($_POST["date"]);
        $hour = test_input($_POST["hour"]);
        $speed = test_input($_POST["speed"]);
        $numsat = test_input($_POST["numsat"]);
        $height = test_input($_POST["height"]);
        $latitude = test_input($_POST["latitude"]);
        $longitude = test_input($_POST["longitude"]);
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
   

       // $sql = "INSERT INTO `sensor`(`code`, `date`, `hour`, `speed`, `numsat`, `height`, `latitude`, `longitude`) 
        //VALUES ('" . $code . "', '" . $date . "', '" . $hour . "', '" . $speed . "', '" . $numsat . "', '" . $height . "', '" . $latitude . "', '" . $longitude . "' )";
        $sql = "INSERT INTO `encsensor`(`code`, `date`, `hour`, `speed`, `numsat`, `height`, `latitude`, `longitude`) 
        VALUES ('" . $code . "', '" . $date . "', '" . $hour . "', '" . $speed . "', '" . $numsat . "', '" . $height . "', '" . $latitude . "', '" . $longitude . "' )";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}