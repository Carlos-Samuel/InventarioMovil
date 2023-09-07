<?php
    session_start(); 	

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
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
            .primeButton {
                height: 50px;
                width: 100%;
                border-radius: 25px;
                margin: 0 auto;
            }

        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <div>
                        <div class="col-lg-6 col-xxl-4 my-5 mx-auto" style="display: none;">
                            <?php
                                $permiso1 = "Admin";
                                $permiso2 = "Alistamiento";

                                if (strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2)) {
                            ?>
                                <div class="d-grid gap-2">
                                    <a class="btn btn-danger primeButton" href = "lista_alistamiento.php" role = "button">Alistamiento</a>
                                </div>
                                <br>
                            <?php
                                }
                            ?>
                            <?php
                                $permiso1 = "Admin";
                                $permiso2 = "Verificacion";

                                if (strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2)) {
                            ?>
                                <div class="d-grid gap-2">
                                    <a class="btn btn-warning primeButton" href = "lista_verificacion.php" role = "button">Verificación</a>
                                </div>
                                <br>
                            <?php
                                }
                            ?>
                            <?php
                                $permiso1 = "Admin";
                                $permiso2 = "Entrega";

                                if (strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2)) {
                            ?>
                                <div class="d-grid gap-2">
                                    <a class="btn btn-success primeButton" href = "lista_alistamiento.php" role = "button">Entrega</a>
                                </div>
                                <br>
                            <?php
                                }
                            ?>
                            <div class="d-grid gap-2">
                                <a class="btn btn-primary primeButton" href = "controladores/logout.php" role = "button">Logout</a>
                            </div>
                        </div>
                    </div>
                </main>
                <h1 id="elemento1"></h1>
                <br>
                <h1 id="elemento2"></h1>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>  
        <script>
            // Obtener el ancho y largo de la pantalla utilizando JavaScript
            var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

            // Enviar los valores al servidor PHP a través de una solicitud AJAX si es necesario
            // Aquí solo mostramos los valores en la consola
            console.log("Ancho de pantalla: " + screenWidth);
            console.log("Largo de pantalla: " + screenHeight);

            // Selecciona los elementos por sus identificadores
            var elemento1 = document.getElementById("elemento1");
            var elemento2 = document.getElementById("elemento2");

            // Asigna valores a los elementos de texto
            // elemento1.textContent = "Ancho de pantalla: " + screenWidth;
            // elemento2.textContent = "Largo de pantalla: " + screenHeight;
        </script>
    </body>
</html>