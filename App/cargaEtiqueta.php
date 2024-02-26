<?php 
    //include "controladores/imprimir.php";
    $idFactura = $_GET['idFactura'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Animación de Carga</title>
<link rel="stylesheet" href="style.css">
<style>

    body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    flex-direction: column; /* Alinea los elementos verticalmente uno tras otro */
    }

    .loading {
    border: 48px solid #f3f3f3; /* Color de fondo */
    border-top: 48px solid #27E132; /* Color del círculo */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
    margin-bottom: 20px; /* Espacio entre la animación y los botones */
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }

    button {
    margin: 5px; 
    padding: 10px 20px; 
    }
</style>
</head>
<body>

<div id="loading" class="loading" style="display: none;"></div>
<!--
<button id="startBtn">Iniciar Carga</button>
<button id="stopBtn">Detener Carga</button>
-->
<input type = "hidden" id = "idFactura" value = "<?php echo $idFactura; ?>">

<script>
    /*
    document.getElementById('startBtn').addEventListener('click', function() {
        document.getElementById('loading').style.display = 'block';
    });

    document.getElementById('stopBtn').addEventListener('click', function() {
        document.getElementById('loading').style.display = 'none';
    });
    */
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('loading').style.display = 'block';

        idFactura = document.getElementById('idFactura').value;

        fetch('controladores/imprimir.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', 
        },
        body: 'idFactura='+idFactura
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); 
            document.getElementById('loading').style.display = 'none';
            window.location.href = 'lista_verificacion.php';
        })
        .catch((error) => console.error('Error:', error));


    });


</script>
</body>
</html>
