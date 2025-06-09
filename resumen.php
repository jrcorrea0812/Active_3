<?php
session_start();

// Recuperar el ID de la cotización y datos de productos de la sesión.
$cotizacion_id = $_SESSION['cotizacion_id'];
$productos     = $_SESSION['productos'];

// Conectar a la base de datos para recuperar el resto de la información.
$servidor = "localhost";
$usuario  = "tu_usuario";
$clave    = "tu_contraseña";
$base     = "tu_base_de_datos";

$conn = new mysqli($servidor, $usuario, $clave, $base);
if($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM cotizaciones WHERE id = $cotizacion_id";
$result = $conn->query($sql);
$cotizacion = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resumen de Cotización</title>
  <style>
    table { border-collapse: collapse; width: 80%; margin: 20px auto; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    th { background-color: #f0f0f0; }
  </style>
</head>
<body>
  <h1>Resumen de Cotización</h1>
  
  <h2>Información Personal</h2>
  <table>
    <tr>
      <th>Nombres y Apellidos</th>
      <td><?php echo $cotizacion['nombre']; ?></td>
    </tr>
    <tr>
      <th>Ciudad</th>
      <td><?php echo $cotizacion['ciudad']; ?></td>
    </tr>
    <tr>
      <th>Dirección</th>
      <td><?php echo $cotizacion['direccion']; ?></td>
    </tr>
    <tr>
      <th>Celular</th>
      <td><?php echo $cotizacion['celular']; ?></td>
    </tr>
  </table>

  <h2>Detalles de Productos</h2>
  <table>
    <tr>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Precio Unitario</th>
      <th>Total</th>
    </tr>
    <?php foreach($productos as $item): ?>
    <tr>
      <td><?php echo $item['producto']; ?></td>
      <td><?php echo $item['cantidad']; ?></td>
      <td><?php echo "$ " . number_format($item['precioUnitario'],0,',','.'); ?></td>
      <td><?php echo "$ " . number_format($item['total'],0,',','.'); ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <!-- Se puede agregar un botón para volver a la página inicial -->
  <button onclick="window.location.href='index.html'">Volver</button>
</body>
</html>