//Tabla
tablaAlistamiento = document.getElementById('tablaVerificacion');

// Modales
modalCerrar = document.getElementById("modalConfirmarCerrar"); 

// Botones del menu
btnMenuPendiente = document.getElementById("botonPendiente");
btnMenuCerrar = document.getElementById("botonCerrar");

btnMenuCerrar.addEventListener('click', function() {

    verificarCompleto();

});

btnMenuPendiente.addEventListener('click', function() {
    guardar(1);
});

// Botones de submenu    
const btnAceptarCerrar = document.getElementById('confirmarCerrar');
const btnCancelarCerrar = document.getElementById('cancelarCerrar');

btnAceptarCerrar.addEventListener('click', confirmarAccionCerrar);

btnCancelarCerrar.addEventListener('click', ocultarDialogo);

let controladorRevision = 0;

//Esta es una variable para cuando mas de un proyecto tiene el mismo id
let productosConCantidad = 0;

quitarDatos();

function ocultarDialogo() {
    modalCerrar.style.display = 'none';
}

function confirmarAccionCerrar() {
    ocultarDialogo();
    var embalaje = obtenerValoresDeTabla();
    var obs = $('#observacionesVer').val();
    guardar(2, obs,  embalaje);
}

function busqueda(){
    let input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById('busqueda');
    filter = input.value.toUpperCase();
    table = document.getElementById('tablaVerificacion');
    tbody = table.getElementsByTagName('tbody')[0];
    tr = tbody.getElementsByTagName('tr');

    conTotal = 0;
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName('td');
        var cont = 0;
        if (filter!=""){
            if (td[0]) {
                txtValue = td[0].textContent || td[0].innerText;
                if (txtValue == filter) {
                    tr[i].style.display = '';
                    cont++;
                    conTotal++;
                } 
            }
            if (td[1]) {
                txtValue = td[1].textContent || td[1].innerText;
                if (txtValue == filter) {
                    tr[i].style.display = '';
                    cont++;
                    conTotal++;
                } 
            }
            if (td[2]) {

                txtValue = td[2].textContent || td[2].innerText;

                var palabras = filter.split(" ");

                var controladorCompleto = true;

                for (j = 0; j < palabras.length; j++) {
                    var palabra = palabras[j].toUpperCase();
                    if (txtValue.toUpperCase().indexOf(palabra) === -1) {
                        controladorCompleto = false;
                        break;
                    }
                }
                
                if (controladorCompleto) {
                    tr[i].style.display = '';
                    cont++;
                    conTotal++;
                } 
            }
 
        }
        if (cont == 0){
            tr[i].style.display = 'none';
        }
    }

    if (conTotal == 0 && filter != ''){
        Swal.fire({
            title: 'Producto no encontrado',
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }

    document.getElementById('busqueda').value = '';
};


function reproducirSonido(nombreSonido) {
    const audio = new Audio('audio/' + nombreSonido + '.mp3');
    audio.play();
}

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

        if(input == selectedItem){

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

function vaciarEspacioTexto(proId) {

    let numeroAparaciones = contarApariciones(proId);
    productosConCantidad++;

    if (productosConCantidad >= numeroAparaciones){
        productosConCantidad = 0;
        document.getElementById('busqueda').value = '';
        controladorRevision = 0;
        busqueda();
    }
}

function contarApariciones(proId) {
    let filas = document.querySelectorAll("#tablaVerificacion tbody tr");
    let contador = 0;

    filas.forEach(fila => {
        if (fila.getAttribute("data-proid") === proId) {
            contador++;
        }
    });

    return contador;
}

document.getElementById('tablaVerificacion').addEventListener('change', function(event) {
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

        fetch('controladores/verificarCantidadAlistada.php', requestOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta de la red');
                }
                return response.json();
            })
            .then(data => {
                if(data.status == 1){

                    if (data.estado){
                        nuevaClase = 'verificacion-completo';
                        vaciarEspacioTexto(data.proId);
                    }else{
                        nuevaClase = 'verificacion-incorrecto';
                        Swal.fire({
                            title: 'Error',
                            text: 'Cantidad incorrecta',
                            icon: 'error',
                            confirmButtonText: 'Entendido'
                        });
                    }

                    var fila = document.querySelector('tr[data-id="' + id + '"]');
            
                    fila.classList.remove('verificacion-completo', 'verificacion-incorrecto');
                    fila.classList.add(nuevaClase);

                }else{
                    alert ("Error al guardar contacte con el administrador");
                }
            })
            .catch(error => {
                alert('Error con la conexión a la base de datos' + error);
            });

    }
});

function verificarCompleto(){

    var dataToSend = {
        idFactura: $('#idFactura').val(),
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
    fetch('controladores/revisarVerificacion.php', requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(data => {
            if(!data.estado){
                Swal.fire({
                    title: 'Error',
                    text: 'Verificacion incompleta',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            }else{
                modalCerrar.style.display = "block";
            }
        
        })
        .catch(error => {
            alert('Error:', error);
        });


}

function guardar(estado, observacion, embalaje) {

    iniciarCarga();

    var dataToSend = {
        idFactura: $('#idFactura').val(),
        idEstado: estado,
        observacion: observacion,
        embalaje: embalaje
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
    fetch('controladores/guardarVerificacion.php', requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta:', data);
            if(data.status == 7){
                window.location.href = "cargaEtiqueta.php?idFactura=" + $('#idFactura').val();
            }else if(data.status == 1){
                //if (estado == 2){
                //    window.open('documentos/etiquetas' + $('#idFactura').val() + '.pdf', '_blank');
                //}
                window.location.href = 'lista_verificacion.php';
            }else{
                alert ("Error al guardar 2");
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
    
    console.log("valores de la tabla");
    console.log(valores);

    return valores;
}

function quitarDatos(){
    let input, filter, table, tr, td, i, j, txtValue;
    table = document.getElementById('tablaVerificacion');
    tbody = table.getElementsByTagName('tbody')[0];
    tr = tbody.getElementsByTagName('tr');

    for (i = 0; i < tr.length; i++) {
        
        td = tr[i].getElementsByTagName('td');
        console.log(td);
        if (td[0]) {
            tr[i].style.display = 'none';
        }
    }
    var inputElement = document.getElementById("busqueda");
    inputElement.focus();
    
}

document.getElementById('busqueda').addEventListener('input', esperarTeclado);

let timerId;

function esperarTeclado(){
    
    clearTimeout(timerId);
    timerId = setTimeout(revisorCode, 1000);
}

function revisorCode(){

    if (controladorRevision == 1){
        controladorRevision = 0;
        busqueda()
    }
    
}

function leerCodigo(){
    var inputElement = document.getElementById("busqueda");
    inputElement.value = "";
    inputElement.focus();
    controladorRevision = 1;
}


// Para la vista de carga 

var loader = document.getElementById("loader");
var stopButton = document.getElementById("stopButton");
var loading = false;

function detenerCarga() {
    if (loading) {
        loader.style.display = "none";
        loading = false;
    }
}

function iniciarCarga() {
    if (!loading) {
        loader.style.display = "block";
        loading = true;
    }
}