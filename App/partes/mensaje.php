<div class="mensaje" id="mensaje">
        <button class="cerrar" onclick="cerrarMensaje()">X</button>
        <p><?php echo $mensaje; ?></p>
    </div>
<script>
        function cerrarMensaje() {
            var mensaje = document.getElementById("mensaje");
            mensaje.style.display = "none";
        }
</script>