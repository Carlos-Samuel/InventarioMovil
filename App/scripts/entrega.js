btnMenuCerrar = document.getElementById("botonCerrar");

btnMenuCerrar.addEventListener('click', function() {

    guardar();

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
