<?php
// Habilitar el informe de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar el buffer de salida
ob_start();

// Configuración de la base de datos usando variables de entorno
$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');
$port = getenv('DB_PORT');
$baseUrl = getenv('BASE_URL'); // Obtener la variable BASE_URL
$baseUrl2 = getenv('BASE_URL2'); // Obtener la variable BASE_URL2
$baseUrl3 = getenv('BASE_URL3'); // Obtener la variable BASE_URL3

// Manejo de las solicitudes GET
if (isset($_GET['get'])) {
    $requestedUrl = $_GET['get'];
    $response = [];

    // Retornar la variable solicitada
    switch ($requestedUrl) {
        case 'BASE_URL':
            $response['baseUrl'] = $baseUrl; // Solo devuelve BASE_URL
            break;
        case 'BASE_URL2':
            $response['baseUrl2'] = $baseUrl2; // Solo devuelve BASE_URL2
            break;
        case 'BASE_URL3':
            $response['baseUrl3'] = $baseUrl3; // Solo devuelve BASE_URL3
            break;
        default:
            $response['error'] = 'No se encontró la variable solicitada.'; // Manejo de errores
            break;
    }

    // Limpiar el buffer de salida para evitar errores de encabezado en producción
    ob_clean();

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Terminar el script aquí
}

// Limpiar el buffer de salida para evitar errores de encabezado en producción
ob_clean();

// Crear conexión a la base de datos
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $conn->connect_error]));
}

// Obtener los datos enviados mediante POST
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$mensaje = $_POST['mensaje'] ?? '';

// Preparar la consulta SQL
$sql = "INSERT INTO reservas (nombre, correo, mensaje) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

$response = [];
if ($stmt) {
    // Vincular parámetros
    $stmt->bind_param("sss", $nombre, $correo, $mensaje);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Registro completado';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error en la preparación de la declaración: ' . $conn->error;
}

// Cerrar la conexión
$conn->close();

// Limpiar el buffer y enviar la respuesta JSON
ob_end_clean();
header('Content-Type: application/json');
echo json_encode($response);
?>
