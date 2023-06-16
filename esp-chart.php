<?php session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header('Location: login.php');
    exit();
}
// Procesar el logout
if (isset($_POST['logout'])) {
  // Limpiar todas las variables de sesión
  session_unset();

  // Destruir la sesión
  session_destroy();

  // Redirigir al usuario a la página de inicio de sesión
  header('Location: login.php');
  exit();
}
/*
$servername = "localhost";

// REPLACE with your Database name
$dbname = "id20142550_dbloteria";
// REPLACE with Database user
$username = "id20142550_rafflegenerator";
// REPLACE with Database user password
$password = "Yp]bTE[N0e0IqUmQ";*/

$hoservernamest = 'localhost';
$username = 'root';
$password = '';
$dbname = 'esp_data';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//$sql = "SELECT id, code, latitude, longitude,speed, reading_time FROM sensor order by reading_time desc limit 40";
//$sql = "SELECT id, code, latitude, longitude,speed, reading_time FROM encsensor order by reading_time desc limit 40";
$sql = "SELECT id, code, latitude, longitude,speed, reading_time FROM sensor order by reading_time desc limit 40";
//echo $sql;

$result = $conn->query($sql);

$sensor_data = array();
while ($data = $result->fetch_assoc()){
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'reading_time');
$value1 = array_reverse(array_column($sensor_data, 'latitude'));
$value2 = array_reverse(array_column($sensor_data, 'longitude'));
$value3 = array_reverse(array_column($sensor_data, 'speed'));
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);

function desencriptarMensaje($mensajeEncriptado, $clave) {
  $mensajeDesencriptado = "";
  $longitudMensaje = strlen($mensajeEncriptado);
  for ($i = 0; $i < $longitudMensaje; $i++) {
    $caracter = $mensajeEncriptado[$i];
    $caracterDesencriptado = chr(ord($caracter) ^ $clave); // Operación XOR con la clave
    $mensajeDesencriptado .= $caracterDesencriptado;
  }
  return $mensajeDesencriptado;
}

$clave = 3;
$value1_desencriptado = [];
foreach ($value1 as $valor) {
    $valor = strval($valor); // Convertir a cadena de texto
    $valor_desencriptado = desencriptarMensaje($valor, $clave);
    $value1_desencriptado[] = $valor_desencriptado;
}

$value1 = json_encode($value1_desencriptado, JSON_NUMERIC_CHECK);



$value2_desencriptado = [];
foreach ($value2 as $valor) {
    $valor = strval($valor); // Convertir a cadena de texto
    $valor_desencriptado = desencriptarMensaje($valor, $clave);
    $value2_desencriptado[] = $valor_desencriptado;
}

$value2 = json_encode($value2_desencriptado, JSON_NUMERIC_CHECK);



$value3_desencriptado = [];
foreach ($value3 as $valor) {
    $valor = strval($valor); // Convertir a cadena de texto
    $valor_desencriptado = desencriptarMensaje($valor, $clave);
    $value3_desencriptado[] = $valor_desencriptado;
}

$value3 = json_encode($value3_desencriptado, JSON_NUMERIC_CHECK);

$result->free();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GPS Station</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.highcharts.com/highcharts.js"></script>
  <style>
 
    h2 {
      font-family: Arial;
      font-size: 2.5rem;
      text-align: center;
    }
  </style>
  
  <!-- Incluye los estilos de Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <!-- Define el estilo del mapa -->
  <style>
    #map {
      height: 100vh;
    }
  </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                
                <div class="sidebar-brand-text mx-3">Gps Station </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="datos.php" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Datos</span>
                </a>
                
            </li>
          

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="esp-chart.php" >
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Mapa</span>
                </a>
               
            </li>  
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                  

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo  htmlspecialchars($_SESSION['nombre_usuario']);?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->


               <!-- Modal de confirmación de logout -->
              <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logoutModalLabel">Confirmar cerrar sesión</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">¿Estás seguro que quieres cerrar sesión?</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                           <form action="" method="post">
                                <button class="btn btn-primary" name="logout" type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">

               
                <h2>ESP GPS Station</h2>
  <div id="chart-latitud" class="container"></div>
  <div id="chart-longitud" class="container"></div>
  <div id="chart-velocidad" class="container"></div>

  <div id="map"></div>

  <script>
    var value1 = <?php echo $value1; ?>;
    var value2 = <?php echo $value2; ?>;
    var value3 = <?php echo $value3; ?>;
    var reading_time = <?php echo $reading_time; ?>;

    // Gráfico de latitud
    Highcharts.chart('chart-latitud', {
      chart: {
        type: 'line'
      },
      title: {
        text: 'Latitud'
      },
      xAxis: {
        categories: reading_time
      },
      yAxis: {
        title: {
          text: 'Latitud'
        }
      },
      series: [{
        name: 'Latitud',
        data: value1
      }]
    });


    // Gráfico de longitud
    Highcharts.chart('chart-longitud', {
      chart: {
        type: 'line'
      },
      title: {
        text: 'Longitud'
      },
      xAxis: {
        categories: reading_time
      },
      yAxis: {
        title: {
          text: 'Longitud'
        }
      },
      series: [{
        name: 'Longitud',
        data: value2
      }]
    });

    // Gráfico de velocidad
    Highcharts.chart('chart-velocidad', {
      chart: {
        type: 'line'
      },
      title: {
        text: 'Velocidad'
      },
      xAxis: {
        categories: reading_time
      },
      yAxis: {
        title: {
          text: 'Velocidad'
        }
      },
      series: [{
        name: 'Velocidad',
        data: value3
      }]
    });

