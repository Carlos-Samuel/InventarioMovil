<?php
    require_once 'Connection.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query("select * from Usuarios where idUsuarios = " . $_REQUEST['id'] . ";");

        if ($quer->num_rows > 0) {
            // Existen resultados, obtén la fila
            $row = $quer->fetch_assoc();
    
            // Accede a los valores de la fila
            $idUsuarios = $row['idUsuarios'];
            $Cedula = $row['Cedula'];
            $Nombres = $row['Nombres'];
            $Apellidos = $row['Apellidos'];
            $Correo = $row['Correo'];

            $valor_codificado = urlencode($_REQUEST['id']);
        } else {
            $valor_codificado = urlencode(-1);
        }
        
        $pagina = "../detalle_usuario.php";
        $pagina_destino = $pagina . "?id=" . $valor_codificado;
        header("Location: $pagina_destino"); // Redireccionamos a la página destino
        exit;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();



?>