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
                $activado = "Alistamiento";
                include('partes/sidebar.php');
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
                    <div class="col-sm-12">
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Prefijo</th>
                                        <th># DOC</th>
                                        <th>Fecha</th>
                                        <th>Nombre Cliente</th>
                                        <th>Razón Social</th>
                                        <th>Ciudad</th>
                                        <th>Vendedor</th>
                                        <th>Hora DOC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Prefijo">RMV</td>
                                        <td data-label="Doc">1234</td>
                                        <td data-label="Fecha">01/11/2021</td>
                                        <td data-label="NombreCliente">Sofia Pérez</td>
                                        <td data-label="RazonSocial">Tienda Sofia</td>
                                        <td data-label="Ciudad">Villavicencio</td>
                                        <td data-label="Vendedor">Carlos</td>
                                        <td data-label="HoraDoc">8:30 AM</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="d-grid gap-2">
                            <button id="botonPendiente" class="btn btn-warning primeButton" type="button">Pendiente</button>
                        </div>
                        <div class="d-grid gap-2">
                            <button id="botonCerrar" class="btn btn-success primeButton" type="button">Cerrar</button>
                        </div>
                        <div class="d-grid gap-2">
                            <button id="botonDevolver" class="btn btn-danger primeButton" type="button">Devolver</button>
                        </div>
                        <div class="d-grid gap-2">
                            <button id="botonForzado" class="btn btn-info primeButton" type="button">Cierre forzado</button>
                        </div>
                        <br>
                        <div class="buscador">
                            <input type="text" id="busqueda" placeholder="" style="max-width: 400px;">
                            <br>
                            <a class="btn btn-primary primeButton"  onclick="vaciarEspacioTexto(); busqueda();" role = "button">Vaciar</a>
                        </div>
                        <br>
                        <div class="table">
                            <table id = "tablaAlistamiento">
                                <thead>
                                    <tr>
                                        <th>Ítem</th>
                                        <th>Descripción</th>
                                        <th>Ubicación</th>
                                        <th>Presentación</th>
                                        <th>Cantidad</th>
                                        <th class="input-container">Alistado</th>
                                        <th>Diferencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Item">010101</td>
                                        <td data-label="Descripcion">Galón pintura</td>
                                        <td data-label="Ubicacion">P1 E2 E3</td>
                                        <td data-label="Presentacion">Galón</td>
                                        <td data-label="Cantidad">2</td>
                                        <td data-label="Alistado" class="input-container"><input type="number" id="numero" name="numero" value="2"></td>
                                        <td data-label="Diferencia">0</td>
                                    </tr>
                                    <tr>
                                        <td data-label="Item">100109</td>
                                        <td data-label="Descripcion">Puntilla de pulgada</td>
                                        <td data-label="Ubicacion">P2 E4 E1</td>
                                        <td data-label="Presentacion">Caja</td>
                                        <td data-label="Cantidad">2</td>
                                        <td data-label="Alistado" class="input-container"><input type="number" id="numero" name="numero" value="1"></td>
                                        <td data-label="Diferencia">1</td>
                                    </tr>
                                    <tr>
                                        <td data-label="Item">200234</td>
                                        <td data-label="Descripcion">Varilla</td>
                                        <td data-label="Ubicacion">P5 E5 E6</td>
                                        <td data-label="Presentacion">Unidad</td>
                                        <td data-label="Cantidad">50</td>
                                        <td data-label="Alistado" class="input-container"><input type="number" id="numero" name="numero" value="48"></td>
                                        <td data-label="Diferencia">2</td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div id="modalConfirmarCerrar" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>¿Estás seguro de que desea cerrar el proceso?</p>
                <div class="boton-container">
                    <button id="confirmarCerrar" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarCerrar" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <div id="modalConfirmarDevolver" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>¿Estás seguro de que desea devolver la factura?</p>
                <div class="boton-container">
                    <button id="confirmarDevolver" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarDevolver" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <div id="modalConfirmarForzado" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>¿Estás seguro de que desea forzar el proceso?</p>
                <input type="password" placeholder="Ingrese la clave de usuario">
                <div class="boton-container">
                    <button id="confirmarForzado" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarForzado" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <script src="scripts/alistamiento.js"></script>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>