<?php
    session_start(); 	

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $permiso1 = "Admin";

    if (!(strpos($_SESSION['permisos'], $permiso1))) {
        header("Location: dashboard.php");
        exit();
    }

    require_once 'controladores/Connection.php';

    $con = Connection::getInstance()->getConnection();
    $quer = $con->query("select * from Usuarios;");

    $datosUsuarios = array();
    
    while ($columna = $quer->fetch_assoc()) {
        $row['idUsuarios'] = $columna['idUsuarios'];
        $row['Cedula'] = $columna['Cedula'];
        $row['Nombres'] = $columna['Nombres'];
        $row['Apellidos'] = $columna['Apellidos'];

        $datosUsuarios[] = $row;
    }
?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <style>
            .select option {
                background-color: #f2f2f2 !important;
                color: #333 !important;
                padding: 5px !important;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Bitacora";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2>Bitacora</h2>
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div style="width: 30%;">
                            <label><strong>Fecha inicio</strong></label>
                            <input type="date" id="fechaInicio" name="fechaInicio">
                        </div>
                        <div style="width: 30%;">
                            <label><strong>Fecha fin</strong></label>
                            <input type="date" id="fechaFin" name="fechaFin">
                        </div>
                        <div style="width: 30%;">
                            <label><strong>Usuario</strong></label>
                            <select id="usuario" class="select">
                            <option value="0">Seleccione una opción</option>
                                <?php
                                    foreach ($datosUsuarios as $usuario) {
                                ?>

                                <option value="<?php echo $usuario['idUsuarios'] ?>"><?php echo $usuario['Cedula'] . " - " . $usuario['Nombres'] . " - " . $usuario['Apellidos'] ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <button type="button" class="btn btn-secondary" id = "botonVaciar">Vaciar</button>
                    <br>
                    <table id="tablaBitacora" class="display">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Usuario</th>
                                <th>Accion</th>
                                <th>Factura</th>
                            </tr>
                        </thead>
                    </table>
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

        <script>

            $(document).ready( function () {

                var botonV = document.getElementById('botonVaciar');

                const fechaInicioInput = document.getElementById('fechaInicio');
                const fechaFinInput = document.getElementById('fechaFin');
                const usuario = document.getElementById('usuario');

                fechaInicioInput.addEventListener('change', function() {
                    tablaBitacora.ajax.reload();
                });

                fechaFinInput.addEventListener('change', function() {
                    tablaBitacora.ajax.reload();
                });

                usuario.addEventListener('change', function() {
                    console.log(usuario.value);
                    tablaBitacora.ajax.reload();
                });

                botonV.addEventListener('click', function() {
                    fechaInicioInput.value = null;
                    fechaFinInput.value = null;
                    usuario.value = 0;
                    tablaBitacora.ajax.reload();
                });

                var tablaBitacora = $('#tablaBitacora').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: 'core/tabla_bitacora.php',
                        type: 'GET',
                        data: function(data) {
                            data.inicial = $('#fechaInicio').val(),
                            data.final = $('#fechaFin').val(),
                            data.usuario = $('#usuario').val()
                        }
                    },
                    language: {
                        lengthMenu: '',
                        search: 'Buscar',
                        zeroRecords: 'Ningún Resultado',
                        emptyTable: "Ningún dato disponible en esta tabla",
                        info: 'De _START_ A _END_ De Un Total De _TOTAL_',
                        infoEmpty: 'Ningún Resultado',
                        infoFiltered: '(Filtrando _MAX_ En Total)',
                        loadingRecords: 'Cargando',
                        paginate: {
                            first: 'Primero',
                            last: 'Último',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        },
                    },
                    columns: [
                        {data: 'fecha', name:'fecha', orderable: true, searchable: true},
                        {data: 'hora', name:'hora', orderable: true, searchable: true},
                        {data: 'usuario', name:'usuario', orderable: true, searchable: true},
                        {data: 'accion', name:'accion', orderable: true, searchable: true},
                        {data: 'factura', name:'factura', orderable: true, searchable: true}
                    ],

                });            
            });
        </script>
    </body>
</html>