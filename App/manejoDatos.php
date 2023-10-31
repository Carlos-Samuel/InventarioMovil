<?php
	session_start(); 	

	if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
		header("Location: index.php");
		exit();
	}

    $permiso = "Admin";

    if (!strpos($_SESSION['permisos'], $permiso)) {
		header("Location: dashboard.php");
		exit();
	}
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
        <style>
            .mensaje {
                position: relative;
                background-color: #e0e0e0;
                padding: 10px;
                border: 1px solid #888;
                text-align: center;
            }

            .cerrar {
                position: absolute;
                top: 5px;
                right: 5px;
                cursor: pointer;
            }

            #botonEmbalaje {
                width: 400px; 
                height: 50px; 
                font-size: 24px;
                margin-top: 10px;
            }

        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "ManejoDatos";
                include('partes/sidebar.php');
            ?>  

            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <h1>Importar datos</h1>
                    <div style="display: flex; justify-content: space-between;">
                        <div style="width: 45%;">
                            <label for="fecha"><strong>Fecha limite inferior</strong></label>
                            <input type="date" id="fecha" name="fecha">
                        </div>
                        <div style="width: 45%;">
                            <br>
                            <button id = "botonImportar" class="btn btn-primary primeButton" type="button">Importar</button>
                        </div>
                    </div>
                    <br>
                    <br>
                    <h1>Borrar datos</h1>
                    <div style="display: flex; justify-content: space-between;">
                        <div style="width: 45%;">
                            <br>
                            <button id = "botonBorrador" class="btn btn-warning primeButton" type="button">Borrar</button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>  
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- Incluye la biblioteca DataTables -->
        <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

        <script>
            $(document).ready( function () {

                $('#botonBorrador').on('click', function() {

                    // Configuración de la solicitud
                    var requestOptions = {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    };

                    // Realizar la solicitud utilizando fetch
                    fetch('controladores/borrador.php', requestOptions)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta de la red');
                            }
                            return response.json();
                        })
                        .then(data => {
                            alert(data.mensaje); // Accede a la propiedad "mensaje" del objeto JSON
                        })
                        .catch(error => {
                            alert('Error al borrar');
                        });
                    
                });

                $('#botonImportar').on('click', function() {

                    var dataToSend = {
                        fecha: $('#fecha').val(),
                    };

                    // Configuración de la solicitud
                    var requestOptions = {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataToSend)
                    };

                    // Realizar la solicitud utilizando fetch
                    fetch('controladores/importador.php', requestOptions)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta de la red');
                            }
                            return response.json();
                        })
                        .then(data => {
                            alert(data.mensaje); // Accede a la propiedad "mensaje" del objeto JSON
                        })
                        .catch(error => {
                            alert('Error al importar');
                        });

                });

            });
        </script>
    </body>
</html>