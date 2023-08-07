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
        <?php
            include('partes/header.php')
        ?>
            <div class = "container fullwidth mt-5"> 
                <br>
                <br>
                <div class="col-sm-12">
                    <br>
                        <a href = "dashboard.php" ><button class="btn btn-primary primeButton" type="button">Volver</button></a>
                    <br>
                    <br>
                    <div class="table">
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
                                        <a href = "alistamiento.php" ><button class="btn btn-primary primeButton" type="button">Detalles</button></a>
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
                                        <a href = "alistamiento.php" ><button class="btn btn-primary primeButton" type="button">Detalles</button></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
            include('partes/foot.php')
        ?>   
    </body>
</html>