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

        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
            <main class="content">
                <div>
                <a id="btn-toggle" href="#" class="sidebar-toggler break-point-sm">
                    <i class="ri-menu-line ri-xl"></i>
                </a>
                </div>
            </main>
            </div>
        </div>
        <div class = "container fullwidth mt-5" display = "none">
            <div class="col-lg-6 col-xxl-4 my-5 mx-auto">
                <div class="d-grid gap-2">
                    <a class="btn btn-danger primeButton" href = "lista_alistamiento.php" role = "button">Alistamiento</a>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <a class="btn btn-warning primeButton" href = "lista_verificacion.php" role = "button">Verificación</a>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <a class="btn btn-success primeButton" href = "lista_alistamiento.php" role = "button">Entrega</a>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <a class="btn btn-primary primeButton" href = "index.php" role = "button">Logout</a>
                </div>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>