<?php date_default_timezone_set("America/Mexico_City"); ?>
<?php session_start();

if (isset($_SESSION['user_name']))
{
	echo '<script>location.href = "#";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">
<style>	
	@media print {
    .oculto-impresion,
    .oculto-impresion * {
        display: none !important;
    }
	@media print {
    .mostrar-impresion,
    .mostrar-impresion * {
        display: inline !important;
    }
	}
	@media print {
    @page { margin: 0;
     size: auto; }
	}
}
</style>
<head>
	<meta charset="UTF-8">
	<title>SISTE - Registro del Título</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../assets/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
	<link rel="stylesheet" href="../../assets/css/estilos.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
	<!-- <div class="loader">
	    <img src="../../assets/loading.gif" alt="Cargando...">
	</div> -->

	<nav class="navbar navbar-light bg-nav">
		<!--<div class="container">
		<?php if(isset($_SESSION['username'])) { 
		?>
		  	<span class="navbar-brand mb-0 h1 text-white">Usuario: <span class="ctrl-control" id="user-name" ><?php echo $_SESSION['username']; ?></span></span>
		<?php
		} ?>

			<span style="color: white; font-weight: bold; font-size: 20px;">actualizar del Título</span>

		  	<button class="btn btn-outline-light my-2 my-sm-0" type="button" id='btnLogout'>Cerrar Sesión</button>
		</div>-->
		<div class="container">
		<label>
			<a><img src="../../assets/images/siste_4.png" style="max-width:100%;width: 230px; height:70px;"></a>
		</label>
		<?php if(isset($_SESSION['username'])) {
		?>
			  <span class="align:center" style="color: white; font-weight: bold; font-size: 20px; ">Sistema de Titulación Electrónica</span>
			  <ul class="nav align-left">
				<li><a class="dropdown-item " id="navbarDropdown1" role="menu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="ctrl-control  h5 text-white text-align-right" id="username" ><i class="fas fa-user" aria-hidden="true"></i>
				<div id="user" hidden ><?php echo $_SESSION['username'];?></div>
					<?php 
					echo $_SESSION['username'];
					?>   
					<i class="fa-solid fa-caret-down"></i></a></span>
					<ul>
						<li><a id='btnLogout' ><span class="ctrl-control  h6 text-white" >Cerrar Sesión</span></a></li>
						
					</ul>
				</li>
			</ul>
		<?php
		} ?>
		</div>
	</nav>

	<div class="container mt-5">
		<div id="new">
			<div class = "oculto-impresion" style="text-align: center; border-bottom: 3px solid #cecece; margin-bottom: 30px;">
				<h3>Actualizar registro</h3>	
			</div>
			<div class="form-group" align="center">
				<div class="mostrar-impresion" style="text-align: center; border-bottom: 3px solid #cecece;">	
					<h1 style="display: none;">Expediente</h1>	
					<br><br>
				</div>	
			</div>	
			<div class="form-group">
				<div class="row">
						<div class="col-sm-3">
							<label for="curp">CURP</label>
							<input type="text" class="form-control text-uppercase" id="curp" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" >
						</div>
						<div class="col-sm-3">
							<label for="name"><span class="text-danger">* </span>Nombre</label>
							<input type="text" class="form-control" id="name" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
						</div>
						<div class="col-sm-3">
							<label for="surname"><span class="text-danger">* </span>Primer Apellido</label>
							<input type="text" class="form-control" id="surname" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
						</div>
						<div class="col-sm-3">
							<label for="second-surname"><span class="text-danger">* </span>Segundo Apellido</label>
							<input type="text" class="form-control" id="second-surname" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
						</div>					
					</div> 
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-8">
							<label for="email"><span class="text-danger">* </span>Correo electrónico personal</label>
							<input type="text" class="form-control" id="email" >
						</div>		
						<div class="col-sm-4">
							<label for="controlinvoice"><span class="text-danger">* </span>Folio del título</label>
							<input type="text" class="form-control" id="controlinvoice">
						</div>															
					</div>
				</div>				
				<br>
				<div style="text-align: center; border-bottom: 3px solid #cecece; margin-bottom: 30px;">
					<h3>Carrera de egreso</h3>				
				</div>	
				<div class="form-group">







					<div class="row">
						<div class="col-lg-12 col-md-12 col-md-12">
							<div class="form-group">
								<label for="institution"><span class="text-danger">* </span>Institución</label>
								<select name="institution" id="institution" class="form-control">
									<option value="null" selected disabled>Seleccione una institución</option>
								</select>
							</div>
						</div>
					</div>



					<div class="row">
						<div class="col-lg-4">
							<div class="form-group">
								<label for="program-type"><span class="text-danger">* </span>Tipo de programa</label>
								<select name="program" id="program" class="form-control" onchange="courses();">
									<option value='null' selected disabled>Seleccione una opción</option>
									<option value="1">MAESTRÍA</option>
									<option value="2">DOCTORADO</option>
								</select>
							</div>
						</div>





						<div class="col-lg-8">
							<div class="form-group">
								<label for="courses"><span class="text-danger">* </span>Área de Adscripción</label>
								<select name="courses" id="course" class="form-control" >
									<option value="null" selected disabled>Seleccione su área</option> 
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<label for="date-start"><span class="text-danger">* </span>Fecha de inicio</label>
							<input type="date" class="form-control" id="date-start">
						</div>
						<div class="col-sm-4">
							<label for="date-end"><span class="text-danger">* </span>Fecha de terminación</label>
							<input type="date" class="form-control" id="date-end">
						</div>
						<div class="col-sm-4">
						<label for="authorization"><span class="text-danger">* </span>Autorización de reconocimiento</label>
							<select name="authorization" id="authorization" class="form-control">
								<option value='null' selected disabled >Seleccione una opción</option>
								<option value="8">DECRETO DE CREACIÓN</option>
							</select>
						</div>										
					</div>
				</div>
				<br>
				<div style="text-align: center; border-bottom: 3px solid #cecece; margin-bottom: 30px;">
				<h3>Información de expedición</h3>				
			</div>			
			<div class="form-group">
				<div class="row">
						<div class="col-sm-4">
							<label for="date-expedition"><span class="text-danger">* </span>Fecha de expedición</label>
							<input type="date" class="form-control" id="date-expedition">
						</div>
						<!--######################################################################## CAMBIO 12/09/22 ####################################-->
						<div class="col-sm-4">
							<label for="degree-modality"><span class="text-danger">* </span>Modalidad de titulación</label>
							<select name="degree-modality" id="degree-modality" class="form-control" onchange="ocultar();">
								<option value='null' selected disabled >Seleccione una opción</option>
								<option value="1">POR TESIS</option>
								<option value="2">POR PROMEDIO</option>								
								<option value="6">TESINA</option>
								<option value="6">LIBRO DE TEXTO</option>
								<option value="6">CAPÍTULO DE LIBRO PUBLICADO</option>
								<option value="6">MATERIAL DIDÁCTICO Y MULTIMEDIA</option>
								<option value="6">PORTAFOLIO PROFESIONAL DE EVIDENCIAS</option>
								<option value="6">ARTÍCULO ARBITRADO EN REVISTA</option>
							</select>
						</div>
						<div class="col-sm-4" id="professionalExam" style="display: none">
							<label for="date-exam"><span class="text-danger">*</span>Fecha de examen profesional</label>
							<input type="date" class="form-control" id="date-exam">
						</div>
						<div class="col-sm-4" id="exemptionDate" style="display: none">
							<label for="date-exemption"><span class="text-danger">*</span>Fecha de exención</label>
							<input type="date" class="form-control" id="date-exemption">
						</div>	
						<!--#########################################################################################################################################-->									
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-4">
							<label for="social-service"><span class="text-danger">* </span>Servicio social</label>
							<select name="social-service" id="social-service" class="form-control">
								<option value='null' selected disabled >Seleccione una opción</option>
								<option value="0">NO APLICA</option>
							</select>
						</div>		
						<div class="col-sm-4">
							<label for="social-service-legal"><span class="text-danger">* </span>Fundamento legal del servicio social</label>
							<select name="social-service-legal" id="social-service-legal" class="form-control">
								<option value='null' selected disabled >Seleccione una opción</option>
								<option value="5">NO APLICA</option>
							</select>
						</div>	
						<div class="col-sm-4">
							<label for="expedition-state"><span class="text-danger">* </span>Entidad Federativa</label>
							<select name="expedition-state" id="expedition-state" class="form-control">
								<option value='null' selected disabled >Seleccione una opción</option>								
							</select>
						</div>															
					</div>
				</div>				
				<br>
				<div style="text-align: center; border-bottom: 3px solid #cecece; margin-bottom: 30px;">
					<h3>Antecedente escolar del alumno</h3>				
				</div>	
				<div class="form-group">
					<div class="row">
						<div class="col-sm-9">
							<label for="antecedent-institution"><span class="text-danger">* </span>Institución de procedencia</label>
							<input type="text" class="form-control" id="antecedent-institution" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
						</div>						
						<div class="col-sm-3">
						<label for="antecedent-type-study"><span class="text-danger">* </span>Tipo de estudio</label>
							<select name="antecedent-type-study" id="antecedent-type-study" class="form-control">
								<option value='null' selected disabled >Seleccione una opción</option>
								<option value="1">MAESTRÍA</option>
								<option value="2">LICENCIATURA</option> 
							</select>
						</div>																					
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-4">
							<label for="cedula"><span class="text-danger">* </span>Cédula Profesional</label>
							<input type="text" class="form-control" id="cedula">
						</div>						
						<div class="col-sm-4">
							<label for="antecedent-finich-date"><span class="text-danger">* </span>Fecha de terminación</label>
							<input type="date" class="form-control" id="antecedent-finich-date">
						</div>
						<div class="col-sm-4">
						<label for="antecedent-state"><span class="text-danger">* </span>Entidad Federativa</label>
							<select name="antecedent-state" id="antecedent-state" class="form-control"  >
								<option value='null' selected disabled >Seleccione la entidad</option>
							</select>
						</div>										
					</div>
				</div>
				<br>

				<div class="form-group oculto-impresion" align="center" >
					<button type="button" class="btn btn-secondary w-20" id="cancel" onClick="history.go(-1);" >Cancelar</button>
					<button type="button" class="btn btn-primary w-10" id="print-info" onclick="imprimir()">Imprimir</button>	
					<button type="button" class="btn btn-success w-20" id="save-exam" onclick="updateRegister()">Actualizar</button>
				</div>

				<div class="form-group" align="center">
					<div class="mostrar-impresion ">
						<br><br>
						<h4 style="display: none;">_____________________</h4>
						<br>
						<h4 style="display: none;">Nombre, firma y fecha </h4>
						<br><br>
						<h4 style="display: none;">Los datos del expediente son correctos y autorizo turnar a firma</h4>					
					</div>
				</div>
				
				
			</div>	
		</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="../../assets/js/bootstrap-select.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="../../controllers/signin/script_login.js"></script>	
	<script src="../../controllers/preregistro/script_preregistro.js"></script>
	<script>
		states();
		
		getRegister();
		institutions();
		coursesAds();
		
	</script>
</body>
</html>