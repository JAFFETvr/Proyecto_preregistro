<?php
date_default_timezone_set("America/Mexico_City");

require_once __DIR__ . '/../../models/model_preregistro.php';

define('UPLOAD_DIR', __DIR__ . '/../../uploads/preregistro/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024);

class actualizarController{
    private $actualizar;

    public function __construct(){}

    public function getInstitution(){
        $this->actualizar = new actualizar();
        $data = $this->actualizar->getInstitution();
        echo json_encode($data);
    }

    public function getArea(){
        $this->actualizar = new actualizar();
        $data = $this->actualizar->getArea($_POST["program"]);
        echo json_encode($data);
    }

    public function getState(){
        $this->actualizar = new actualizar();
        $data = $this->actualizar->getState();
        echo json_encode($data);
    }

    public function getRegister(){
        $this->actualizar = new actualizar();
        $idTitleData = isset($_POST["id_titledata"]) ? (int)$_POST["id_titledata"] : 0;
        if ($idTitleData <= 0) { echo json_encode([]); return; }
        $data = $this->actualizar->getRegister($idTitleData);
        echo json_encode($data);
    }

    public function getAreaAd(){
        $this->actualizar = new actualizar();
        $idTitleData = isset($_POST["id_titledata"]) ? (int)$_POST["id_titledata"] : 0;
        if ($idTitleData <= 0) { echo json_encode([]); return; }
        $data = $this->actualizar->getAreaAd($idTitleData);
        echo json_encode($data);
    }

    public function insertRegister(){
        $this->actualizar = new actualizar();
        $data = $this->actualizar->insertRegister(
            $_POST["controlinvoice"], $_POST["professional_curp"],
            $_POST["professional_name"], $_POST["professional_surname"],
            $_POST["professional_secondsurname"], $_POST["professional_email"],
            $_POST["institution_cveinstitution"], $_POST["institution_nameinstitution"],
            $_POST["course_cvecourse"], $_POST["course_name"],
            $_POST["course_startdate"], $_POST["course_finishdate"],
            $_POST["course_idreconnaissanceauthorization"], $_POST["course_reconnaissanceauthorization"],
            $_POST["expedition_date"], $_POST["expedition_iddegreemodality"],
            $_POST["expedition_degreemodality"], $_POST["expedition_degreemodality_details"],
            $_POST["expedition_dateprofessionalexam"], $_POST["expedition_socialservice"],
            $_POST["expedition_idlegalbasissocialservice"], $_POST["expedition_legalbasissocialservice"],
            $_POST["expedition_idstate"], $_POST["expedition_state"],
            $_POST["antecedent_institutionorigin"], $_POST["antecedent_idtypestudy"],
            $_POST["antecedent_typestudy"], $_POST["antecedent_idstate"],
            $_POST["antecedent_state"], $_POST["antecedent_finishdate"],
            $_POST["antecedent_document"]
        );
        echo json_encode($data);
    }

    public function traceDocument(){
        $this->actualizar = new actualizar();
        $data = $this->actualizar->traceDocument(
            $_POST["id_titledata"], $_POST["controlinvoice"], $_POST["user"]
        );
        echo ($data);
    }

    public function traceUpdateInfo(){
        $this->actualizar = new actualizar();
        $data = $this->actualizar->traceUpdateInfo(
            $_POST["registerinfo_id"], $_POST["new_titledata_id"]
        );
        echo json_encode($data);
    }

    public function insertPreregistro(){
        header('Content-Type: application/json');

        $errores = actualizar::validarPreregistro($_POST, $_FILES);
        if (!empty($errores)){
            echo json_encode(['ok' => false, 'errores' => $errores]);
            return;
        }

        $rutas = [];
        $camposArchivo = [
            'archivo_curp',
            'archivo_certificado',
            'archivo_acta_examen',
            'archivo_titulo_grado',
            'archivo_cedula'
        ];

        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

        foreach ($camposArchivo as $campo){
            if (empty($_FILES[$campo]['name'])){
                $rutas[$campo] = null;
                continue;
            }
            $archivo = $_FILES[$campo];
            if ($archivo['error'] !== UPLOAD_ERR_OK){
                echo json_encode(['ok'=>false,'errores'=>[$campo=>'Error al subir el archivo.']]);
                return;
            }
            if ($archivo['size'] > MAX_FILE_SIZE){
                echo json_encode(['ok'=>false,'errores'=>[$campo=>'El archivo supera los 10 MB.']]);
                return;
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $archivo['tmp_name']);
            finfo_close($finfo);
            $allowed = ['application/pdf','image/jpeg','image/png','image/webp'];
            if (!in_array($mime, $allowed)){
                echo json_encode(['ok'=>false,'errores'=>[$campo=>'Solo se aceptan PDF o imágenes (JPG, PNG).']]);
                return;
            }
            $ext    = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $nombre = $campo . '_' . uniqid() . '.' . $ext;
            if (!move_uploaded_file($archivo['tmp_name'], UPLOAD_DIR . $nombre)){
                echo json_encode(['ok'=>false,'errores'=>[$campo=>'No se pudo guardar el archivo. Verifica permisos.']]);
                return;
            }
            $rutas[$campo] = $nombre;
        }

        $modTextoPost = $_POST['expedition_iddegreemodality'] ?? '';
        $mapMod = [
            'Tesis' => 1,
            'Promedio' => 2,
            'Tesina' => 3,
            'Presentacion y Defensa' => 4,
            'Portafolio de Evidencias' => 5
        ];
        $modId = $mapMod[$modTextoPost] ?? 0;

        $modalidades = [
            1 => ['texto' => 'POR TESIS', 'detalle' => 'SIN DETALLES'],
            2 => ['texto' => 'POR PROMEDIO', 'detalle' => 'SIN DETALLES'],
            3 => ['texto' => 'TESINA', 'detalle' => 'TESINA'],
            4 => ['texto' => 'PRESENTACION Y DEFENSA CON MATERIAL DIDACTICO', 'detalle' => 'MATERIAL DIDACTICO Y MULTIMEDIA'],
            5 => ['texto' => 'PORTAFOLIO DE EVIDENCIAS', 'detalle' => 'PORTAFOLIO PROFESIONAL DE EVIDENCIAS'],
        ];
        $modTexto   = $modalidades[$modId]['texto']  ?? 'OTRO';
        $modDetalle = $modalidades[$modId]['detalle'] ?? 'SIN DETALLES';

        $areaPost = $_POST['course_cvecourse'] ?? '';
        $mapAreas = [
            'Astrofisica' => 1,
            'Optica' => 2,
            'Electronica' => 3,
            'Ciencias Computacionales' => 4,
            'Ciencia y Tecnologia del Espacio' => 5,
            'Ciencias y Tecnologias Biomedicas' => 6,
            'Ciencias y Tecnologias de Seguridad' => 7,
            'Ensenanza de Ciencias Exactas' => 8
        ];
        $courseCve = $mapAreas[$areaPost] ?? 0;

        $authId = (int)($_POST['authorization'] ?? 0);
        $authTexto = ($authId === 8) ? 'DECRETO DE CREACION' : '';

        $socialServiceId = (int)($_POST['social_service'] ?? 0);
        $socialServiceLegal = (int)($_POST['social_service_legal'] ?? 0);
        $socialServiceTexto = ($socialServiceId === 0) ? 'NO APLICA' : '';
        $socialLegalTexto = ($socialServiceLegal === 5) ? 'NO APLICA' : '';

        $grado = $_POST['course_type'] ?? '';
        $antTypeId = ($grado === 'Doctorado') ? 1 : 2;
        $antTypeTextos = [1 => 'MAESTRIA', 2 => 'LICENCIATURA'];
        $antTypeTexto  = $antTypeTextos[$antTypeId] ?? '';

        $this->actualizar = new actualizar();
        $id = $this->actualizar->insertPreregistro(
            $_POST['professional_curp']            ?? '',   
            $_POST['professional_name'],                    
            $_POST['professional_surname'],                 
            $_POST['professional_secondsurname']   ?? '',   
            $_POST['professional_email'],                   
            $_POST['nacionalidad']                 ?? '',   
            $_POST['controlinvoice']               ?? '',   
            (int)($_POST['institution']            ?? 0),   
            $_POST['institution_name']             ?? '',   
            $courseCve,                                     
            $_POST['course_cvecourse']             ?? '',   
            $_POST['course_startdate'],                     
            $_POST['date_end']                     ?? '',   
            $authId,                                        
            $authTexto,                                     
            $_POST['date_expedition']              ?? '',   
            $modId,                                         
            $modTexto,                                      
            $modDetalle,                                    
            $_POST['expedition_dateprofessionalexam'],      
            $_POST['expedition_dateexemption']     ?? null, 
            $socialServiceId,                               
            $socialServiceLegal,                            
            $socialServiceTexto,                            
            $socialLegalTexto,                              
            $_POST['expedition_state']             ?? '',   
            $_POST['expedition_state_text']        ?? '',   
            $antTypeId,                                     
            $antTypeTexto,                                  
            $_POST['antecedent_institution']       ?? '',   
            $_POST['antecedent_state']             ?? '',   
            $_POST['antecedent_state_text']        ?? '',   
            $_POST['antecedent_finich_date']       ?? '',   
            $_POST['antecedent_document'],                  
            $_POST['carrera_egreso']               ?? '',   
            $rutas['archivo_curp']                 ?? null, 
            $rutas['archivo_certificado']          ?? '',   
            $rutas['archivo_acta_examen']          ?? '',   
            $rutas['archivo_titulo_grado']         ?? '',   
            $rutas['archivo_cedula']               ?? ''    
        );

        if (is_numeric($id)){
            echo json_encode(['ok' => true, 'id' => (int)$id]);
        } else {
            $msg = is_string($id) ? $id : 'Error al guardar en la base de datos. Intenta de nuevo.';
            echo json_encode(['ok' => false, 'errores' => ['general' => $msg]]);
        }
    }

    public function getPreregistrosPendientes(){
        if (!isset($_SESSION['username'])){ http_response_code(401); return; }
        header('Content-Type: application/json');
        $this->actualizar = new actualizar();
        echo json_encode($this->actualizar->getPreregistrosPendientes());
    }

    public function getPreregistroById(){
        if (!isset($_SESSION['username'])){ http_response_code(401); return; }
        header('Content-Type: application/json');
        $this->actualizar = new actualizar();
        $row = $this->actualizar->getPreregistroById((int)$_POST['id']);
        echo json_encode($row ?: ['error' => 'No encontrado']);
    }

    public function promoverPreregistro(){
        if (!isset($_SESSION['username'])){ http_response_code(401); return; }
        header('Content-Type: application/json');
        $this->actualizar = new actualizar();
        $ok = $this->actualizar->promoverPreregistro(
            (int)$_POST['prereg_id'],
            (int)$_POST['id_titledata']
        );
        echo json_encode(['ok' => $ok]);
    }

    public function getAreaPublico(){
        header('Content-Type: application/json');
        $program = isset($_POST['program']) ? (int)$_POST['program'] : 0;
        if ($program <= 0){ echo json_encode([]); return; }
        $this->actualizar = new actualizar();
        $data = $this->actualizar->getArea($program);
        echo json_encode($data);
    }
}

session_start();
$obj = new actualizarController();

if (isset($_POST["action"])){
    switch((int)$_POST["action"]){
        case 1:  $obj->getInstitution();            break;
        case 2:  $obj->getArea();                   break;
        case 3:  $obj->getState();                  break;
        case 4:  $obj->getRegister();               break;
        case 5:  $obj->insertRegister();            break;
        case 6:  $obj->traceDocument();             break;
        case 7:  $obj->getAreaAd();                 break;
        case 8:  $obj->traceUpdateInfo();           break;
        case 9:  $obj->insertPreregistro();         break;
        case 10: $obj->getPreregistrosPendientes(); break;
        case 11: $obj->getPreregistroById();        break;
        case 12: $obj->promoverPreregistro();       break;
        case 13: $obj->getAreaPublico();            break;
    }
}