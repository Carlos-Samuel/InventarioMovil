// Modales
modalCerrar = document.getElementById("modalConfirmarCerrar"); 
modalDevolver = document.getElementById("modalConfirmarDevolver"); 
modalForzado = document.getElementById("modalConfirmarForzado"); 

// Botones del menu
btnMenuPendiente = document.getElementById("botonPendiente");
btnMenuCerrar = document.getElementById("botonCerrar");
btnMenuDevolver = document.getElementById("botonDevolver");
btnMenuForzado = document.getElementById("botonForzado");

btnMenuPendiente.addEventListener('click', function() {
    ocultarDialogo();
    window.location.href = 'lista_alistamiento.php';
});

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
    window.location.href = 'lista_alistamiento.php';
}

function confirmarAccionDevolver() {
    ocultarDialogo();
    window.location.href = 'lista_alistamiento.php';
}

function confirmarAccionForzado() {
    ocultarDialogo();
    window.location.href = 'lista_alistamiento.php';
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