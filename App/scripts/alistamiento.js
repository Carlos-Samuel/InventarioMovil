// Modales
modalCerrar = document.getElementById("modalConfirmarCerrar"); 
modalDevolver = document.getElementById("modalConfirmarDevolver"); 
modalForzado = document.getElementById("modalConfirmarForzado"); 
modalPendiente = document.getElementById("modalConfirmarPendiente"); 

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

btnMenuPendiente.addEventListener('click', function() {
    modalPendiente.style.display = "block";
    //guardar(2);
});

// Botones de submenu    
const btnAceptarPendiente = document.getElementById('confirmarPendiente');
const btnAceptarCerrar = document.getElementById('confirmarCerrar');
const btnAceptarDevolver = document.getElementById('confirmarDevolver');
const btnAceptarForzado = document.getElementById('confirmarForzado');

const btnCancelarPendiente = document.getElementById('cancelarPendiente');
const btnCancelarCerrar = document.getElementById('cancelarCerrar');
const btnCancelarDevolver = document.getElementById('cancelarDevolver');
const btnCancelarForzado = document.getElementById('cancelarForzado');

btnAceptarCerrar.addEventListener('click', confirmarAccionCerrar);
btnAceptarDevolver.addEventListener('click', confirmarAccionDevolver);
btnAceptarForzado.addEventListener('click', confirmarAccionForzado);
btnAceptarPendiente.addEventListener('click', confirmarAccionPendiente);

btnCancelarCerrar.addEventListener('click', ocultarDialogo);
btnCancelarDevolver.addEventListener('click', ocultarDialogo);
btnCancelarForzado.addEventListener('click', ocultarDialogo);
btnCancelarPendiente.addEventListener('click', ocultarDialogo);

function ocultarDialogo() {
    modalCerrar.style.display = 'none';
    modalDevolver.style.display = 'none';
    modalForzado.style.display = "none";
    modalPendiente.style.display = "none";
}

function confirmarAccionCerrar() {
    ocultarDialogo();
    console.log("llega al accion cerrar");
    guardar(1);
}

function confirmarAccionDevolver() {
    ocultarDialogo();
    console.log("llega al accion devolver");
    guardar(0);
}

