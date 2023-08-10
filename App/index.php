<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
		<style>
			html,body { 
				height: 100%; 
			}

			.global-container{
				height:100%;
				display: flex;
				align-items: center;
				justify-content: center;
				background-color: #f5f5f5;
			}

			form{
				padding-top: 10px;
				font-size: 14px;
				margin-top: 30px;
			}

			.card-title{ 
				font-weight:300; 
			}

			.btn{
				font-size: 14px;
				margin-top:20px;
			}

			.login-form{ 
				width:800px;
				margin:20px;
			}

			.sign-up{
				text-align:center;
				padding:20px 0 0;
			}

			.alert{
				margin-bottom:-30px;
				font-size: 13px;
				margin-top:20px;
			}
			
			.card-body{
				box-shadow: 2px 2px 5px #999;
				padding: 40px; /* Espacio interno */
			}

			#footer {
				position: fixed;
				bottom: 0;
				width: 100%;
				z-index: 9999;
			}

			.btn.btn-primary.btn-block {
				width: 100%;
				height: 50px;
				font-size: 24px;
			}

			#botonMostrar{
				height: 50px;
				position: absolute;
  				top: 0;
				width: 80%;
				font-size: 16px;
			}

			.col-2{
				position: relative;
			}

			.global-container {
				background-color: #18519c !important;
			}

			@media screen and (max-width: 600px) {
				.card.login-form {
					height: 100%;
					width: 100%;
				}
				#botonMostrar{
					font-size: 10px;
				}
			}
		</style>
    </head>
    <body>
		<div class="global-container">
			<div class="card login-form">
				<div class="card-body">
					<h3 class="text-center"><strong>Iniciar Sesión</strong></h3>
					<div class="card-text">
						<form action = "dashboard.php" method = "POST">
							<div class="form-group">
								<label for="usuarioCorreo">Usuario (Cédula)</label>
								<br>
								<br>
								<input type="text" class="form-control form-control-sm" name = "cedula" id="cedula">
							</div>
							<br>
							<div class="form-group">
								<label for="contraseña">Contraseña</label>
								<div class="row">
									<div class="col-10">
										<br>
										<input type="password" class="form-control form-control-sm" name = "password" id="password">
									</div>
									<div class="col-2">
										<button id = "botonMostrar"class = "btn btn-primary" type = "button" onclick = "mostrarContrasena()">Mostrar</button>
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<script>
			function mostrarContrasena(){
				var type = document.getElementById("password");
				var botonMostrar = document.getElementById("botonMostrar");
				if (type.type == "password"){
					type.type = "text";
					botonMostrar.innerText = "Ocultar";
				}else{
					type.type = "password";
					botonMostrar.innerText = "Mostrar";
				}
			}
		</script>

		<?php
            include('partes/foot.php')
        ?>
    </body>
</html>