</script>

  <div id="map"></div>

  <!-- Incluye las bibliotecas de Leaflet y Leaflet.markercluster -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>

  <script>
    // Crea un mapa en el elemento con el ID "map"
    var map = L.map('map').setView([0, 0], 2);

    // Añade una capa de teselas de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
      maxZoom: 18
    }).addTo(map);

    // Capa para las líneas de recorrido
    var polylineLayer = L.layerGroup().addTo(map);

    // Variables para almacenar el último punto
    var lastPoint = null;
    var lastPolyline = null;

    // Función para cargar y mostrar los datos de la base de datos como GeoJSON
    function cargarDatosBaseDeDatos() {
      fetch('cargar_datos.php?api_key=tPmAT5Ab3j7F9')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            var latitude = JSON.parse(data.latitude);
            var longitude = JSON.parse(data.longitude);
            var speed = JSON.parse(data.speed);
            var code = JSON.parse(data.code);
            var date = JSON.parse(data.date);
            var hour = JSON.parse(data.hour);
            var numsat = JSON.parse(data.numsat);
            var height = JSON.parse(data.height);

            // Convierte los datos a GeoJSON
            var geojson = {
              type: 'FeatureCollection',
              features: []
            };
            var lineCoordinates = []; // Array para almacenar las coordenadas de los puntos

                   for (var i = 0; i < latitude.length; i++) {
              var lat = desencriptarMensaje(latitude[i],3);
              var lon = desencriptarMensaje(longitude[i],3);

              if (!isNaN(lat) && !isNaN(lon)) {
                var feature = {
                  type: 'Feature',
                  geometry: {
                    type: 'Point',
                    coordinates: [lon, lat]
                  },
                  properties: {
                    // Reemplaza con las propiedades correctas
                    Longitud: lon,
                Latitud: lat,
                Code:  code[i],
                Velocidad:  desencriptarMensaje(speed[i],3),
                Fecha:  desencriptarMensaje(date[i],3),
                Hora: desencriptarMensaje(hour[i],3),
                Satelites:  desencriptarMensaje(numsat[i],3),
                Altura:  desencriptarMensaje(height[i],3),
                  }
                };

                geojson.features.push(feature);
                lineCoordinates.push([lat, lon]); // Agrega las coordenadas al array
              }
            }

            // Limpia las capas existentes antes de agregar el nuevo GeoJSON
            map.eachLayer(layer => {
              if (layer instanceof L.GeoJSON) {
                map.removeLayer(layer);
              }
            });
            polylineLayer.clearLayers(); // Limpia las capas de líneas de recorrido

            var polyline = L.polyline(lineCoordinates, { color: 'red' }).addTo(polylineLayer); // Crea la polilínea y la agrega a la capa de líneas

            if (lastPoint !== null) {
              map.removeLayer(lastPoint); // Elimina el marcador del último punto
              map.removeLayer(lastPolyline); // Elimina la línea del último punto al actual
            }

            lastPoint = L.marker(lineCoordinates[lineCoordinates.length - 1]).addTo(map); // Crea un marcador en el último punto
            lastPolyline = L.polyline([lineCoordinates[lineCoordinates.length - 1], lineCoordinates[lineCoordinates.length - 2]], { color: 'blue' }).addTo(map); // Crea una línea del último punto al anterior

            L.geoJSON(geojson, {
              onEachFeature: function (feature, layer) {
                var tooltipContent = "<strong>ID:</strong> " + feature.properties.Code + "<br>" +
                                      "<strong>Latitud:</strong> " + feature.properties.Latitud + "<br>" +
                                      "<strong>Longitud:</strong> " + feature.properties.Longitud + "<br>" +
                                      "<strong>Fecha:</strong> " + feature.properties.Fecha + "<br>" +
                                      "<strong>Hora (GMT):</strong> " + feature.properties.Hora + "<br>" +
                                      "<strong>Satélites:</strong> " + feature.properties.Satelites + "<br>" +
                                      "<strong>Altura (FOSL):</strong> " + feature.properties.Altura + "<br>" +
                                      "<strong>Velocidad (Km/h):</strong> " + feature.properties.Velocidad;

                layer.bindTooltip(tooltipContent);
              }
            }).addTo(map);
          } else {
            console.error('No se encontraron datos en la base de datos.');
          }
        })
        .catch(error => {
          console.error('Error al cargar los datos de la base de datos:', error);
        });
    }

    // Llama a la función para cargar y mostrar los datos de la base de datos
    cargarDatosBaseDeDatos();

    setInterval(cargarDatosBaseDeDatos, 5000);

    // Función para hacer zoom al último punto
    function zoomToLastPoint() {
      if (lastPoint !== null) {
        map.setView(lastPoint.getLatLng(), 18); // Zoom al último punto con nivel de zoom 18
      }
    }

    // Botón para hacer zoom al último punto
    var zoomButton = L.control({ position: 'topright' });

    zoomButton.onAdd = function (map) {
      var div = L.DomUtil.create('div', 'zoom-button');
      div.innerHTML = '<button onclick="zoomToLastPoint()">Zoom al Último Punto</button>';
      return div;
    };

    zoomButton.addTo(map);
    function desencriptarMensaje(mensajeEncriptado, clave) {
  let mensajeDesencriptado = "";
  const longitudMensaje = mensajeEncriptado.length;
  for (let i = 0; i < longitudMensaje; i++) {
    const caracter = mensajeEncriptado.charAt(i);
    const caracterDesencriptado = String.fromCharCode(caracter.charCodeAt(0) ^ clave); // Operación XOR con la clave
    mensajeDesencriptado += caracterDesencriptado;
  }
  return mensajeDesencriptado;
}
  </script>
               
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <!-- <span>Copyright &copy; Your Website 2021</span> -->
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>