function confirmarAccionForzado() {
    ocultarDialogo();

    let varcedula = $('#cedulaUsuario').val();
    let varpassword = $('#passwordUsuario').val();

    var dataToSend = {
        cedula: varcedula,
        password: varpassword
    };

    // Configuración de la solicitud
    var requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dataToSend)
    };

    fetch('controladores/revisarUsuario.php', requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(data => {
            if(data.status == 1){
                if (data.estado){
                    guardar(3, '', varcedula);
                }else{
                    Swal.fire({
                        title: 'Error',
                        text: 'Credenciales incorrectas',
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                    $('#passwordUsuario').val('');
                }
            }else{
                alert ("Error al revisar contacte con el administrador");
            }
        })
        .catch(error => {
            alert('Error con la conexión a la base de datos' + error);
        });
}

function confirmarAccionPendiente() {
    let razon = $('#razon').val();

    if (razon.trim() === '') {
        Swal.fire({
            title: 'Error',
            text: 'Ingrese una razón',
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }else{
        // console.log("La razon es");
        // console.log(razon);
        guardar(2, razon);
    }
}
// Funcionalidades de la busqueda

// document.getElementById('').addEventListener('keyup', busqueda);

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
        modalPendiente.style.display = "none";
    }
}

function vaciarEspacioTexto() {
    document.getElementById('busqueda').value = '';
    controladorRevision = 0;
    busqueda();
}



function guardar(estado, razon, usuario) {

    $controlador = true;

    if (estado == 1){
        $controlador = verificarDiferenciasIgualesACero();
    }

    if ($controlador){

        var dataToSend = {
            idFactura: $('#idFactura').val(),
            idEstado: estado,
            justificacion: razon,
            usuario: usuario
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
    }else{
        Swal.fire({
            title: 'Error',
            text: 'Las Cantidades Alistadas no coinciden.',
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }

}

function reproducirSonido(nombreSonido) {
    const audio = new Audio('audio/' + nombreSonido + '.mp3');
    audio.play();
}


const tablaAlistamiento = document.getElementById('tablaAlistamiento');

let selectedItem = null;

// tablaAlistamiento.addEventListener('click', seleccionarFila);
// tablaAlistamiento.addEventListener('touchstart', seleccionarFila);

let controladorRevision = 0;

// function seleccionarFila(event) {
//     const filas = tablaAlistamiento.querySelectorAll('tr');
//     filas.forEach(fila => fila.classList.remove('selected-row'));

//     const filaSeleccionada = event.target.closest('tr');

//     if (filaSeleccionada) {
//         filaSeleccionada.classList.add('selected-row');
        
//         selectedItem = filaSeleccionada.querySelector('[data-label="Item"]').textContent;

//         controladorRevision = 1;
//         limpiar(selectedItem);

//     }
// }

var dataId = 0;

function limpiar(item){
    let input, filter, table, tr, td, i, j, txtValue;
    table = document.getElementById('tablaAlistamiento');
    tbody = table.getElementsByTagName('tbody')[0];
    tr = tbody.getElementsByTagName('tr');

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName('td');
        if (td[0]) {
            txtValue = td[0].textContent || td[0].innerText;
            if (txtValue == item) {
                tr[i].style.display = '';
                dataId = tr[i].getAttribute('data-id');
            }else{
                tr[i].style.display = 'none';
            }
        }
    }
    var inputElement = document.getElementById("busqueda");
    inputElement.focus();
    console.log();
    
}

//document.getElementById('busqueda').addEventListener('keyup', revisorCode);

document.getElementById('busqueda').addEventListener('input', esperarTeclado);

let timerId;

function esperarTeclado(){
    clearTimeout(timerId);
    timerId = setTimeout(revisorCode, 1000);
}

function revisorCode(){
    if (controladorRevision == 1){
        let input = document.getElementById('busqueda').value;

        if(input == selectedItem){

            //reproducirSonido('correcto');

            new Noty({
                type: 'success',
                text: 'Elemento correcto',
                timeout: 4000,
            }).show();

            selectedItem = null;
            controladorRevision = 0;

            var inputElement = document.getElementById("numero_" + dataId);
            inputElement.focus();

        }else{

            reproducirSonido('incorrecto');

            new Noty({
                type: 'error',
                text: 'Elemento erroneo',
                timeout: 4000,
            }).show();

        }

        document.getElementById('busqueda').value = '';
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

            //reproducirSonido('correcto');

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

document.getElementById('tablaAlistamiento').addEventListener('change', function(event) {
    var target = event.target;

    if (target && target.tagName === 'INPUT') {
        var id = target.closest('tr').getAttribute('data-id');
        var newValue = target.value;

        var dataToSend = {
            idProducto: id,
            cantidadAlistada: newValue
        };
    
        // Configuración de la solicitud
        var requestOptions = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dataToSend)
        };

        fetch('controladores/guardarCantidadAlistada.php', requestOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta de la red');
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta:', data);
                if(data.status == 1){

                    if (data.diferencia == 0){
                        nuevaClase = 'alistamiento-completo';
                        vaciarEspacioTexto();
                    }else{
                        nuevaClase = 'alistamiento-incompleto';
                    }

                    var fila = document.querySelector('tr[data-id="' + id + '"]');
            
                    fila.classList.remove('alistamiento-completo', 'alistamiento-incompleto');
                    fila.classList.add(nuevaClase);

                    var celdaDiferencia = document.querySelector('tr[data-id="' + id + '"] td[data-label="Diferencia"]');
                    celdaDiferencia.textContent = data.diferencia;

                }else{
                    alert ("Error al guardar contacte con el administrador");
                }
            })
            .catch(error => {
                alert('Error con la conexión a la base de datos' + error);
            });

    }
});

function verificarDiferenciasIgualesACero() {
    var filas = document.querySelectorAll('#tablaAlistamiento tbody tr');
    
    for (var i = 0; i < filas.length; i++) {
        var celdaDiferencia = filas[i].querySelector('td[data-label="Diferencia"]');
        var valorDiferencia = parseInt(celdaDiferencia.textContent);
        
        if (valorDiferencia !== 0) {
            return false;
        }
    }
    
    return true;
}

const botonesProcesar = document.querySelectorAll('.procesar-btn');

botonesProcesar.forEach(boton => {
    boton.addEventListener('click', function() {
        const item = this.getAttribute('data-item');
        console.log(item);
        controladorRevision = 1;
        selectedItem = item;
        limpiar(item);
    });
});
