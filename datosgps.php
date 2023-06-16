<?php
include "conn.php";
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 => 'date',
    1 => 'hour',
    2 => 'height',
    3 => 'latitude',
    4 => 'longitude',
    5 => 'numsat',
    6 => 'speed',
   
);

// $user_id = $_SESSION['user_id'];
								
								/*$rs = mysqli_query($conn,"SELECT user_rol FROM user_ WHERE user_id='$user_id'");	
								if ($row = mysqli_fetch_row($rs)) {
									$user_rol = trim($row[0]);
									}
								if($user_rol==3){

									$resc = mysqli_query($conn,"SELECT escuelacodigo FROM usuario WHERE user_id='$user_id'");	
									$rowesc = mysqli_fetch_row($resc);
									$escuelacod = trim($rowesc[0]);
									$escuela= " AND escuela.escuelacodigo ='$escuelacod'";	
								
									
								}*/

// getting total number records without any search
//$sql = "SELECT *  FROM encsensor";
$sql = "SELECT *  FROM sensor";
$query=mysqli_query($conn, $sql) or die("datosgps.php: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "SELECT * FROM sensor";
	$sql.=" WHERE date LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR hour LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR height LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR latitude LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR longitude LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR numsat LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR speed LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
   
	$query=mysqli_query($conn, $sql) or die("datosgps.php: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query=mysqli_query($conn, $sql) or die("datosgps.php: get PO"); // again run query with limit
	
} else {	

	$sql = "SELECT * FROM sensor";
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=mysqli_query($conn, $sql) or die("datosgps.php: get PO");
	
}

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 	

	//$nestedData[] = $row["id"];    

	$fecha = $row["date"];
	$hora =  $row["hour"];
    $nestedData[] = $fecha;
    $nestedData[] = $hora;
    $nestedData[] = $row["height"];
    $nestedData[] = $row["latitude"];
    $nestedData[] = $row["longitude"];
    $nestedData[] = $row["numsat"];
    $nestedData[] = $row["speed"];
  
	$msjeliminar=' Está seguro que desea eliminar ubicación:  '.$row['latitude'].','.$row['longitude'];
	$href='datos.php?action=delete&id='.$row['id'];

	$data[] = $nestedData;
    
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);
echo json_encode($json_data);  // send data as json format
?>
