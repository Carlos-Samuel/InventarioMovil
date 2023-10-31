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

    require_once 'controladores/Connection.php';

    // isset($_GET['id'])


?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">

        <style>
            /* Estilos para el botón */
            .btnActivar {
                background-color: green;
                color: white;
                border: none;
                border-radius: 10px;
                padding: 5px 10px;
                cursor: pointer;
            }

            .btnDesactivar {
                background-color: red;
                color: white;
                border: none;
                border-radius: 10px;
                padding: 5px 10px;
                cursor: pointer;
            }

            #tablaPermisos {
                width: 100%; /* Ancho de la tabla al 100% */
                border-collapse: collapse; /* Colapso de bordes para un diseño más limpio */
                margin: 20px 0; /* Margen superior e inferior */
                font-family: Arial, sans-serif;
            }

            #tablaPermisos th,
            #tablaPermisos td {
                padding: 10px; /* Espaciado interior de celdas */
                border: 1px solid #ccc; /* Borde para celdas */
            }

            #tablaPermisos th {
                background-color: #f2f2f2; /* Color de fondo para encabezados */
                text-align: left; /* Alineación de texto en encabezados */
            }

            /* Estilo para la columna específica */
            #tablaPermisos td:nth-child(4) {
                width: 50px; /* Ancho de la columna específica */
                text-align: center; /* Alineación de texto al centro */
            }
        </style>

    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Usuario";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2><strong>Cambiar contraseña</strong></h2>
                    <br>
                    <form id="formularioClave" action="controladores/updateClave.php" method="post">
                        <input type ="hidden" id = "idUsuario" name = "idUsuario" value = "<?php echo $_GET['id'] ?>" >
                        <div style="display: flex; justify-content: space-between;">
                            <div style="width: 45%;">
                                <label for="clave"><strong>Contraseña</strong></label>
                                <input type="password" id="clave" name="clave" value = "" required>
                            </div>
                            <div style="width: 45%;">
                                <label for="nombres"><strong>Confirmar Clave</strong></label>
                                <input type="password" id="confirmarClave" name="confirmarClave" value = "" required>
                            </div>
                        </div>
                        <br>
                        <a><button class="btn btn-primary primeButton" type="submit">Cambiar</button></a>
                        <br>  
                    </form>
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
<!--         
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

        <script>
            document.getElementById('formularioClave').addEventListener('submit', function (e) {
                e.preventDefault(); 

                var clave = document.getElementById('clave').value;
                var confirmarClave = document.getElementById('confirmarClave').value;

                if (clave === confirmarClave) {
                    this.submit(); 
                } else {
                   
                    Swal.fire({
                        icon: 'error',
                        title: 'Las contraseñas no coinciden',
                        text: 'Por favor, asegúrate de que las contraseñas sean iguales.',
                    });
                }
            });
        </script>
    </body>
</html>