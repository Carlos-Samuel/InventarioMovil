<!doctype html>
<html lang="en" data-bs-theme="auto">
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
        <?php
            include('partes/header.php')
        ?>     
        <div class = "container fullwidth mt-5">  
            <div class="col-lg-6 col-xxl-4 my-5 mx-auto">
                <div class="d-grid gap-2">
                    <a class="btn btn-primary primeButton" href = "lista_alistamiento.php" role = "button">Alistamiento</a>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <button class="btn btn-secondary primeButton" type="button">Verificación</button>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <button class="btn btn-success primeButton" type="button">Entrega</button>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <button class="btn btn-danger primeButton" type="button">Logout</button>
                </div>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>   
    </body>
</html>