<?php
    require_once '../controladores/Connection.php';

    try {

        $id_recibido = urldecode($_GET['idUsuario']);

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query("SELECT 
                                P.*,
                                CASE
                                    WHEN UHP.idUsuarios IS NOT NULL THEN true
                                    ELSE false
                                END AS TienePermiso
                            FROM 
                                Permisos P
                            LEFT JOIN 
                                Usuarios_tienen_permisos UHP ON P.idPermisos = UHP.idPermisos
                                                        AND UHP.idUsuarios = " . $id_recibido ."
                            ORDER BY 
                                P.idPermisos;");

        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['id'] = "<p>" . $columna['idPermisos'] . "</p>";
            $row['nombres'] = "<p>" . $columna['Nombre'] . "</p>";
            $row['descripcion'] = "<p>" . $columna['Descripcion'] . "</p>";

            if(!$columna['TienePermiso']){
                $row['estado'] = "<button class='btn btn-success btnCambiar' type='button' data-action='Activar' data-id=".$columna['idPermisos'].">Activar</button>";
            }else{
                $row['estado'] = "<button class='btn btn-danger btnCambiar' type='button' data-action='Desactivar' data-id=".$columna['idPermisos'].">Desactivar</button>";;
            }

            $response[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode(array("data" => $response));

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>
