<!DOCTYPE html>
<html lang="en">
<?php 
include_once 'core/Config.php';
include_once 'core/Router.php';
include_once 'core/Controller.php'; 
session_start();
if(isset($_SESSION['user_name']))
{
	//echo '<script>location.href = "v/deegreDocument/document.php";</script>';
	$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'admin';
if ($rol === 'admin') {
    echo '<script>location.href = "v/secretaria/secretaria.php";</script>';
} else {
    echo '<script>location.href = "v/deegreDocument/document.php";</script>';
}
}
else
{
	?>
<head>
	<meta charset="UTF-8">
	<title>Sistema de Titulación Electrónica</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/estilos.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src= "https://code.jquery.com/jquery-3.6.0.min.js"  integrity= "sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="  crossorigin="anonymous">
    </script>
</head>
<body background="assets/images/report/inaoe2.jpg">
	<div class="container" >
		<div style="width: 60%; margin: auto; margin-top: 10vh;">
			<form  id="login-form" style="background-color: rgba(10, 10, 50, 0.70)">
				<h4 class="text-center" style="background: #162DBF; color: white; padding: 10px;">Inicio de sesión</h4>
				
				<div align="center" style="padding-top: 10px;">
					<img src="assets/images/siste_4.png" style="max-width:100%;width: 300px; height:100px; align:center">
					<h4 style="color:#DFE0ED; padding-top: 10px">Sistema de Titulación Electrónica</h4>
					
				</div>
				
				<div style="padding: 10px; padding-top: 20px;">
					<div class="form-group">
						<div style="padding: 50px; padding-top: 20px;">
							<div class="input-group mb-3">
  								<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
  								<input type="text" class="form-control" maxlength="50" id="username" placeholder="Nombre de usuario">
							</div>
							<div class="input-group mb-3">
  								<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
  								<input type="password" class="form-control" id="password" placeholder="Contraseña">
							</div>
							<!--<div class="form-group">
								<label for="password">Contraseña</label>
								<input type="password" class="form-control" id="password">
							</div>-->
							<button type="submit" class="btn btn-success w-100">ENTRAR</button>
							<!--<button type="submit" class="btn btn-success w-100" disabled>PÁGINA EN MANTENIMIENTO</button>-->
						</div>	
					</div>
				</div>
			</form>
		</div> 
	</div>

	
<?php  
}
?>
	
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
	<script src="controllers/signin/script_login.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<!-- <script src="../../controllers/signatureDocument/script_signature.js"></script> -->
	<script>
		$(document).ready(function() {
            function disableBack() {
                window.history.forward()
            }
            window.onload = disableBack();
            window.onpageshow = function(e) {
                if (e.persisted)
                    disableBack();
            }

                 
        });
	</script>
</body>
</html>