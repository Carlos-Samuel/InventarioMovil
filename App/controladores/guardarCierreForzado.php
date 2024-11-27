<?php

    require_once 'Connection.php';
    include 'funciones.php';

    session_start();

    try {
        
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['idFactura']) && isset($data['idEstado'])) {

            $idFactura = $data['idFactura'];
            $idEstado = $data['idEstado'];

            $idFactura = $con->real_escape_string($idFactura);
            $idEstado = $con->real_escape_string($idEstado);

            date_default_timezone_set('America/Bogota');
            $horaLocal = date('Y-m-d H:i:s');
            $idEncargado = $_SESSION["idUsuarios"];
            $estadoForzado = 2 + $idEstado;

            $mensajes = [
                0 => "Factura Forzada Cerrada",
                1 => "Factura Forzada Enviada a Alistamiento"
            ];
            
            $mensajeLog = $mensajes[$idEstado];

            $sql = "UPDATE Facturas SET Forzado = $estadoForzado, FinEntrega = '$horaLocal', idEntregador = $idEncargado WHERE vtaid = $idFactura";   

            $resultado = $con->query($sql);

            if ($resultado) {
                bitacoraLog($mensajeLog, $idFactura);

                if($idEstado == 1){
                    $quer = $con->query("SELECT * FROM Facturas WHERE vtaid = $idFactura;");
    
                    if ($quer->num_rows > 0) {
                        $row = $quer->fetch_assoc();
    
                        utf8_encode_array($row);
    
                        $vtaid = $row['vtaid'];
                        $numDoc = $row['VtaNum'];
                        $prefijo = $row['PrfId'];
                        $fecha = $row['vtafec'];
                        $hora = $row['vtahor'];
                        $terId = $row['TerId'];
                        $nombre = $row['TerNom'];
                        $direccion = $row['TerDir'];
                        $telefono = $row['TerTel'];
                        $razon = $row['TerRaz'];
                        $vendedorId = $row['VenId'];
                        $vendedor = $row['VenNom'];
                        $ciudadId = $row['CiuId'];
                        $ciudad = $row['CiuNom'];
                        $observaciones = $row['facObservaciones'];
                        $prfCod = $row['PrfCod'] . "-RP";
    
                        $sql = "INSERT INTO Facturas (VtaNum, PrfId, vtafec, vtahor, TerId, TerNom, TerDir, TerTel, TerRaz, VenId, VenNom, CiuId, CiuNom, facObservaciones, facEstado, MomentoCarga, PrfCod) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), ?)";
                        
                        // Preparar la consulta
                        if ($stmt = $con->prepare($sql)) {
                            // Vincular parámetros y tipos de datos
                            $stmt->bind_param(
                                "issssssssssssss", // Tipos de datos
                                $numDoc,           // Entero
                                $prefijo,          // Entero
                                $fecha,            // Cadena
                                $hora,             // Cadena
                                $terId,            // Entero
                                $nombre,           // Cadena
                                $direccion,        // Cadena
                                $telefono,         // Cadena
                                $razon,            // Cadena
                                $vendedorId,       // Entero
                                $vendedor,         // Cadena
                                $ciudadId,         // Entero
                                $ciudad,           // Cadena
                                $observaciones,    // Cadena
                                $prfCod            // Cadena
                            );
                        
                            if ($stmt->execute()) {
    
                                $nuevoId = $con->insert_id;
    
                                $quer2 = $con->query(
                                    "SELECT * 
                                    FROM Productos 
                                    WHERE vtaid =  $idFactura 
                                    AND VtaCant != AlisCant
                                    ORDER BY
                                    CASE
                                      WHEN ProUbica = 'DATO NO DISPONIBLE' THEN 1
                                      ELSE 0
                                    END,
                                    ProUbica ASC;");
                    
                                $datosProductos = array();
                        
                                while ($columna = $quer2->fetch_assoc()) {
                                    $row['ProId'] = $columna['ProId'];
                                    $row['ProCod'] = $columna['ProCod'];
                                    $row['ProCodBar'] = $columna['ProCodBar'];
                                    $row['descripcion'] = $columna['ProNom'];
                                    $row['ubicacion'] = $columna['ProUbica'];
                                    $row['presentacion'] = $columna['ProPresentacion'];
                                    $row['cantidad'] = $columna['VtaCant'];
                                    $row['alistado'] = $columna['AlisCant'];
                                    $row['diferencia'] = $columna['VtaCant'] - $columna['AlisCant'];
                    
                                    utf8_encode_array($row);
                        
                                    $datosProductos[] = $row;
                                }
    
                                foreach ($datosProductos as $producto) {
                                    $consultaP = "INSERT INTO Productos
                                    (VtaId, ProId, ProCod, ProNom, ProUbica, ProPresentacion, ProCodBar, VtaCant) 
                                    VALUES 
                                    ('$nuevoId', ".$producto['ProId'].", '".$producto['ProCod']."', '".$producto['descripcion']."', '".$producto['ubicacion']."', '".$producto['presentacion']."', '".$producto['ProCodBar']."', '".$producto['diferencia']."');";
                                    

                                    if (!$consultaProductos= $con->query($consultaP)){
                                        $response = array(
                                            "message" => "Error en la consulta: " . $con->error,
                                            "status" => 2
                                        );
                                        echo json_encode($response);
                                        break;
                                    }
    
                                }
                                bitacoraLog($mensajeLog, $idFactura);

                                $response = array(
                                    "message" => "Factura actualizada correctamente",
                                    "status" => 1
                                );
                            } else {
                                $response = array(
                                    "error" => "Error al insertar nueva  factura: ",
                                    "status" => 2
                                );                        
                            }
                        
                            $stmt->close();
                        }
    
                    } 
                
                }else{
                    $response = array(
                        "message" => "Factura cerrada correctamente",
                        "status" => 1
                    );
                }
            } else {
                $response = array(
                    "error" => "Error " . $sql . " al actualizar la facturacion: " . $con->error,
                    "status" => 2
                );
            }

            header("Content-Type: application/json");
            echo json_encode($response);
        } else {
            $response = array(
                "error" => "ID no proporcionado",
                "status" => 2
            );

            header("Content-Type: application/json");
            echo json_encode($response);
        }
    } catch (Exception $e) {
        $response = array(
            "error" => "Error " . $sql . " en la conexión: " . $e->getMessage(),
            "status" => 2
        );

        header("Content-Type: application/json");
        echo json_encode($response);
    }
?>
