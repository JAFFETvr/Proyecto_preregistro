<?php date_default_timezone_set("America/Mexico_City"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-registro de Titulación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@400;600;700&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        :root {
            --azul-inaoep:   #1a3a5c;
            --azul-medio:    #2a5298;
            --azul-claro:    #d6e4f7;
            --acento:        #c8940a;
            --acento-claro:  #fef3d0;
            --gris-fondo:    #f0f4f8;
            --gris-borde:    #cdd7e3;
            --texto:         #1e2a38;
            --texto-suave:   #5a6a7e;
            --blanco:        #ffffff;
            --exito:         #1a6e3f;
            --exito-fondo:   #d4edda;
            --error:         #c0392b;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: var(--gris-fondo);
            color: var(--texto);
            font-size: 0.93rem;
            min-height: 100vh;
        }

        .navbar-institucional {
            background: var(--azul-inaoep);
            padding: 0.6rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border-bottom: 3px solid var(--acento);
        }

        .navbar-institucional img { height: 52px; width: auto; }

        .wrapper { max-width: 780px; margin: 2.5rem auto 4rem; padding: 0 1rem; }

        .minimal-progress-container { margin-bottom: 2rem; text-align: right; }
        .minimal-progress-text { font-size: 0.75rem; color: var(--texto-suave); margin-bottom: 0.4rem; font-weight: 600; letter-spacing: 0.05em; }
        .minimal-progress-track { height: 3px; background-color: var(--gris-borde); border-radius: 3px; overflow: hidden; }
        .minimal-progress-fill { height: 100%; background-color: var(--azul-inaoep); width: 25%; transition: width 0.4s ease; }

        .form-header { background: var(--azul-inaoep); border-radius: 8px 8px 0 0; padding: 1.6rem 2rem; display: flex; align-items: center; gap: 1.2rem; border-bottom: 4px solid var(--acento); }
        .form-header .icono-header { background: rgba(255,255,255,0.12); border-radius: 50%; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .form-header .icono-header i { font-size: 1.5rem; color: var(--blanco); }
        .form-header h1 { font-family: 'Source Serif 4', serif; font-size: 1.3rem; font-weight: 700; color: var(--blanco); margin: 0; line-height: 1.3; }
        .form-header p { color: rgba(255,255,255,0.75); font-size: 0.82rem; margin: 0.2rem 0 0; }

        .aviso-cuenta { background: var(--blanco); border: 1px solid var(--gris-borde); border-top: none; padding: 0.9rem 2rem; display: flex; align-items: center; gap: 0.7rem; font-size: 0.82rem; color: var(--texto-suave); }
        .aviso-cuenta i { color: var(--azul-medio); font-size: 1rem; }

        .seccion-titulo { background: var(--azul-claro); border-left: 4px solid var(--azul-inaoep); padding: 0.65rem 1.5rem; font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 1rem; color: var(--azul-inaoep); margin: 0; display: flex; align-items: center; gap: 0.6rem; }
        .card-seccion { background: var(--blanco); border: 1px solid var(--gris-borde); border-top: none; padding: 1.5rem 2rem; }

        label { font-weight: 500; font-size: 0.88rem; color: var(--texto); margin-bottom: 0.25rem; }
        label .req { color: var(--error); font-weight: 700; margin-left: 2px; }
        label .opcional { color: var(--texto-suave); font-weight: 400; font-size: 0.78rem; margin-left: 4px; }

        .form-control, .custom-select { border: 1px solid var(--gris-borde); border-radius: 5px; padding: 0.55rem 0.85rem; font-size: 0.91rem; color: var(--texto); background-color: var(--blanco); transition: border-color 0.2s, box-shadow 0.2s; }
        .form-control:focus, .custom-select:focus { border-color: var(--azul-medio); box-shadow: 0 0 0 3px rgba(42,82,152,0.12); outline: none; }
        .form-control.is-invalid, .custom-select.is-invalid { border-color: var(--error); }
        .is-invalid-radio { border: 1px solid var(--error) !important; border-radius: 5px; padding: 5px; background-color: #fdf2f2; }
        .is-invalid-file { border-color: var(--error) !important; background-color: #fdf2f2 !important; }
        
        .invalid-feedback { display: block; font-size: 0.8rem; color: var(--error); margin-top: 0.2rem; display: none; }
        .is-invalid ~ .invalid-feedback { display: block; }
        .field-hint { font-size: 0.78rem; color: var(--texto-suave); margin-top: 0.25rem; }

        .upload-area { border: 2px dashed var(--gris-borde); border-radius: 6px; padding: 1rem 1.2rem; background: #fafbfd; transition: border-color 0.2s, background 0.2s; cursor: pointer; }
        .upload-area:hover, .upload-area.drag-over { border-color: var(--azul-medio); background: var(--azul-claro); }
        .upload-area .upload-icon { font-size: 1.4rem; color: var(--azul-medio); margin-bottom: 0.3rem; }
        .upload-area .upload-label { font-size: 0.85rem; color: var(--azul-medio); font-weight: 500; cursor: pointer; }
        .upload-area .upload-hint { font-size: 0.76rem; color: var(--texto-suave); }
        .upload-area input[type="file"] { display: none; }

        .nombre-archivo { margin-top: 0.4rem; }
        .file-preview-wrapper { display: flex; align-items: center; justify-content: space-between; background: #f8f9fa; border: 1px solid var(--gris-borde); padding: 0.6rem 0.8rem; border-radius: 6px; margin-top: 0.5rem; }
        .file-preview-info { display: flex; align-items: center; gap: 0.6rem; color: var(--azul-medio); text-decoration: none; overflow: hidden; }
        .file-preview-info:hover { text-decoration: underline; color: var(--azul-inaoep); }
        .file-name-text { display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; font-weight: 500; font-size: 0.85rem; }
        @media (min-width: 768px) { .file-name-text { max-width: 250px; } }
        .file-size-text { font-size: 0.75rem; color: var(--texto-suave); white-space: nowrap; }
        .btn-quitar { background: transparent; border: none; color: var(--error); cursor: pointer; padding: 0.3rem; font-size: 1rem; border-radius: 4px; transition: background 0.2s; }
        .btn-quitar:hover { background: #fdf2f2; color: #a93226; }

        .nota-info { background: var(--acento-claro); border: 1px solid #e6c05a; border-radius: 5px; padding: 0.7rem 1rem; font-size: 0.82rem; color: #7a5a00; display: flex; gap: 0.6rem; align-items: flex-start; margin-bottom: 1.2rem; }
        #bloque-curp { background: #f7faff; border: 1px solid var(--azul-claro); border-radius: 6px; padding: 1rem; margin-top: 0.8rem; }

        .btn-enviar, .btn-secundario { border: none; border-radius: 6px; padding: 0.8rem 2.5rem; font-size: 0.97rem; font-weight: 600; cursor: pointer; transition: background 0.2s, transform 0.1s; }
        .btn-enviar { background: var(--azul-inaoep); color: var(--blanco); }
        .btn-enviar:hover { background: var(--azul-medio); transform: translateY(-1px); }
        .btn-secundario { background: #e2e8f0; color: var(--texto); }
        .btn-secundario:hover { background: #cbd5e1; }

        .step-footer { background: var(--blanco); border: 1px solid var(--gris-borde); border-top: none; border-radius: 0 0 8px 8px; padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; }

        .form-step { display: none; animation: fadeIn 0.4s ease-in-out; }
        .form-step.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .opciones-radio { display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.3rem; }
        .opcion-radio { display: flex; align-items: center; gap: 0.7rem; padding: 0.55rem 0.9rem; border: 1px solid var(--gris-borde); border-radius: 5px; cursor: pointer; font-size: 0.88rem; }
        .opcion-radio:hover { background: var(--azul-claro); border-color: var(--azul-medio); }
        .opcion-radio input[type="radio"] { width: 16px; height: 16px; accent-color: var(--azul-inaoep); }

        .divisor { border: none; border-top: 1px solid var(--gris-borde); margin: 1.2rem 0; }
        
        #loader-envio { display: none; text-align: center; padding: 1rem 0; }
        .spinner-inaoep { width: 36px; height: 36px; border: 4px solid var(--azul-claro); border-top-color: var(--azul-inaoep); border-radius: 50%; animation: girar 0.8s linear infinite; margin: 0 auto 0.5rem; }
        @keyframes girar { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<nav class="navbar-institucional">
    <a href="#">
        <img src="../assets/images/siste_4.png" alt="SISTE">
    </a>
</nav>

<div class="wrapper">
    <div class="minimal-progress-container" id="progress-container">
        <div class="minimal-progress-text" id="progress-text">Paso 1 / 4</div>
        <div class="minimal-progress-track">
            <div class="minimal-progress-fill" id="progress-fill"></div>
        </div>
    </div>

    <div class="form-header" id="main-header">
        <div class="icono-header"><i class="fa-solid fa-graduation-cap"></i></div>
        <div>
            <h1>Dirección de Formación Académica</h1>
            <p>Solicitud de titulación</p>
        </div>
    </div>

    <div class="aviso-cuenta" id="main-aviso">
        <i class="fa-solid fa-circle-info"></i>
        Asegúrate de que toda la información sea correcta antes de enviar.
    </div>

    <form id="form-preregistro" novalidate enctype="multipart/form-data">
        
        <div id="step-1" class="form-step active">
            <div class="seccion-titulo">
                <i class="fa-solid fa-user"></i> Datos personales
            </div>
            <div class="card-seccion">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="professional_name">Nombre(s) <span class="req">*</span></label>
                        <input type="text" class="form-control" id="professional_name" name="professional_name" maxlength="100" style="text-transform:uppercase;" required>
                        <div class="field-hint">En mayúsculas, sin acentos</div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="professional_surname">Apellido paterno <span class="req">*</span></label>
                        <input type="text" class="form-control" id="professional_surname" name="professional_surname" maxlength="100" style="text-transform:uppercase;" required>
                        <div class="field-hint">En mayúsculas, sin acentos</div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="professional_secondsurname">Apellido materno</label>
                        <input type="text" class="form-control" id="professional_secondsurname" name="professional_secondsurname" maxlength="100" style="text-transform:uppercase;">
                        <div class="field-hint">En mayúsculas, sin acentos</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="professional_email">Correo electrónico <span class="req">*</span></label>
                    <input type="email" class="form-control" id="professional_email" name="professional_email" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label>Nacionalidad <span class="req">*</span></label>
                    <div class="opciones-radio">
                        <label class="opcion-radio">
                            <input type="radio" name="nacionalidad" value="Mexicana" onchange="toggleCurp()" required>
                            <span>Mexicana</span>
                        </label>
                        <label class="opcion-radio">
                            <input type="radio" name="nacionalidad" value="Extranjera" onchange="toggleCurp()" required>
                            <span>Extranjera</span>
                        </label>
                    </div>
                </div>

                <div id="bloque-curp" style="display:none;">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="professional_curp">CURP <span class="req">*</span></label>
                            <input type="text" class="form-control" id="professional_curp" name="professional_curp" minlength="18" maxlength="18" style="text-transform:uppercase; letter-spacing:0.08em;">
                            <div class="field-hint">En mayúsculas, exactamente 18 caracteres</div>
                            <div class="invalid-feedback">La CURP debe tener exactamente 18 caracteres.</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Adjuntar CURP <span class="req">*</span></label>
                            <div class="upload-area" onclick="document.getElementById('archivo_curp').click()">
                                <div class="text-center">
                                    <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                    <div class="upload-label">Seleccionar archivo</div>
                                    <div class="upload-hint">PDF o imagen · máx. 10 MB</div>
                                </div>
                                <input type="file" id="archivo_curp" name="archivo_curp" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="nombre-archivo" id="nombre-archivo_curp"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="step-footer">
                <div></div>
                <button type="button" class="btn-enviar" onclick="avanzarPaso(1, 2)">Siguiente <i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>

        <div id="step-2" class="form-step">
            <div class="seccion-titulo" style="margin-top:0;">
                <i class="fa-solid fa-scroll"></i> Título que solicita
            </div>
            <div class="card-seccion">
                <div class="form-group">
                    <label>Grado del título que solicita <span class="req">*</span></label>
                    <div class="opciones-radio">
                        <label class="opcion-radio"><input type="radio" name="course_type" value="Maestria" required><span>Maestría</span></label>
                        <label class="opcion-radio"><input type="radio" name="course_type" value="Doctorado" required><span>Doctorado</span></label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="course_cvecourse">Área del título que solicita <span class="req">*</span></label>
                    <select class="form-control custom-select" id="course_cvecourse" name="course_cvecourse" required>
                        <option value="null" selected disabled>Selecciona área</option>
                        <option value="Astrofisica">Astrofísica</option>
                        <option value="Optica">Óptica</option>
                        <option value="Electronica">Electrónica</option>
                        <option value="Ciencias Computacionales">Ciencias Computacionales</option>
                        <option value="Ciencia y Tecnologia del Espacio">Ciencia y Tecnología del Espacio</option>
                        <option value="Ciencias y Tecnologias Biomedicas">Ciencias y Tecnologías Biomédicas</option>
                        <option value="Ciencias y Tecnologias de Seguridad">Ciencias y Tecnologías de Seguridad</option>
                        <option value="Ensenanza de Ciencias Exactas">Enseñanza de Ciencias Exactas</option>
                    </select>
                </div>

                <hr class="divisor">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Fecha de inicio de grado <span class="req">*</span></label>
                        <input type="date" class="form-control" id="course_startdate" name="course_startdate" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha de defensa de tesis, examen o entrevista <span class="req">*</span></label>
                        <input type="date" class="form-control" id="expedition_dateprofessionalexam" name="expedition_dateprofessionalexam" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Modalidad de titulación <span class="req">*</span></label>
                    <select class="form-control custom-select" id="expedition_iddegreemodality" name="expedition_iddegreemodality" required>
                        <option value="null" selected disabled>Seleccione una opción</option>
                        <option value="Tesis">Tesis (Astrófísica, Óptica, Electrónica, C. Computacionales, Espacio, Biomédicas, Seguridad, MECE)</option>
                        <option value="Tesina">Tesina (Solo MECE)</option>
                        <option value="Promedio">Promedio (Solo MECE)</option>
                        <option value="Presentacion y Defensa">Presentación y Defensa de intervención con Material Didáctico (Solo MECE)</option>
                        <option value="Portafolio de Evidencias">Portafolio de Evidencias</option>
                    </select>
                </div>
            </div>
            <div class="step-footer">
                <button type="button" class="btn-secundario" onclick="retrocederPaso(1)"><i class="fa-solid fa-arrow-left"></i> Atrás</button>
                <button type="button" class="btn-enviar" onclick="avanzarPaso(2, 3)">Siguiente <i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>

        <div id="step-3" class="form-step">
            <div class="seccion-titulo" style="margin-top:0;">
                <i class="fa-solid fa-building-columns"></i> Antecedentes Académicos
            </div>
            <div class="card-seccion">
                <div class="nota-info">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>Si el título de grado que solicitas es maestría, tendrás que adjuntar los documentos de licenciatura. Si el título de grado que solicitas es de doctorado, tendrás que adjuntar los documentos de maestría.</div>
                </div>

                <div class="form-group">
                    <label>Adjuntar Certificado del grado anterior al que solicita <span class="req">*</span></label>
                    <div class="upload-area" onclick="document.getElementById('archivo_certificado').click()">
                        <div class="text-center">
                            <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                            <div class="upload-label">Seleccionar archivo</div>
                            <div class="upload-hint">Sube 1 archivo compatible: PDF o imagen. Tamaño máximo: 10 MB</div>
                        </div>
                        <input type="file" id="archivo_certificado" name="archivo_certificado" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="nombre-archivo" id="nombre-archivo_certificado"></div>
                </div>

                <div class="form-group">
                    <label>Adjuntar Acta de examen del grado anterior al que solicita <span class="req">*</span></label>
                    <div class="upload-area" onclick="document.getElementById('archivo_acta_examen').click()">
                        <div class="text-center">
                            <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                            <div class="upload-label">Seleccionar archivo</div>
                            <div class="upload-hint">Sube 1 archivo compatible: PDF o imagen. Tamaño máximo: 10 MB</div>
                        </div>
                        <input type="file" id="archivo_acta_examen" name="archivo_acta_examen" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="nombre-archivo" id="nombre-archivo_acta_examen"></div>
                </div>

                <div class="form-group">
                    <label>Adjuntar Título de grado del grado anterior al que solicita <span class="req">*</span></label>
                    <div class="upload-area" onclick="document.getElementById('archivo_titulo_grado').click()">
                        <div class="text-center">
                            <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                            <div class="upload-label">Seleccionar archivo</div>
                            <div class="upload-hint">Sube 1 archivo compatible: PDF o imagen. Tamaño máximo: 10 MB</div>
                        </div>
                        <input type="file" id="archivo_titulo_grado" name="archivo_titulo_grado" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="nombre-archivo" id="nombre-archivo_titulo_grado"></div>
                </div>

                <hr class="divisor">

                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label>No. de Cédula profesional <span class="req">*</span></label>
                        <input type="text" class="form-control" id="antecedent_document" name="antecedent_document" maxlength="8" inputmode="numeric" required>
                    </div>
                    <div class="form-group col-md-7">
                        <label>Adjuntar Cédula profesional <span class="req">*</span></label>
                        <div class="upload-area" onclick="document.getElementById('archivo_cedula').click()">
                            <div class="text-center">
                                <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                <div class="upload-label">Seleccionar archivo</div>
                                <div class="upload-hint">Sube 1 archivo compatible: PDF o imagen. Tamaño máximo: 10 MB</div>
                            </div>
                            <input type="file" id="archivo_cedula" name="archivo_cedula" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        <div class="nombre-archivo" id="nombre-archivo_cedula"></div>
                    </div>
                </div>
            </div>
            
            <div class="step-footer flex-wrap">
                <button type="button" class="btn-secundario" onclick="retrocederPaso(2)"><i class="fa-solid fa-arrow-left"></i> Atrás</button>
                <div class="d-flex align-items-center flex-column">
<div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" style="margin-bottom: 0.5rem;"></div>                    <div id="recaptcha-error" class="invalid-feedback text-center mt-0 mb-2">Por favor, verifica que no eres un robot.</div>
                    <button type="button" class="btn-enviar" id="btn-enviar" onclick="finalizarFormulario()"><i class="fa-solid fa-paper-plane"></i> Enviar</button>
                </div>
            </div>
        </div>

        <div id="step-4" class="form-step">
            <div class="card-seccion text-center" style="padding: 4rem 2rem; border-radius: 8px; border: 1px solid var(--gris-borde);">
                <div style="font-size: 3.5rem; color: var(--exito); margin-bottom: 1.5rem;"><i class="fa-solid fa-circle-check"></i></div>
                <h2 style="color: var(--azul-inaoep); font-family: 'Source Serif 4', serif; margin-bottom: 1.5rem; font-size: 1.8rem; font-weight: 700;">Gracias, en breve nos comunicaremos para informarte el avance de tu título.</h2>
                <p style="color: var(--texto-suave); font-size: 1rem; line-height: 1.6; max-width: 600px; margin: 0 auto;">
                    Si tienes alguna duda, por favor, contacta a la responsable de la Oficina de Titulación Lic. Militza Macia Garcini al correo: <a href="mailto:titulacion@inaoep.mx" style="color: var(--azul-medio); font-weight: 600;">titulacion@inaoep.mx</a> o bien con la Mtra. Yenni Carpinteyro al correo: <a href="mailto:serviciosescolares@inaoep.mx" style="color: var(--azul-medio); font-weight: 600;">serviciosescolares@inaoep.mx</a>
                </p>
            </div>
        </div>

        <div id="loader-envio">
            <div class="spinner-inaoep"></div>
            <p style="color:var(--texto-suave); font-size:0.85rem;">Enviando tu información, por favor espera…</p>
        </div>

    </form>
</div>

<script>
    window.CONTROLLER_URL = '../controllers/preregistro/controller_preregistro.php';
</script>
<script src="datos_Precargados/datos_precargados.js"></script>
<script src="../controllers/preregistro/script_preregistro.js"></script>
</body>
</html>