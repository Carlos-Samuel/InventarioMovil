<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <table id="miTabla" class="display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Edad</th>
                                <th>Email</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-id="1">1</td>
                                <td>Juan</td>
                                <td>25</td>
                                <td>juan@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="2">2</td>
                                <td>Maria</td>
                                <td>30</td>
                                <td>maria@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="3">3</td>
                                <td>Juan</td>
                                <td>25</td>
                                <td>juan@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="4">4</td>
                                <td>Maria</td>
                                <td>30</td>
                                <td>maria@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="5">5</td>
                                <td>Juan</td>
                                <td>25</td>
                                <td>juan@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="6">6</td>
                                <td>Maria</td>
                                <td>30</td>
                                <td>maria@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="7">7</td>
                                <td>Juan</td>
                                <td>25</td>
                                <td>juan@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="8">8</td>
                                <td>Maria</td>
                                <td>30</td>
                                <td>maria@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="9">9</td>
                                <td>Juan</td>
                                <td>25</td>
                                <td>juan@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="10">10</td>
                                <td>Maria</td>
                                <td>30</td>
                                <td>maria@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="11">11</td>
                                <td>Juan</td>
                                <td>25</td>
                                <td>juan@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                            <tr>
                                <td data-id="12">12</td>
                                <td>Maria</td>
                                <td>30</td>
                                <td>maria@example.com</td>
                                <td><button class="btn btn-primary primeButton">Editar</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <a href = "detalle_usuario.php" ><button class="btn btn-primary primeButton" type="button">Crear usuario</button></a>
                    <br>
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
                $('#miTabla').DataTable();

                $('.btnMostrar').on('click', function() {
                    var id = $(this).closest('tr').find('td[data-id]').data('id');
                    console.log('Valor de data-id:', id);
                    window.location.href = 'detalle_usuario.php';
                });
            } );
        </script>
    </body>
</html>