<?php 
date_default_timezone_set("America/Mexico_City"); 
session_start();

if (!isset($_SESSION['user_name'])) {
    echo '<script>location.href = "../../index.php";</script>';
    exit;
}
$id_titledata = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_titledata === 0) {
    echo '<script>location.href = "secretaria.php";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SISTE - Completar Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>
<body>
    <nav class="navbar navbar-light bg-nav" style="background: #1a3a6b;">
        <div class="container">
            <label>
                <a><img src="../../assets/images/siste_4.png" style="max-width:100%;width: 230px; height:70px;"></a>
            </label>
            <span style="color: white; font-weight: bold; font-size: 20px;">Completar Registro de Titulación</span>
            <ul class="nav align-left">
                <li><a class="dropdown-item" id="navbarDropdown1" role="menu" data-bs-toggle="dropdown"><span class="ctrl-control h5 text-white" id="username" ><i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?> <i class="fa-solid fa-caret-down"></i></span></a>
                    <ul>
                        <li><a id='btnLogout'><span class="ctrl-control h6 text-white">Cerrar Sesión</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <form id="form-completar">
            <input type="hidden" id="id_titledata" name="id_titledata" value="<?php echo $id_titledata; ?>">
            
            <div style="text-align: center; border-bottom: 3px solid #cecece; margin-bottom: 30px;">
                <h3>Información del pre-registro</h3>   
            </div>
            
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                        <label for="professional_curp">CURP</label>
                        <input type="text" class="form-control text-uppercase" id="professional_curp" name="professional_curp" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label for="professional_name">Nombre</label>
                        <input type="text" class="form-control text-uppercase" id="professional_name" name="professional_name" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label for="professional_surname">Primer Apellido</label>
                        <input type="text" class="form-control text-uppercase" id="professional_surname" name="professional_surname" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label for="professional_secondsurname">Segundo Apellido</label>
                        <input type="text" class="form-control text-uppercase" id="professional_secondsurname" name="professional_secondsurname" readonly>
                    </div>                  
                </div> 
            </div>
            
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-8">
                        <label for="professional_email">Correo electrónico personal</label>
                        <input type="email" class="form-control" id="professional_email" name="professional_email" readonly>
                    </div>      
                    <div class="col-sm-4">
                        <label for="controlinvoice"><span class="text-danger">* </span>Folio del título</label>
                        <input type="text" class="form-control" id="controlinvoice" name="controlinvoice" required>
                    </div>                                                          
                </div>
            </div>              
            
            <br>
            <div style="text-align: center; border-bottom: 3px solid #cecece; margin-bottom: 30px;">
                <h3>Carrera de egreso</h3>              
            </div>  
            
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="institution_cveinstitution"><span class="text-danger">* </span>Institución</label>
                            <select name="institution_cveinstitution" id="institution_cveinstitution" class="form-control" required>
                                <option value="null" selected disabled>Seleccione una institución</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="course_type"><span class="text-danger">* </span>Tipo de programa</label>
                            <select name="course_type" id="course_type" class="form-control" required>
                                <option value='null' selected disabled>Seleccione una opción</option>
                                <option value="1">MAESTRÍA</option>
                                <option value="2">DOCTORADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="course_cvecourse"><span class="text-danger">* </span>Área de Adscripción</label>
                            <select name="course_cvecourse" id="course_cvecourse" class="form-control" required>
                                <option value="null" selected disabled>Seleccione su área</option> 
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-4">
                        <label for="course_startdate">Fecha de inicio</label>
                        <input type="date" class="form-control" id="course_startdate" name="course_startdate" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label for="course_finishdate"><span class="text-danger">* </span>Fecha de terminación</label>
                        <input type="date" class="form-control" id="course_finishdate" name="course_finishdate" required>
                    </div>
                    <div class="col-sm-4">
                        <label for="course_idreconnaissanceauthorization"><span class="text-danger">* </span>Autorización de reconocimiento</label>
                        <select name="course_idreconnaissanceauthorization" id="course_idreconnaissanceauthorization" class="form-control" required>
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
                        <label for="expedition_date"><span class="text-danger">* </span>Fecha de expedición</label>
                        <input type="date" class="form-control" id="expedition_date" name="expedition_date" required>
                    </div>
                    <div class="col-sm-4">
                        <label for="expedition_degreemodality">Modalidad de titulación</label>
                        <input type="text" class="form-control" id="expedition_degreemodality" name="expedition_degreemodality" readonly>
                    </div>
                    <div class="col-sm-4">
                        <label for="expedition_dateprofessionalexam">Fecha de examen/defensa</label>
                        <input type="date" class="form-control" id="expedition_dateprofessionalexam" name="expedition_dateprofessionalexam" readonly>
                    </div>                                    
                </div>
            </div>
            
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="expedition_socialservice"><span class="text-danger">* </span>Servicio social</label>
                        <select name="expedition_socialservice" id="expedition_socialservice" class="form-control" required>
                            <option value='null' selected disabled >Seleccione una opción</option>
                            <option value="0">NO APLICA</option>
                        </select>
                    </div>      
                    <div class="col-sm-4">
                        <label for="expedition_idlegalbasissocialservice"><span class="text-danger">* </span>Fundamento legal del servicio social</label>
                        <select name="expedition_idlegalbasissocialservice" id="expedition_idlegalbasissocialservice" class="form-control" required>
                            <option value='null' selected disabled >Seleccione una opción</option>
                            <option value="5">NO APLICA</option>
                        </select>
                    </div>  
                    <div class="col-sm-4">
                        <label for="expedition_idstate"><span class="text-danger">* </span>Entidad Federativa</label>
                        <select name="expedition_idstate" id="expedition_idstate" class="form-control" required>
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
                        <label for="antecedent_institutionorigin"><span class="text-danger">* </span>Institución de procedencia</label>
                        <input type="text" class="form-control text-uppercase" id="antecedent_institutionorigin" name="antecedent_institutionorigin" required>
                    </div>                      
                    <div class="col-sm-3">
                        <label for="antecedent_idtypestudy"><span class="text-danger">* </span>Tipo de estudio</label>
                        <select name="antecedent_idtypestudy" id="antecedent_idtypestudy" class="form-control" required>
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
                        <label for="antecedent_document">Cédula Profesional</label>
                        <input type="text" class="form-control" id="antecedent_document" name="antecedent_document" readonly>
                    </div>                      
                    <div class="col-sm-4">
                        <label for="antecedent_finishdate"><span class="text-danger">* </span>Fecha de terminación</label>
                        <input type="date" class="form-control" id="antecedent_finishdate" name="antecedent_finishdate" required>
                    </div>
                    <div class="col-sm-4">
                        <label for="antecedent_idstate"><span class="text-danger">* </span>Entidad Federativa</label>
                        <select name="antecedent_idstate" id="antecedent_idstate" class="form-control" required>
                            <option value='null' selected disabled >Seleccione la entidad</option>
                        </select>
                    </div>                                      
                </div>
            </div>
            
            <br>
            <div class="form-group text-center mt-4">
                <button type="button" class="btn btn-secondary mr-2" id="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn btn-success" id="btn-guardar">Guardar y Pasar a Creados</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
<script src="../../controllers/secretaria/datos_precargados_secretaria.js"></script>
    <script src="../../controllers/secretaria/script_completar.js"></script>
</html>