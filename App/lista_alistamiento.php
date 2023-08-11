<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" href="css_individuales/alistamiento.css">

    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <div id = "contenidoMovil" style="display: none;">
                        <br>
                        <a href = "dashboard.php" ><button class="btn btn-primary primeButton" type="button">Volver</button></a>
                        <br>
                        <br>
                    </div>
                    <div class="table">
                        <br>
                        <br>
                        <table>
                            <thead>
                                <tr>
                                    <th>#Factura</th>
                                    <th>Fecha</th>
                                    <th>Nombre Cliente</th>
                                    <th>Razón Social</th>
                                    <th>Ciudad</th>
                                    <th>Vendedor</th>
                                    <th>Hora Doc</th>
                                    <th>Observacion</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Factura">FE 1234</td>
                                    <td data-label="Fecha">01/09/2022</td>
                                    <td data-label="NombreCliente">Sofia Pérez</td>
                                    <td data-label="RazonSocial">Tienda Sofia</td>
                                    <td data-label="Ciudad">Villavicencio</td>
                                    <td data-label="Vendedor">Carlos</td>
                                    <td data-label="HoraDoc">8:30 AM</td>
                                    <td data-label="Observacion">Ser muy demasiado muchisimo cuidadoso</td>
                                    <td data-label="Accion">
                                        <a href = "alistamiento.php" ><button class="btn btn-primary primeButton" type="button">Procesar</button></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Factura">FE 1234</td>
                                    <td data-label="Fecha">01/09/2022</td>
                                    <td data-label="NombreCliente">Sofia Pérez</td>
                                    <td data-label="RazonSocial">Tienda Sofia</td>
                                    <td data-label="Ciudad">Villavicencio</td>
                                    <td data-label="Vendedor">Carlos</td>
                                    <td data-label="HoraDoc">8:30 AM</td>
                                    <td data-label="Observacion">Ser muy demasiado muchisimo cuidadoso</td>
                                    <td data-label="Accion">
                                        <a href = "alistamiento.php" ><button class="btn btn-primary primeButton" type="button">Procesar</button></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>     
                    </div>  
                </main>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>