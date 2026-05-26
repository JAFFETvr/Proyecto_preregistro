<?php
date_default_timezone_set("America/Mexico_City");

require_once __DIR__ . '/../../models/model_preregistro.php';

define('UPLOAD_DIR', __DIR__ . '/../../uploads/preregistro/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10 MB

class actualizarController{
    private $actualizar;

    public function __construct(){}

    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS EXISTENTES (sin cambios)
    // ─────────────────────────────────────────────────────────────────────────

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

    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS NUEVOS — Pre-registro público
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * action=9 — Alumno envía el formulario público.
     */
    public function insertPreregistro(){
        header('Content-Type: application/json');

        // ── Validar campos ───────────────────────────────────────────────
        $errores = actualizar::validarPreregistro($_POST, $_FILES);
        if (!empty($errores)){
            echo json_encode(['ok' => false, 'errores' => $errores]);
            return;
        }

        // ── Subir archivos ───────────────────────────────────────────────
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

        // ── Construir textos de modalidad ────────────────────────────────
        $modId = (int)($_POST['expedition_iddegreemodality'] ?? 0);
        $modalidades = [
            1 => ['texto' => 'POR TESIS',                                     'detalle' => 'SIN DETALLES'],
            2 => ['texto' => 'POR PROMEDIO',                                  'detalle' => 'SIN DETALLES'],
            3 => ['texto' => 'TESINA',                                        'detalle' => 'TESINA'],
            4 => ['texto' => 'PRESENTACION Y DEFENSA CON MATERIAL DIDACTICO', 'detalle' => 'MATERIAL DIDACTICO Y MULTIMEDIA'],
            5 => ['texto' => 'PORTAFOLIO DE EVIDENCIAS',                      'detalle' => 'PORTAFOLIO PROFESIONAL DE EVIDENCIAS'],
        ];
        $modTexto   = $modalidades[$modId]['texto']  ?? 'OTRO';
        $modDetalle = $modalidades[$modId]['detalle'] ?? 'SIN DETALLES';

        // ── Textos de selects ─────────────────────────────────────────────
        $authId     = (int)($_POST['authorization']      ?? 0);
        $authTexto  = ($authId === 8) ? 'DECRETO DE CREACION' : '';

        $socialServiceId    = (int)($_POST['social_service']       ?? 0);
        $socialServiceLegal = (int)($_POST['social_service_legal'] ?? 0);
        $socialServiceTexto = ($socialServiceId === 0)    ? 'NO APLICA' : '';
        $socialLegalTexto   = ($socialServiceLegal === 5) ? 'NO APLICA' : '';

        $antTypeId     = (int)($_POST['antecedent_type_study'] ?? 0);
        $antTypeTextos = [1 => 'MAESTRIA', 2 => 'LICENCIATURA'];
        $antTypeTexto  = $antTypeTextos[$antTypeId] ?? '';

        // ── Insertar en BD ───────────────────────────────────────────────
        $this->actualizar = new actualizar();
        $id = $this->actualizar->insertPreregistro(
            $_POST['professional_curp']            ?? '',   // professional_curp
            $_POST['professional_name'],                    // professional_name
            $_POST['professional_surname'],                 // professional_surname
            $_POST['professional_secondsurname']   ?? '',   // professional_secondsurname
            $_POST['professional_email'],                   // professional_email
            $_POST['nacionalidad']                 ?? '',   // nacionalidad
            $_POST['controlinvoice']               ?? '',   // controlinvoice
            (int)($_POST['institution']            ?? 0),   // institution_cveinstitution
            $_POST['institution_name']             ?? '',   // institution_nameinstitution
            (int)$_POST['course_cvecourse'],                // course_cvecourse
            $_POST['course_name']                  ?? '',   // course_name
            $_POST['course_startdate'],                     // course_startdate
            $_POST['date_end']                     ?? '',   // course_finishdate
            $authId,                                        // course_idreconnaissanceauthorization
            $authTexto,                                     // course_reconnaissanceauthorization
            $_POST['date_expedition']              ?? '',   // expedition_date
            $modId,                                         // expedition_iddegreemodality
            $modTexto,                                      // expedition_degreemodality
            $modDetalle,                                    // expedition_degreemodality_details
            $_POST['expedition_dateprofessionalexam'],      // expedition_dateprofessionalexam
            $_POST['expedition_dateexemption']     ?? null, // expedition_dateexemption
            $socialServiceId,                               // expedition_socialservice
            $socialServiceLegal,                            // expedition_idlegalbasissocialservice
            $socialServiceTexto,                            // expedition_legalbasissocialservice
            $socialLegalTexto,                              // expedition_legalbasissocialservice_text (unused but passed)
            $_POST['expedition_state']             ?? '',   // expedition_idstate
            $_POST['expedition_state_text']        ?? '',   // expedition_state texto
            $antTypeId,                                     // antecedent_idtypestudy
            $antTypeTexto,                                  // antecedent_typestudy
            $_POST['antecedent_institution']       ?? '',   // antecedent_institutionorigin
            $_POST['antecedent_state']             ?? '',   // antecedent_idstate
            $_POST['antecedent_state_text']        ?? '',   // antecedent_state texto
            $_POST['antecedent_finich_date']       ?? '',   // antecedent_finishdate
            $_POST['antecedent_document'],                  // antecedent_document
            $_POST['carrera_egreso']               ?? '',   // carrera_egreso
            $rutas['archivo_curp']                 ?? null, // archivo_curp
            $rutas['archivo_certificado']          ?? '',   // archivo_certificado
            $rutas['archivo_acta_examen']          ?? '',   // archivo_acta_examen
            $rutas['archivo_titulo_grado']         ?? '',   // archivo_titulo_grado
            $rutas['archivo_cedula']               ?? ''    // archivo_cedula
        );

        if (is_numeric($id)){
            echo json_encode(['ok' => true, 'id' => (int)$id]);
        } else {
            $msg = is_string($id) ? $id : 'Error al guardar en la base de datos. Intenta de nuevo.';
            echo json_encode(['ok' => false, 'errores' => ['general' => $msg]]);
        }
    }

    /**
     * action=10 — Lista pre-registros pendientes.
     */
    public function getPreregistrosPendientes(){
        if (!isset($_SESSION['username'])){ http_response_code(401); return; }
        header('Content-Type: application/json');
        $this->actualizar = new actualizar();
        echo json_encode($this->actualizar->getPreregistrosPendientes());
    }

    /**
     * action=11 — Detalle de un pre-registro.
     */
    public function getPreregistroById(){
        if (!isset($_SESSION['username'])){ http_response_code(401); return; }
        header('Content-Type: application/json');
        $this->actualizar = new actualizar();
        $row = $this->actualizar->getPreregistroById((int)$_POST['id']);
        echo json_encode($row ?: ['error' => 'No encontrado']);
    }

    /**
     * action=12 — Marcar como promovido.
     */
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

    /**
     * action=13 — Áreas públicas según grado.
     */
    public function getAreaPublico(){
        header('Content-Type: application/json');
        $program = isset($_POST['program']) ? (int)$_POST['program'] : 0;
        if ($program <= 0){ echo json_encode([]); return; }
        $this->actualizar = new actualizar();
        $data = $this->actualizar->getArea($program);
        echo json_encode($data);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Dispatcher
// ─────────────────────────────────────────────────────────────────────────────

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
