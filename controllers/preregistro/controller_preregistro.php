<?php
date_default_timezone_set("America/Mexico_City");

require_once __DIR__ . '/../../models/model_preregistro.php';

define('UPLOAD_DIR',    __DIR__ . '/../../uploads/preregistro/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024);   // 10 MB

class actualizarController {

    private $actualizar;

    public function __construct() {}

    /* ─────────────────────────────────────────────────────────────────
       CATÁLOGOS GENERALES
    ───────────────────────────────────────────────────────────────── */
    public function getInstitution() {
        $this->actualizar = new actualizar();
        echo json_encode($this->actualizar->getInstitution());
    }

    public function getArea() {
        $this->actualizar = new actualizar();
        echo json_encode($this->actualizar->getArea($_POST["program"]));
    }

    public function getState() {
        $this->actualizar = new actualizar();
        echo json_encode($this->actualizar->getState());
    }

    public function getRegister() {
        $this->actualizar = new actualizar();
        $id = isset($_POST["id_titledata"]) ? (int)$_POST["id_titledata"] : 0;
        if ($id <= 0) { echo json_encode([]); return; }
        echo json_encode($this->actualizar->getRegister($id));
    }

    public function getAreaAd() {
        $this->actualizar = new actualizar();
        $id = isset($_POST["id_titledata"]) ? (int)$_POST["id_titledata"] : 0;
        if ($id <= 0) { echo json_encode([]); return; }
        echo json_encode($this->actualizar->getAreaAd($id));
    }

    public function insertRegister() {
        $this->actualizar = new actualizar();
        $data = $this->actualizar->insertRegister(
            $_POST["controlinvoice"],                    $_POST["professional_curp"],
            $_POST["professional_name"],                 $_POST["professional_surname"],
            $_POST["professional_secondsurname"],        $_POST["professional_email"],
            $_POST["institution_cveinstitution"],        $_POST["institution_nameinstitution"],
            $_POST["course_cvecourse"],                  $_POST["course_name"],
            $_POST["course_startdate"],                  $_POST["course_finishdate"],
            $_POST["course_idreconnaissanceauthorization"], $_POST["course_reconnaissanceauthorization"],
            $_POST["expedition_date"],                   $_POST["expedition_iddegreemodality"],
            $_POST["expedition_degreemodality"],         $_POST["expedition_degreemodality_details"],
            $_POST["expedition_dateprofessionalexam"],   $_POST["expedition_socialservice"],
            $_POST["expedition_idlegalbasissocialservice"], $_POST["expedition_legalbasissocialservice"],
            $_POST["expedition_idstate"],                $_POST["expedition_state"],
            $_POST["antecedent_institutionorigin"],      $_POST["antecedent_idtypestudy"],
            $_POST["antecedent_typestudy"],              $_POST["antecedent_idstate"],
            $_POST["antecedent_state"],                  $_POST["antecedent_finishdate"],
            $_POST["antecedent_document"]
        );
        echo json_encode($data);
    }

    public function traceDocument() {
        $this->actualizar = new actualizar();
        echo $this->actualizar->traceDocument(
            $_POST["id_titledata"], $_POST["controlinvoice"], $_POST["user"]
        );
    }

    public function traceUpdateInfo() {
        $this->actualizar = new actualizar();
        echo json_encode($this->actualizar->traceUpdateInfo(
            $_POST["registerinfo_id"], $_POST["new_titledata_id"]
        ));
    }

    /* ─────────────────────────────────────────────────────────────────
       ACTION 9 — INSERTAR PRE-REGISTRO (formulario público)
    ───────────────────────────────────────────────────────────────── */
    public function insertPreregistro() {
        header('Content-Type: application/json');

        /* 1. Validar con el método estático del modelo */
        $errores = actualizar::validarPreregistro($_POST, $_FILES);
        if (!empty($errores)) {
            echo json_encode(['ok' => false, 'errores' => $errores]);
            return;
        }

        /* 2. Subir archivos ─────────────────────────────────────────── */
        $rutas         = [];
        $camposArchivo = [
            'archivo_curp',
            'archivo_certificado',
            'archivo_acta_examen',
            'archivo_titulo_grado',
            'archivo_cedula'
        ];

        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

        foreach ($camposArchivo as $campo) {
            if (empty($_FILES[$campo]['name'])) {
                $rutas[$campo] = null;
                continue;
            }
            $archivo = $_FILES[$campo];
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['ok' => false, 'errores' => [$campo => 'Error al subir el archivo.']]);
                return;
            }
            if ($archivo['size'] > MAX_FILE_SIZE) {
                echo json_encode(['ok' => false, 'errores' => [$campo => 'El archivo supera los 10 MB.']]);
                return;
            }
            $finfo   = finfo_open(FILEINFO_MIME_TYPE);
            $mime    = finfo_file($finfo, $archivo['tmp_name']);
            finfo_close($finfo);
            $allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($mime, $allowed)) {
                echo json_encode(['ok' => false, 'errores' => [$campo => 'Solo se aceptan PDF o imágenes (JPG, PNG).']]);
                return;
            }
            $ext    = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $nombre = $campo . '_' . uniqid() . '.' . $ext;
            if (!move_uploaded_file($archivo['tmp_name'], UPLOAD_DIR . $nombre)) {
                echo json_encode(['ok' => false, 'errores' => [$campo => 'No se pudo guardar el archivo. Verifica permisos.']]);
                return;
            }
            $rutas[$campo] = $nombre;
        }

        /* 3. Mapear modalidad ──────────────────────────────────────────
         *
         * ✅ FIX CURP: los textos de expedition_degreemodality deben caber
         *    en varchar(50) según el esquema real de la BD.
         *    Máx. 50 chars. Se verificó cada cadena:
         *      "POR TESIS"                      =  9 chars ✓
         *      "POR PROMEDIO"                   = 12 chars ✓
         *      "TESINA"                         =  6 chars ✓
         *      "PRESENTACION Y DEFENSA"         = 22 chars ✓  ← acortado
         *      "PORTAFOLIO DE EVIDENCIAS"       = 24 chars ✓  ← acortado
         *
         *    expedition_degreemodality_details es varchar(150), sin problema.
         ──────────────────────────────────────────────────────────────── */
        $modTextoPost = $_POST['expedition_iddegreemodality'] ?? '';
        $mapMod = [
            'Tesis'                    => 1,
            'Promedio'                 => 2,
            'Tesina'                   => 3,
            'Presentacion y Defensa'   => 4,
            'Portafolio de Evidencias' => 5
        ];
        $modId = $mapMod[$modTextoPost] ?? 0;

        /*
         * IMPORTANTE: expedition_degreemodality = varchar(50)
         * Cada texto <= 50 caracteres.
         */
        $modalidades = [
            1 => ['texto' => 'POR TESIS',               'detalle' => 'SIN DETALLES'],
            2 => ['texto' => 'POR PROMEDIO',             'detalle' => 'SIN DETALLES'],
            3 => ['texto' => 'TESINA',                   'detalle' => 'TESINA'],
            4 => ['texto' => 'PRESENTACION Y DEFENSA',   'detalle' => 'MATERIAL DIDACTICO Y MULTIMEDIA'],
            5 => ['texto' => 'PORTAFOLIO DE EVIDENCIAS', 'detalle' => 'PORTAFOLIO DE EVIDENCIAS'],
        ];
        $modTexto   = $modalidades[$modId]['texto']  ?? 'OTRO';
        $modDetalle = $modalidades[$modId]['detalle'] ?? 'SIN DETALLES';

        /*
         * ✅ FIX CURP: si $modId = 0 significa que el valor del POST no
         *    coincide con ninguna clave del mapa. Devolver error explícito
         *    en lugar de intentar insertar y fallar en la BD.
         */
        if ($modId === 0) {
            echo json_encode([
                'ok'     => false,
                'errores'=> ['expedition_iddegreemodality' => 'Modalidad de titulación no válida.']
            ]);
            return;
        }

        /* 4. Mapear área ────────────────────────────────────────────────
         *
         * ✅ FIX CURP: $areaPost = value del <select> = texto sin acentos
         *    (ej. "Astrofisica"). datos_precargados.js ya establece esto.
         *    Si no mapea → error explícito, nunca insertar course_cvecourse = 0.
         ──────────────────────────────────────────────────────────────── */
        $areaPost = trim($_POST['course_cvecourse'] ?? '');
        $mapAreas = [
            'Astrofisica'                         => 1,
            'Optica'                              => 2,
            'Electronica'                         => 3,
            'Ciencias Computacionales'            => 4,
            'Ciencia y Tecnologia del Espacio'    => 5,
            'Ciencias y Tecnologias Biomedicas'   => 6,
            'Ciencias y Tecnologias de Seguridad' => 7,
            'Ensenanza de Ciencias Exactas'       => 8
        ];
        $courseCve = $mapAreas[$areaPost] ?? 0;

        /*
         * ✅ FIX CURP: igual que con la modalidad, si el área no mapea
         *    devolver error en lugar de insertar NULL/0 en la BD.
         */
        if ($courseCve === 0) {
            echo json_encode([
                'ok'     => false,
                'errores'=> ['course_cvecourse' => 'Área del título no válida. Recarga la página e intenta de nuevo.']
            ]);
            return;
        }

        /* El nombre del área (con acentos) lo envía el JS via fd.set('course_name', ...) */
        $courseNombre = trim($_POST['course_name'] ?? $areaPost);

        /* 5. Datos auxiliares ──────────────────────────────────────── */
        $authId             = (int)($_POST['authorization']        ?? 0);
        $authTexto          = ($authId === 8) ? 'DECRETO DE CREACION' : '';

        $socialServiceId    = (int)($_POST['social_service']       ?? 0);
        $socialServiceLegal = (int)($_POST['social_service_legal'] ?? 0);
        $socialServiceTexto = ($socialServiceId    === 0) ? 'NO APLICA' : '';
        $socialLegalTexto   = ($socialServiceLegal === 5) ? 'NO APLICA' : '';

        $grado         = $_POST['course_type'] ?? '';
        $antTypeId     = ($grado === 'Doctorado') ? 1 : 2;
        $antTypeTextos = [1 => 'MAESTRIA', 2 => 'LICENCIATURA'];
        $antTypeTexto  = $antTypeTextos[$antTypeId] ?? '';

        /* 6. Llamar al modelo ──────────────────────────────────────── */
        $this->actualizar = new actualizar();
        $id = $this->actualizar->insertPreregistro(
            /* professional_curp                    */ $_POST['professional_curp']            ?? '',
            /* professional_name                    */ $_POST['professional_name'],
            /* professional_surname                 */ $_POST['professional_surname'],
            /* professional_secondsurname           */ $_POST['professional_secondsurname']   ?? '',
            /* professional_email                   */ $_POST['professional_email'],
            /* nacionalidad                         */ $_POST['nacionalidad']                 ?? '',
            /* controlinvoice                       */ $_POST['controlinvoice']               ?? '',
            /* institution_cveinstitution           */ (int)($_POST['institution']            ?? 0),
            /* institution_nameinstitution          */ $_POST['institution_name']             ?? '',
            /* course_cvecourse  ← ID numérico ✅   */ $courseCve,
            /* course_name       ← nombre texto ✅  */ $courseNombre,
            /* course_startdate                     */ $_POST['course_startdate'],
            /* course_finishdate                    */ $_POST['date_end']                     ?? '',
            /* course_idreconnaissanceauthorization */ $authId,
            /* course_reconnaissanceauthorization   */ $authTexto,
            /* expedition_date                      */ $_POST['date_expedition']              ?? '',
            /* expedition_iddegreemodality ← int ✅ */ $modId,
            /* expedition_degreemodality  <=50ch ✅ */ $modTexto,
            /* expedition_degreemodality_details    */ $modDetalle,
            /* expedition_dateprofessionalexam      */ $_POST['expedition_dateprofessionalexam'],
            /* expedition_dateexemption             */ $_POST['expedition_dateexemption']     ?? null,
            /* expedition_socialservice             */ $socialServiceId,
            /* expedition_idlegalbasissocialservice */ $socialServiceLegal,
            /* expedition_legalbasissocialservice   */ $socialServiceTexto,
            /* expedition_legalbasissocialservice_u */ $socialLegalTexto,
            /* expedition_idstate                   */ $_POST['expedition_state']             ?? '',
            /* expedition_state_text                */ $_POST['expedition_state_text']        ?? '',
            /* antecedent_idtypestudy               */ $antTypeId,
            /* antecedent_typestudy                 */ $antTypeTexto,
            /* antecedent_institutionorigin         */ $_POST['antecedent_institution']       ?? '',
            /* antecedent_idstate                   */ $_POST['antecedent_state']             ?? '',
            /* antecedent_state_text                */ $_POST['antecedent_state_text']        ?? '',
            /* antecedent_finishdate                */ $_POST['antecedent_finich_date']       ?? '',
            /* antecedent_document                  */ $_POST['antecedent_document'],
            /* carrera_egreso                       */ $_POST['carrera_egreso']               ?? '',
            /* archivo_curp                         */ $rutas['archivo_curp']                ?? null,
            /* archivo_certificado                  */ $rutas['archivo_certificado']          ?? '',
            /* archivo_acta_examen                  */ $rutas['archivo_acta_examen']          ?? '',
            /* archivo_titulo_grado                 */ $rutas['archivo_titulo_grado']         ?? '',
            /* archivo_cedula                       */ $rutas['archivo_cedula']               ?? ''
        );

        /* 7. Respuesta ─────────────────────────────────────────────── */
        if (is_numeric($id)) {
            echo json_encode(['ok' => true, 'id' => (int)$id]);
        } else {
            $msg = is_string($id) ? $id : 'Error al guardar en la base de datos. Intenta de nuevo.';
            echo json_encode(['ok' => false, 'errores' => ['general' => $msg]]);
        }
    }

    /* ─────────────────────────────────────────────────────────────────
       ACCIONES ADMINISTRATIVAS (requieren sesión)
    ───────────────────────────────────────────────────────────────── */
    public function getPreregistrosPendientes() {
        if (!isset($_SESSION['username'])) { http_response_code(401); return; }
        header('Content-Type: application/json');
        $this->actualizar = new actualizar();
        echo json_encode($this->actualizar->getPreregistrosPendientes());
    }

    public function getPreregistroById() {
        if (!isset($_SESSION['username'])) { http_response_code(401); return; }
        header('Content-Type: application/json');
        $this->actualizar = new actualizar();
        $row = $this->actualizar->getPreregistroById((int)$_POST['id']);
        echo json_encode($row ?: ['error' => 'No encontrado']);
    }

    public function promoverPreregistro() {
        if (!isset($_SESSION['username'])) { http_response_code(401); return; }
        header('Content-Type: application/json');
        $this->actualizar = new actualizar();
        $ok = $this->actualizar->promoverPreregistro(
            (int)$_POST['prereg_id'],
            (int)$_POST['id_titledata']
        );
        echo json_encode(['ok' => $ok]);
    }

    public function getAreaPublico() {
        header('Content-Type: application/json');
        $program = isset($_POST['program']) ? (int)$_POST['program'] : 0;
        if ($program <= 0) { echo json_encode([]); return; }
        $this->actualizar = new actualizar();
        echo json_encode($this->actualizar->getArea($program));
    }

    public function updateRegistroAdministrativo() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['username'])) { http_response_code(401); return; }
        $this->actualizar = new actualizar();
        $id = isset($_POST['id_titledata']) ? (int)$_POST['id_titledata'] : 0;
        if ($id > 0) {
            $ok = $this->actualizar->updateRegistroAdministrativo($id, $_POST);
            echo json_encode($ok
                ? ['ok' => true]
                : ['ok' => false, 'error' => 'No se pudo actualizar el registro']
            );
        } else {
            echo json_encode(['ok' => false, 'error' => 'ID inválido']);
        }
    }

    public function getCatalogosAdministrativos() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['username'])) { http_response_code(401); return; }
        $this->actualizar = new actualizar();
        echo json_encode([
            'instituciones' => $this->actualizar->getInstitution(),
            'estados'       => $this->actualizar->getState()
        ]);
    }
}

/* ─────────────────────────────────────────────────────────────────────
   DISPATCHER
───────────────────────────────────────────────────────────────────── */
session_start();
$obj = new actualizarController();

if (isset($_POST["action"])) {
    switch ((int)$_POST["action"]) {
        case  1: $obj->getInstitution();               break;
        case  2: $obj->getArea();                      break;
        case  3: $obj->getState();                     break;
        case  4: $obj->getRegister();                  break;
        case  5: $obj->insertRegister();               break;
        case  6: $obj->traceDocument();                break;
        case  7: $obj->getAreaAd();                    break;
        case  8: $obj->traceUpdateInfo();              break;
        case  9: $obj->insertPreregistro();            break;
        case 10: $obj->getPreregistrosPendientes();    break;
        case 11: $obj->getPreregistroById();           break;
        case 12: $obj->promoverPreregistro();          break;
        case 13: $obj->getAreaPublico();               break;
        case 15: $obj->updateRegistroAdministrativo(); break;
        case 16: $obj->getCatalogosAdministrativos();  break;
    }
}