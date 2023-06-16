
<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "id20142550_dbloteria";
// REPLACE with Database user
$username = "id20142550_rafflegenerator";
// REPLACE with Database user password
$password = "Yp]bTE[N0e0IqUmQ";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//$sql = "SELECT id, code, latitude, longitude,speed, reading_time FROM sensor order by reading_time desc limit 40";
$sql = "SELECT id, code, latitude, longitude,speed, reading_time FROM sensor order by reading_time desc limit 40";
//echo $sql;

$result = $conn->query($sql);

$sensor_data = array();
while ($data = $result->fetch_assoc()){
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'reading_time');
$value1 = json_encode(array_reverse(array_column($sensor_data, 'latitude')), JSON_NUMERIC_CHECK);
$value2 = json_encode(array_reverse(array_column($sensor_data, 'longitude')), JSON_NUMERIC_CHECK);
$value3 = json_encode(array_reverse(array_column($sensor_data, 'speed')), JSON_NUMERIC_CHECK);
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);



$result->free();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <style>
    body {
      min-width: 310px;
      max-width: 1280px;
      height: 500px;
      margin: 0 auto;
    }
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
 <body>
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
    var chartT = new Highcharts.Chart({
      chart: {
        renderTo: 'chart-latitud'
      },
      title: {
        text: 'Latitud'
      },
      series: [{
        showInLegend: false,
        data: value1
      }],
      plotOptions: {
        line: {
          animation: false,
          dataLabels: {
            enabled: true
          }
        },
        series: {
          color: '#059e8a'
        }
      },
      xAxis: {
        type: 'datetime',
        categories: reading_time
      },
      yAxis: {
        title: {
          text: 'Latitud'
        }
      },
      credits: {
        enabled: false
      }
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
</body>
</html>