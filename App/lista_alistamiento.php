<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <style>
            body {
                font-family: arial;
            }

            table {
                border: 1px solid #ccc;
                width: 100%;
                margin:0;
                padding:0;
                border-collapse: collapse;
                border-spacing: 0;
            }

            table tr {
                border: 1px solid #ddd;
                padding: 5px;
            }

            table th, table td {
                padding: 10px;
                text-align: center;
            }

            table th {
                text-transform: uppercase;
                font-size: 14px;
                letter-spacing: 1px;
            }

            @media screen and (max-width: 600px) {
             
                table {
                    border: 0;
                }

                table thead {
                    display: none;
                }

                table tr {
                    margin-bottom: 10px;
                    display: block;
                    border-bottom: 2px solid #ddd;
                }

                table td {
                    display: block;
                    text-align: right;
                    font-size: 13px;
                    border-bottom: 1px dotted #ccc;
                }

                table td:last-child {
                    border-bottom: 0;
                }

                table td:before {
                    content: attr(data-label);
                    float: left;
                    text-transform: uppercase;
                    font-weight: bold;
                }
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
                            <div class="table">
                                <?php
                                    include('partes/tablas/t_lista_alistamiento.php')
                                ?>     
                            </div>
                        </a>
                    </div>
                </main>
            </div>
        </div>

        <div class = "container fullwidth mt-5" > 
            <div class="col-sm-12">
                <br>
                    <a href = "dashboard.php" ><button class="btn btn-primary primeButton" type="button">Volver</button></a>
                <br>
                <br>
                <div class="table">
                    <?php
                        include('partes/tablas/t_lista_alistamiento.php')
                    ?>     
                </div>
            </div>
        </div>

        <?php
            include('partes/foot.php')
        ?>   
    </body>
</html>