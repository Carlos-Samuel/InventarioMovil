<?php
    require_once '../controladores/Connection.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query("select * from Usuarios");

        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['id'] = "<p>" . $columna['idUsuarios'] . "</p>";
            $row['nombres'] = "<p>" . $columna['Cedula'] . "</p>";
            $row['apellidos'] = "<p>" . $columna['Nombres'] . "</p>";
            $row['cedula'] = "<p>" . $columna['Apellidos'] . "</p>";
            $row['email'] = "<p>" . $columna['Correo'] . "</p>";

            $boton = "<a href='../controladores/receptor_crear_usuarios.php?id=" . $columna['idUsuarios'] . "' class='btn btn-primary'>Ver detalles</a>";
            $row['editar'] = $boton;

            $boton = "<a href='../controladores/receptor_crear_usuarios.php?id=" . $columna['idUsuarios'] . "' class='btn btn-warning'>Cambiar</a>";
            $row['password'] = $boton;

            $response[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode(array("data" => $response));

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>
