// Modales
modalCerrar = document.getElementById("modalConfirmarCerrar"); 
modalDevolver = document.getElementById("modalConfirmarDevolver"); 
modalForzado = document.getElementById("modalConfirmarForzado"); 

// Botones del menu
btnMenuPendiente = document.getElementById("botonPendiente");
btnMenuCerrar = document.getElementById("botonCerrar");
btnMenuDevolver = document.getElementById("botonDevolver");
btnMenuForzado = document.getElementById("botonForzado");

// btnMenuPendiente.addEventListener('click', function() {
//     ocultarDialogo();
//     window.location.href = 'lista_alistamiento.php';
// });

btnMenuCerrar.addEventListener('click', function() {
    modalCerrar.style.display = "block";
});

btnMenuDevolver.addEventListener('click', function() {
    modalDevolver.style.display = "block";
});

btnMenuForzado.addEventListener('click', function() {
    modalForzado.style.display = "block";
});

// Botones de submenu    
const btnAceptarCerrar = document.getElementById('confirmarCerrar');
const btnAceptarDevolver = document.getElementById('confirmarDevolver');
const btnAceptarForzado = document.getElementById('confirmarForzado');

const btnCancelarCerrar = document.getElementById('cancelarCerrar');
const btnCancelarDevolver = document.getElementById('cancelarDevolver');
const btnCancelarForzado = document.getElementById('cancelarForzado');

btnAceptarCerrar.addEventListener('click', confirmarAccionCerrar);
btnAceptarDevolver.addEventListener('click', confirmarAccionDevolver);
btnAceptarForzado.addEventListener('click', confirmarAccionForzado);

btnCancelarCerrar.addEventListener('click', ocultarDialogo);
btnCancelarDevolver.addEventListener('click', ocultarDialogo);
btnCancelarForzado.addEventListener('click', ocultarDialogo);

function ocultarDialogo() {
    modalCerrar.style.display = 'none';
    modalDevolver.style.display = 'none';
    modalForzado.style.display = "none";
}

function confirmarAccionCerrar() {
    ocultarDialogo();
    console.log("llega al accion cerrar");
    guardar(3);
    //window.location.href = 'lista_alistamiento.php';
}

function confirmarAccionDevolver() {
    ocultarDialogo();
    console.log("llega al accion devolver");
    guardar(0);
    //window.location.href = 'lista_alistamiento.php';
}

function confirmarAccionForzado() {
    console.log("llega al accion forzado");
    ocultarDialogo();
    //window.location.href = 'lista_alistamiento.php';
}

// Funcionalidades de la busqueda

document.getElementById('busqueda').addEventListener('keyup', busqueda);

function busqueda(){
    let input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById('busqueda');
    filter = input.value.toUpperCase();
    table = document.getElementById('tablaAlistamiento');
    tbody = table.getElementsByTagName('tbody')[0];
    tr = tbody.getElementsByTagName('tr');

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName('td');
        var cont = 0;
        for (j = 0; j < 2; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                    cont++;
                } 
            }
        }
        if (cont == 0){
            tr[i].style.display = 'none';
        }
    }
};

// Para el manejo del modal

var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
    ocultarDialogo();
}

window.onclick = function(event) {
    if (event.target == modalCerrar || event.target == modalDevolver || event.target == modalForzado) {
        modalCerrar.style.display = "none";
        modalDevolver.style.display = "none";
        modalForzado.style.display = "none";
    }
}

// Vaciado del buscador

function vaciarEspacioTexto() {
    document.getElementById('busqueda').value = '';
}

btnMenuPendiente.addEventListener('click', function() {
    guardar(2);
});

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
    fetch('controladores/guardarAlistamiento.php', requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta:', data);
            if(data.status == 1){
                window.location.href = 'lista_alistamiento.php';
            }else{
                alert ("Error al guardar");
            }
        })
        .catch(error => {
            alert('Error:', error);
        });

}

function reproducirSonido(nombreSonido) {
    const audio = new Audio('audio/' + nombreSonido + '.mp3');
    audio.play();
}


const tablaAlistamiento = document.getElementById('tablaAlistamiento');

let selectedItem = null;

tablaAlistamiento.addEventListener('click', seleccionarFila);
tablaAlistamiento.addEventListener('touchstart', seleccionarFila);

function seleccionarFila(event) {
    const filas = tablaAlistamiento.querySelectorAll('tr');
    filas.forEach(fila => fila.classList.remove('selected-row'));

    const filaSeleccionada = event.target.closest('tr');

    if (filaSeleccionada) {
        filaSeleccionada.classList.add('selected-row');
        
        selectedItem = filaSeleccionada.querySelector('[data-label="Item"]').textContent;

    }
}

function RevisarBarCode(){

    if (selectedItem == null){

        reproducirSonido('error');

        new Noty({
            type: 'warning',
            text: 'No has seleccionado ningún elemento.',
            timeout: 4000, 
        }).show();

    }else{

        let input = document.getElementById('busqueda').value;

        console.log("Este es el input : " + input);

        console.log("Este es el elemento seleccionado : " + selectedItem);

        if(input == selectedItem){

            reproducirSonido('correcto');

            new Noty({
                type: 'success',
                text: 'Elemento correcto',
                timeout: 4000,
            }).show();

            selectedItem = null;
        }else{

            reproducirSonido('incorrecto');

            new Noty({
                type: 'error',
                text: 'Elemento erroneo',
                timeout: 4000,
            }).show();

        }

    }

    
}