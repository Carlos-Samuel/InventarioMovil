<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
        <link href="css/select2.min.css" rel="stylesheet" />

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
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2><strong>FORMULARIO DE USUARIOS</strong></h2>
                    <br>
                    <form>
                        <div style="display: flex; justify-content: space-between;">
                            <div style="width: 30%;">
                                <label for="campo3"><strong>Cedula</strong></label>
                                <input type="number" id="campo3" name="campo3">
                            </div>
                            <div style="width: 30%;">
                                <label for="campo2"><strong>Nombre</strong></label>
                                <input type="text" id="campo2" name="campo2" min="0" max="100" step="1">
                            </div>
                            <div style="width: 30%;">
                                <label for="campo3"><strong>Apellido</strong></label>
                                <input type="text" id="campo3" name="campo3">
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <!-- <div style="width: 30%;">
                                <label for="campo4">Campo 4</label>
                                <select id="campo4" class="select2">
                                    <option value="opcion4">Opción 4</option>
                                    <option value="opcion5">Opción 5</option>
                                    <option value="opcion6">Opción 6</option>
                                </select>
                            </div> -->
                            <div style="width: 30%;">
                                <label for="campo5"><strong>Correo</strong></label>
                                <input type="email" id="campo5" name="campo5" min="0" max="100" step="1">
                            </div>
                            <div style="width: 30%;">
                                <label for="campo5"><strong>Contraseña</strong></label>
                                <input type="password" id="campo5" name="campo5" min="0" max="100" step="1">
                            </div>
                            <div style="width: 30%;">
                                <label for="campo6"><strong>Confirmar Contraseña</strong></label>
                                <input type="password" id="campo6" name="campo6">
                            </div>
                        </div>
                    </form>
                    <br>
                    <h3><strong>PERMISOS DEL USUARIO</strong></h3>
                    <div class="table">
                        <table id = "tablaPermisos">
                            <thead>
                                <tr>
                                    <th>#Id</th>
                                    <th>Permiso</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-id="1">1</td>
                                    <td>Alistamiento</td>
                                    <td>Acceso a alistamiento</td>
                                    <td><button class="btnActivar">Activar</button></td>
                                </tr>
                                <tr>
                                    <td data-id="2">2</td>
                                    <td>Verificacion</td>
                                    <td>Acceso a verificacion</td>
                                    <td><button class="btnDesactivar">Desactivar</button></td>
                                </tr>
                                <tr>
                                    <td data-id="1">1</td>
                                    <td>Entrega</td>
                                    <td>Acceso a entrega</td>
                                    <td><button class="btnActivar">Activar</button></td>
                                </tr>
                                <tr>
                                    <td data-id="1">1</td>
                                    <td>Reportes</td>
                                    <td>Acceso a todos los reportes</td>
                                    <td><button class="btnActivar">Activar</button></td>
                                </tr>
                                <tr>
                                    <td data-id="1">1</td>
                                    <td>Admin</td>
                                    <td>Todos los permisos</td>
                                    <td><button class="btnActivar">Activar</button></td>
                                </tr>
                            </tbody>
                        </table>   
                        <br>
                        <a href = "lista_usuarios.php" ><button class="btn btn-primary primeButton" type="button">Guardar</button></a>
                        <br>  
                    </div>  
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
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2();
  });
</script>
        <script>
            $(document).ready( function () {
                $('#miTabla').DataTable();
            } );
        </script>
    </body>
</html>