<?php
$servername = "localhost";
$dbname = "id20142550_dbloteria";
$username = "id20142550_rafflegenerator";
$password = "Yp]bTE[N0e0IqUmQ";
$api_key_value = "tPmAT5Ab3j7F9";

// Verificar la clave API
$api_key = "";
if (isset($_GET['api_key'])) {
    $api_key = $_GET['api_key'];
}

if ($api_key === $api_key_value) {
    try {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //$sql = "SELECT * FROM sensor ORDER BY reading_time DESC LIMIT 40";
        $sql = "SELECT * FROM encsensor ORDER BY reading_time DESC LIMIT 40";

        $result = $conn->query($sql);
        $sensor_data = array();
        while ($data = $result->fetch_assoc()) {
            $sensor_data[] = $data;
        }

        $latitude = json_encode(array_reverse(array_column($sensor_data, 'latitude')), JSON_NUMERIC_CHECK);
$longitude = json_encode(array_reverse(array_column($sensor_data, 'longitude')), JSON_NUMERIC_CHECK);
$speed = json_encode(array_reverse(array_column($sensor_data, 'speed')), JSON_NUMERIC_CHECK);
$code = json_encode(array_reverse(array_column($sensor_data, 'code')), JSON_NUMERIC_CHECK);
$date = json_encode(array_reverse(array_column($sensor_data, 'date')), JSON_NUMERIC_CHECK);
$hour = json_encode(array_reverse(array_column($sensor_data, 'hour')), JSON_NUMERIC_CHECK);
$numsat = json_encode(array_reverse(array_column($sensor_data, 'numsat')), JSON_NUMERIC_CHECK);
$height = json_encode(array_reverse(array_column($sensor_data, 'height')), JSON_NUMERIC_CHECK);
$reading_time = json_encode(array_reverse(array_column($sensor_data, 'reading_time')), JSON_NUMERIC_CHECK);
    $response = array();
    if (!empty($sensor_data)) {
        $response['success'] = true;
        $response['latitude'] = $latitude;
        $response['longitude'] = $longitude;
        $response['speed'] = $speed;
        $response['code'] = $code;
        $response['date'] = $date;
        $response['hour'] = $hour;
        $response['numsat'] = $numsat;
        $response['height'] = $height;
        $response['reading_time'] = $reading_time;
    } else {
        $response['success'] = false;
        $response['message'] = 'No se encontraron datos en la base de datos.';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    $response = array(
        'success' => false,
        'message' => 'Error al conectar a la base de datos: ' . $e->getMessage()
    );

    header('Content-Type: application/json');
    echo json_encode($response);
}
} else {
$response = array(
'success' => false,
'message' => 'Clave API no válida.'
);
header('Content-Type: application/json');
echo json_encode($response);
}
?>