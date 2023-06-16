<?php
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=GpsStation_" . date('Y_m_d_H_i_s') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header('Location: login.php');
    exit();
}
include "conn.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include("head.php");
$output = "";

// Fetch data from database
$query = "SELECT * FROM sensor";
$result = mysqli_query($conn, $query);


$output .="
			<table>
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Hora (GMT)</th>
						<th>Altura (FOSL)</th>
						<th>Latitud </th>
						<th>Longitud</th>
						<th>No. Satelites</th>
						<th>Velocidad</th>
						<th>Registro</th>
					</tr>
				<tbody>
		";
// Loop through the data and write rows to the Excel file
while($fetch = mysqli_fetch_array($result)){
			
    $output .= "
                <tr>
                        
                    <td>".$fetch['date']."</td>
                    <td>".$fetch['hour']."</td>
                    <td>".$fetch['height']."</td>
                    <td>".$fetch['latitude']."</td>
                    <td>".$fetch['longitude']."</td>
                    <td>".$fetch['numsat']."</td>
                    <td>".$fetch['speed']."</td>
                    <td>".$fetch['reading_time']."</td>
                </tr>
    ";
       
    $output .="
            </tbody>
            
        </table>
    ";
    
    echo $output;
}
// Close the database connection
mysqli_close($conn);

exit();
?>
