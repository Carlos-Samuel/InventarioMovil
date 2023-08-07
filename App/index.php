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

			.card-title{ font-weight:300; }

			.btn{
				font-size: 14px;
				margin-top:20px;
			}


			.login-form{ 
				width:330px;
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

			#footer {
				position: fixed;
				bottom: 0;
				width: 100%;
				/* Agrega cualquier otro estilo necesario para evitar superposiciones */
				z-index: 9999; /* Por ejemplo, aumenta el valor del z-index para asegurar que esté por encima de otros elementos */
				/* Agrega más estilos según tus necesidades */
			}
		</style>
    </head>
    <body>
        <?php
            include('partes/header.php')
        ?>
		<div class="global-container">
			<div class="card login-form">
				<div class="card-body">
					<br>
					<h3 class="text-center"><strong>Iniciar Sesión</strong></h3>
					<div class="card-text">
						<form action = "dashboard.php" method = "POST">
							<div class="form-group">
								<label for="usuarioCorreo">Usuario (Cédula)</label>
								<input type="text" class="form-control form-control-sm" name = "cedula" id="cedula">
							</div>
							<div class="form-group">
								<label for="contraseña">Contraseña</label>
								<input type="password" class="form-control form-control-sm" name = "password" id="password">
							</div>
							<button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php
            include('partes/foot.php')
        ?>
    </body>
</html>