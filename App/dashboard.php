<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <style>
            .primeButton {
                height: 50px;
                border-radius: 25px;
            }
            @media screen and (max-width: 600px) {
                /* Estilos para la alineación de los botones en una fila */
                #moduloReporte {
                    display: none;
                }
                #moduloBitacora {
                    display: none;
                }
                #moduloPermisos {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <?php
            include('partes/head.php')
        ?>     
        <div class = "container fullwidth mt-5">  
            <div class="col-lg-6 col-xxl-4 my-5 mx-auto">
                <div class="d-grid gap-2">
                    <a class="btn btn-primary primeButton" href = "lista_alistamiento.php" role = "button">Alistamiento</a>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <a class="btn btn-secondary primeButton" href = "lista_verificacion.php" role = "button">Verificación</a>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <a class="btn btn-success primeButton" href = "lista_alistamiento.php" role = "button">Entrega</a>
                </div>
                <br>
                <div id = "moduloReporte">
                    <div class="d-grid gap-2" >
                        <a class="btn btn-warning primeButton" href = "lista_alistamiento.php" role = "button">Reportes</a>
                    </div>
                    <br>
                </div>
                <div id = "moduloPermisos">
                    <div class="d-grid gap-2" >
                        <a class="btn btn-warning primeButton" href = "lista_usuarios.php" role = "button">Usuarios</a>
                    </div>
                    <br>
                </div>
                <div id = "moduloBitacora">
                    <div class="d-grid gap-2" >
                        <a class="btn btn-warning primeButton" href = "lista_alistamiento.php" role = "button">Bitacora</a>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>   
    </body>
</html>