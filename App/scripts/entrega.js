btnMenuCerrar = document.getElementById("botonCerrar");
botonGenerarEtiqueta = document.getElementById("botonGenerarEtiqueta");

btnMenuCerrar.addEventListener('click', function() {

    guardar();

});

botonGenerarEtiqueta.addEventListener('click', function() {
    idFactura =  $('#idFactura').val();
    console.log(idFactura);
    guardarEmbalaje();

});

function guardar() {

    var dataToSend = {
        idFactura: $('#idFactura').val()
    };

    // Configuración de la solicitud
    var requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dataToSend)
    };

    // Realizar la solicitud utilizando fetch
    fetch('controladores/guardarEntrega.php', requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta:', data);
            if(data.status == 1){
                window.location.href = 'lista_entrega.php';
            }else{
                alert ("Error al guardar");
            }
        })
        .catch(error => {
            alert('Error:', error);
        });

}


function guardarEmbalaje() {

    var dataToSend = {
        idFactura: $('#idFactura').val(),
        embalaje: obtenerValoresDeTabla(),
        observacion: $('#observacionesVer').val()
    };

    // Configuración de la solicitud
    var requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dataToSend)
    };

    // Realizar la solicitud utilizando fetch
    fetch('controladores/guardarEmbalaje.php', requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta:', data);
            if(data.status == 1){
                imprimirEtiquetas($('#idFactura').val());
            }else{
                alert (data.error);
            }
        })
        .catch(error => {
            alert('Error:', error);
        });

}


function obtenerValoresDeTabla() {
    var tabla = document.getElementById('tablaEmbalaje'); 
    var filas = tabla.getElementsByTagName('tr');

    var valores = "";

    for (var i = 0; i < filas.length; i++) {
        var fila = filas[i];
        var celdas = fila.getElementsByTagName('td');

        if (celdas.length >= 2) {
            var descripcion = celdas[0].textContent;
            var cantidadInput = celdas[1].querySelector('input');
            var cantidad = cantidadInput.value;

            if (cantidad != null && cantidad != ""&& cantidad != 0){
                valores = valores + " " + descripcion + " : " + cantidad + " # ";
            }
        }
    }
    

    return valores;
}

function imprimirEtiquetas(idFactura) {
    // Mostrar el loader bloqueante
    document.getElementById('loader').style.display = 'block';

    fetch('controladores/imprimir.php?idFactura=' + encodeURIComponent(idFactura))
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.text(); // o .json() si cambias el backend
        })
        .catch(error => {
            console.error("Error en la impresión:", error);
            alert("Ocurrió un error al generar las etiquetas.");
        })
        .finally(() => {
            // Ocultar el loader en todos los casos
            document.getElementById('loader').style.display = 'none';
            asignarRutaEtiqueta('documentos/etiquetas' + idFactura + '.pdf');
        });
}

function asignarRutaEtiqueta(ruta) {
    const enlace = document.getElementById('enlaceEtiqueta');
    if (enlace) {
        enlace.href = ruta;
    }
}