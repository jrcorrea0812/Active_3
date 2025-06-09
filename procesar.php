<?php

if(isset($_POST['enviar'])) {
   
    $nombre    = htmlspecialchars($_POST['nombre']);
    $ciudad    = htmlspecialchars($_POST['ciudad']);
    $direccion = htmlspecialchars($_POST['direccion']);
    $celular   = htmlspecialchars($_POST['celular']);

      $servidor = "localhost";
    $usuario  = "tu_usuario";
    $clave    = "tu_contraseña";
    $base     = "tu_base_de_datos";

    
    $conn = new mysqli($servidor, $usuario, $clave, $base);
    if($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $sql = "INSERT INTO cotizaciones (nombre, ciudad, direccion, celular)
            VALUES ('$nombre', '$ciudad', '$direccion', '$celular')";

    if($conn->query($sql) === TRUE) {
       
        $ultimo_id = $conn->insert_id;
    } else {
        die("Error en la inserción: " . $conn->error);
    }

    $precios = [
        "producto1" => 10000,
        "producto2" => 20000
       
    ];

  
    $productosSeleccionados = [];
    if(isset($_POST['productos']) && is_array($_POST['productos'])){
        foreach($_POST['productos'] as $producto) {
          
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