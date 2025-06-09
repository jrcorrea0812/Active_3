<?php
// Verificar que el formulario se envió correctamente.
if(isset($_POST['enviar'])) {
    // Recoger y sanitizar la información proveniente del formulario.
    $nombre    = htmlspecialchars($_POST['nombre']);
    $ciudad    = htmlspecialchars($_POST['ciudad']);
    $direccion = htmlspecialchars($_POST['direccion']);
    $celular   = htmlspecialchars($_POST['celular']);

    // Datos de conexión a la base de datos.
    $servidor = "localhost";
    $usuario  = "tu_usuario";
    $clave    = "tu_contraseña";
    $base     = "tu_base_de_datos";

    // Crear la conexión.
    $conn = new mysqli($servidor, $usuario, $clave, $base);
    if($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Inserción de datos en la tabla "cotizaciones"
    $sql = "INSERT INTO cotizaciones (nombre, ciudad, direccion, celular)
            VALUES ('$nombre', '$ciudad', '$direccion', '$celular')";

    if($conn->query($sql) === TRUE) {
        // Se obtiene el ID de la inserción para consultas posteriores.
        $ultimo_id = $conn->insert_id;
    } else {
        die("Error en la inserción: " . $conn->error);
    }

    // Procesar los productos.
    // Ejemplo: Definir precios unitarios por producto.
    $precios = [
        "producto1" => 10000,
        "producto2" => 20000
        // Agregar más productos y sus respectivos precios unitarios
    ];

    // Preparar un arreglo para almacenar la información de productos seleccionados.
    $productosSeleccionados = [];
    if(isset($_POST['productos']) && is_array($_POST['productos'])){
        foreach($_POST['productos'] as $producto) {
            // Recuperamos la cantidad asociada con cada producto.
            $campoCantidad = "cantidad_" . $producto;
            $cantidad = isset($_POST[$campoCantidad]) ? (int)$_POST[$campoCantidad] : 0;
            if($cantidad > 0) {
                $precioUnitario = isset($precios[$producto]) ? $precios[$producto] : 0;
                $total = $precioUnitario * $cantidad;
                $productosSeleccionados[] = [
                    "producto"      => $producto,
                    "cantidad"      => $cantidad,
                    "precioUnitario"=> $precioUnitario,
                    "total"         => $total
                ];
            }
        }
    }
    
    // Aquí puedes insertar los detalles de los productos en otra tabla relacionada (por ejemplo, "detalles_cotizacion")
    // o almacenarlos en una variable de sesión para luego mostrarlos.
    
    // Una vez guardados los datos, redirige a una página de resumen.
    // Se puede pasar el ID del registro insertado como parámetro si se va a consultar ese registro.
    session_start();
    $_SESSION['productos'] = $productosSeleccionados;
    $_SESSION['cotizacion_id'] = $ultimo_id;
    header("Location: resumen.php");
    exit();
} else {
    header("Location: index.html");
    exit();
}
?>