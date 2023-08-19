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
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2><strong>FORMULARIO DE USUARIOS</strong></h2>
                    <br>
                    <form id="crearUsuario" action="controladores/insertUsuario.php" method="post">
                        <div style="display: flex; justify-content: space-between;">
                            <div style="width: 30%;">
                                <label for="cedula"><strong>Cedula</strong></label>
                                <input type="number" id="cedula" name="cedula" required>
                            </div>
                            <div style="width: 30%;">
                                <label for="nombres"><strong>Nombres</strong></label>
                                <input type="text" id="nombres" name="nombres" required>
                            </div>
                            <div style="width: 30%;">
                                <label for="apellidos"><strong>Apellidos</strong></label>
                                <input type="text" id="apellidos" name="apellidos" required>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <div style="width: 30%;">
                                <label for="correo"><strong>Correo</strong></label>
                                <input type="email" id="correo" name="correo" required>
                            </div>
                            <div style="width: 30%;">
                                <label for="password"><strong>Contraseña</strong></label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            <div style="width: 30%;">
                                <label for="campo6"><strong>Confirmar Contraseña</strong></label>
                                <input type="password" id="Cpassword" name="Cpassword" required>
                            </div>
                        </div>
                        <button class="btn btn-primary primeButton" type="submit">Guardar</button>
                    </form>
                    <br>
                    <br> 
                </main>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>  
        <script>
        document.getElementById("crearUsuario").addEventListener("submit", function(event) {
            event.preventDefault();

            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("Cpassword").value;

            if (password !== confirmPassword) {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden',
                });

            }else{

                this.submit();

            }
            
        });

    </script>

    </body>
</html>