// Modales
modalEnviarAlistamiento = document.getElementById("modalConfirmarEnviarAlistamiento"); 
modalCerrar = document.getElementById("modalConfirmarCerrar"); 

// Botones del menu
btnMenuEnviarAlistamiento = document.getElementById("botonEnviarAlistamiento");
btnMenuCerrar = document.getElementById("botonCerrar");

btnMenuEnviarAlistamiento.addEventListener('click', function() {
    modalEnviarAlistamiento.style.display = "block";
});

btnMenuCerrar.addEventListener('click', function() {
    modalCerrar.style.display = "block";
});


// Botones de submenu    
const btnAceptarEnviarAlistamiento = document.getElementById('confirmarEnviarAlistamiento');
const btnAceptarCerrar = document.getElementById('confirmarCerrar');

const btnCancelarEnviarAlistamiento = document.getElementById('cancelarEnviarAlistamiento');
const btnCancelarCerrar = document.getElementById('cancelarCerrar');

btnAceptarEnviarAlistamiento.addEventListener('click', confirmarAccionEnviarAlistamiento);
btnAceptarCerrar.addEventListener('click', confirmarAccionCerrar);

btnCancelarEnviarAlistamiento.addEventListener('click', ocultarDialogo);
btnCancelarCerrar.addEventListener('click', ocultarDialogo);

function ocultarDialogo() {
    modalEnviarAlistamiento.style.display = 'none';
    modalCerrar.style.display = 'none';
}

function confirmarAccionEnviarAlistamiento() {
    ocultarDialogo();
    guardar(1);
}

function confirmarAccionCerrar() {
    ocultarDialogo();
    guardar(0);
}


var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
    ocultarDialogo();
}

window.onclick = function(event) {
    if (event.target == modalEnviarAlistamiento || event.target == modalCerrar) {
        ocultarDialogo();
    }
}

function guardar(estado) {

    var dataToSend = {
        idFactura: $('#idFactura').val(),
        idEstado: estado
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
    fetch('controladores/guardarCierreForzado.php', requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta:', data);
            if(data.status == 1){
                window.location.href = 'cierresForzadosControl.php';
            }else{
                alert ("Error al guardar");
            }
        })
        .catch(error => {
            alert('Error:', error);
        });

}

let selectedItem = null;
let selectedCodBar = null;
