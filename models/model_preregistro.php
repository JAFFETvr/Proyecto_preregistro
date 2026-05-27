<?php
date_default_timezone_set("America/Mexico_City");

require_once __DIR__ . '/../m/db_connection.php';

class actualizar{

    public function getInstitution(){ 
        $con=new DBconnection();
        $con->openDB(); 
        $inst = $con->query("SELECT cve, name FROM institutions WHERE status=1");
        if (!$inst) { $con->closeDB(); return array(); }
        $data = array();
        while($row = pg_fetch_array($inst)){
            $data[] = array("cve"=>$row["cve"], "name"=>$row["name"]);
        }
        $con->closeDB();
        return $data;
    }

    public function getArea($program){
        $con=new DBconnection();
        $con->openDB();
        $areas = $con->query("SELECT cve, name FROM courses WHERE status=1 AND type = " . (int)$program);
        if (!$areas) { $con->closeDB(); return array(); }
        $data = array();
        while($row = pg_fetch_array($areas)){
            $data[] = array("cve"=>$row["cve"], "name"=>$row["name"]);
        }
        $con->closeDB();
        return $data;
    }

    public function getAreaAd($id_titledata){
        $con=new DBconnection();
        $con->openDB();
        $id_titledata = (int)$id_titledata;
        if ($id_titledata <= 0) { $con->closeDB(); return array(); }
        $dataTitleR = $con->query("SELECT course_cvecourse, course_name FROM registerinfo WHERE id_titledata=". $id_titledata);
        if (!$dataTitleR) { $con->closeDB(); return array(); }
        $data = array();
        while($row = pg_fetch_array($dataTitleR)){
            $data[] = array("cve"=>$row["course_cvecourse"], "name"=>$row["course_name"]);
        }
        $con->closeDB();
        return $data;
    }

    public function getState(){
        $con=new DBconnection();
        $con->openDB();
        $inst = $con->query("SELECT id, name FROM states");
        if (!$inst) { $con->closeDB(); return array(); }
        $data = array();
        while($row = pg_fetch_array($inst)){
            $data[] = array("id"=>$row["id"], "name"=>$row["name"]);
        }
        $con->closeDB();
        return $data;
    }

    public function getRegister($id_titledata){
        $con=new DBconnection();
        $con->openDB();
        $id_titledata = (int)$id_titledata;
        if ($id_titledata <= 0) { $con->closeDB(); return array(); }
        $dataTitleR = $con->query("SELECT id_titledata, controlinvoice, professional_curp,
            professional_name, professional_surname, professional_secondsurname,
            professional_email, institution_cveinstitution, institution_nameinstitution,
            course_cvecourse, course_name,
            DATE(course_startdate) AS course_startdate,
            DATE(course_finishdate) AS course_finishdate,
            course_idreconnaissanceauthorization, course_reconnaissanceauthorization,
            DATE(expedition_date) AS expedition_date,
            expedition_iddegreemodality, expedition_degreemodality, expedition_degreemodality_details,
            DATE(expedition_dateprofessionalexam) AS expedition_dateprofessionalexam,
            expedition_socialservice, expedition_idlegalbasissocialservice,
            expedition_legalbasissocialservice, expedition_idstate, expedition_state,
            antecedent_institutionorigin, antecedent_idtypestudy, antecedent_typestudy,
            antecedent_idstate, antecedent_state,
            DATE(antecedent_finishdate) AS antecedent_finishdate,
            antecedent_document, status
            FROM registerinfo WHERE id_titledata=". (int)$id_titledata);
        if (!$dataTitleR) { $con->closeDB(); return array(); }
        $data = array();
        while($row = pg_fetch_array($dataTitleR)){
            $data[] = array(
                "id_titledata"                        =>$row["id_titledata"],
                "controlinvoice"                      =>$row["controlinvoice"],
                "professional_curp"                   =>$row["professional_curp"],
                "professional_name"                   =>$row["professional_name"],
                "professional_surname"                =>$row["professional_surname"],
                "professional_secondsurname"          =>$row["professional_secondsurname"],
                "professional_email"                  =>$row["professional_email"],
                "institution_cveinstitution"          =>$row["institution_cveinstitution"],
                "institution_nameinstitution"         =>$row["institution_nameinstitution"],
                "course_cvecourse"                    =>$row["course_cvecourse"],
                "course_name"                         =>$row["course_name"],
                "course_startdate"                    =>$row["course_startdate"],
                "course_finishdate"                   =>$row["course_finishdate"],
                "course_idreconnaissanceauthorization"=>$row["course_idreconnaissanceauthorization"],
                "course_reconnaissanceauthorization"  =>$row["course_reconnaissanceauthorization"],
                "expedition_date"                     =>$row["expedition_date"],
                "expedition_iddegreemodality"         =>$row["expedition_iddegreemodality"],
                "expedition_degreemodality"           =>$row["expedition_degreemodality"],
                "expedition_degreemodality_details"   =>$row["expedition_degreemodality_details"],
                "expedition_dateprofessionalexam"     =>$row["expedition_dateprofessionalexam"],
                "expedition_socialservice"            =>$row["expedition_socialservice"],
                "expedition_idlegalbasissocialservice"=>$row["expedition_idlegalbasissocialservice"],
                "expedition_legalbasissocialservice"  =>$row["expedition_legalbasissocialservice"],
                "expedition_idstate"                  =>$row["expedition_idstate"],
                "expedition_state"                    =>$row["expedition_state"],
                "antecedent_institutionorigin"        =>$row["antecedent_institutionorigin"],
                "antecedent_idtypestudy"              =>$row["antecedent_idtypestudy"],
                "antecedent_typestudy"                =>$row["antecedent_typestudy"],
                "antecedent_idstate"                  =>$row["antecedent_idstate"],
                "antecedent_state"                    =>$row["antecedent_state"],
                "antecedent_finishdate"               =>$row["antecedent_finishdate"],
                "antecedent_document"                 =>$row["antecedent_document"],
                "status"                              =>$row["status"]
            );
        }
        $con->closeDB();
        return $data;
    }

    public function insertRegister(
        $controlinvoice, $professional_curp, $professional_name,
        $professional_surname, $professional_secondsurname, $professional_email,
        $institution_cveinstitution, $institution_nameinstitution,
        $course_cvecourse, $course_name, $course_startdate, $course_finishdate,
        $course_idreconnaissanceauthorization, $course_reconnaissanceauthorization,
        $expedition_date, $expedition_iddegreemodality, $expedition_degreemodality,
        $expedition_degreemodality_details, $expedition_dateprofessionalexam,
        $expedition_socialservice, $expedition_idlegalbasissocialservice,
        $expedition_legalbasissocialservice, $expedition_idstate, $expedition_state,
        $antecedent_institutionorigin, $antecedent_idtypestudy, $antecedent_typestudy,
        $antecedent_idstate, $antecedent_state, $antecedent_finishdate, $antecedent_document
    ) {
        $con = new DBconnection();
        $con->openDB();

        $sqlQuery = "INSERT INTO titledata (
            controlinvoice, professional_curp, professional_name, professional_surname,
            professional_secondsurname, professional_email,
            institution_cveinstitution, institution_nameinstitution,
            course_cvecourse, course_name, course_startdate, course_finishdate,
            course_idreconnaissanceauthorization, course_reconnaissanceauthorization,
            expedition_date, expedition_idDegreeModality, expedition_degreeModality,
            expedition_degreemodality_details, expedition_dateProfessionalExam,
            expedition_socialService, expedition_idLegalBasisSocialService,
            expedition_legalBasisSocialService, expedition_idState, expedition_state,
            antecedent_institutionorigin, antecedent_idtypestudy, antecedent_typestudy,
            antecedent_idstate, antecedent_state, antecedent_finishdate,
            antecedent_document, date_register
        ) VALUES (
            '".$controlinvoice."', '".$professional_curp."', '".$professional_name."',
            '".$professional_surname."', '".$professional_secondsurname."', '".$professional_email."',
            ".$institution_cveinstitution.", '".$institution_nameinstitution."',
            ".$course_cvecourse.", '".$course_name."', '".$course_startdate."', '".$course_finishdate."',
            ".$course_idreconnaissanceauthorization.", '".$course_reconnaissanceauthorization."',
            '".$expedition_date."', ".$expedition_iddegreemodality.", '".$expedition_degreemodality."',
            '".$expedition_degreemodality_details."', '".$expedition_dateprofessionalexam."',
            ".$expedition_socialservice.", ".$expedition_idlegalbasissocialservice.",
            '".$expedition_legalbasissocialservice."', '".$expedition_idstate."', '".$expedition_state."',
            '".$antecedent_institutionorigin."', ".$antecedent_idtypestudy.", '".$antecedent_typestudy."',
            '".$antecedent_idstate."', '".$antecedent_state."', '".$antecedent_finishdate."',
            '".$antecedent_document."', NOW()
        ) RETURNING id_titledata";

        $titleData = $con->query($sqlQuery);
        $validateTitleData = pg_fetch_row($titleData);

        if ($validateTitleData > 0) {
            $con->closeDB();
            return $validateTitleData[0];
        } else {
            return "error: " . $sqlQuery;
        }
    }

    public function traceDocument($id_titledata, $controlinvoice, $user){
        $descrip = 'Creado por '.$user;
        $con = new DBconnection();
        $con->openDB();
        $traceDocument = $con->query("INSERT INTO traceelectronicdocument 
            (fk_documentdata, status, description, controlinvoice, date)
            VALUES (".(int)$id_titledata.", 100, '".$descrip."', '".$controlinvoice."', NOW()) 
            RETURNING fk_documentdata");
        $validatetTraceUpdateDelete = pg_fetch_row($traceDocument);
        if ($validatetTraceUpdateDelete && isset($validatetTraceUpdateDelete[0])) {
            $con->closeDB();
            return $validatetTraceUpdateDelete[0];
        } else {
            $con->closeDB();
            return "error";
        }
    }

    public function traceUpdateInfo($registerinfo_id, $new_titledata_id){
        $con = new DBconnection();
        $con->openDB();
        $updateDelete = $con->query("
            UPDATE registerinfo 
            SET status = 0 
            WHERE id_titledata = ".(int)$registerinfo_id." 
            RETURNING id_titledata
        ");
        $validateUpdateDelete = pg_fetch_row($updateDelete);
        if ($validateUpdateDelete) {
            $updateTrace = $con->query("
                UPDATE traceelectronicdocument 
                SET fk_documentdata = ".(int)$new_titledata_id." 
                WHERE fk_registerinfo = ".(int)$registerinfo_id."
            ");
            $con->closeDB();
            return $validateUpdateDelete[0];
        } else {
            $con->closeDB();
            return "error";
        }
    }

    public function insertPreregistro(
        $professional_curp,
        $professional_name,
        $professional_surname,
        $professional_secondsurname,
        $professional_email,
        $nacionalidad,
        $controlinvoice,
        $institution_cveinstitution,
        $institution_nameinstitution,
        $course_cvecourse,
        $course_name,
        $course_startdate,
        $course_finishdate,
        $course_idreconnaissanceauthorization,
        $course_reconnaissanceauthorization,
        $expedition_date,
        $expedition_iddegreemodality,
        $expedition_degreemodality,
        $expedition_degreemodality_details,
        $expedition_dateprofessionalexam,
        $expedition_dateexemption,
        $expedition_socialservice,
        $expedition_idlegalbasissocialservice,
        $expedition_legalbasissocialservice,
        $expedition_legalbasissocialservice_unused,
        $expedition_idstate,
        $expedition_state_text,
        $antecedent_idtypestudy,
        $antecedent_typestudy,
        $antecedent_institutionorigin,
        $antecedent_idstate,
        $antecedent_state_text,
        $antecedent_finishdate,
        $antecedent_document,
        $carrera_egreso,
        $archivo_curp,
        $archivo_certificado,
        $archivo_acta_examen,
        $archivo_titulo_grado,
        $archivo_cedula
    ) {
        $con = new DBconnection();
        $con->openDB();
        $db = $con->getConn();

        $esc  = fn($v) => pg_escape_string($db, $v);
        $str  = fn($v) => ($v !== null && $v !== '') ? "'" . pg_escape_string($db, trim($v)) . "'" : "NULL";
        $strU = fn($v) => ($v !== null && $v !== '') ? "'" . pg_escape_string($db, strtoupper(trim($v))) . "'" : "NULL";
        $strL = fn($v) => ($v !== null && $v !== '') ? "'" . pg_escape_string($db, strtolower(trim($v))) . "'" : "NULL";
        $int  = fn($v) => ($v !== null && $v !== '' && (int)$v > 0) ? (int)$v : 'NULL';
        $dt   = fn($v) => ($v !== null && $v !== '') ? "'" . pg_escape_string($db, $v) . "'" : 'NULL';

        $sql = "INSERT INTO titledata (
                    professional_curp,
                    professional_name,
                    professional_surname,
                    professional_secondsurname,
                    professional_email,
                    nacionalidad,
                    controlinvoice,
                    institution_cveinstitution,
                    institution_nameinstitution,
                    course_cvecourse,
                    course_name,
                    course_startdate,
                    course_finishdate,
                    course_idreconnaissanceauthorization,
                    course_reconnaissanceauthorization,
                    expedition_date,
                    expedition_iddegreemodality,
                    expedition_degreemodality,
                    expedition_degreemodality_details,
                    expedition_dateprofessionalexam,
                    expedition_dateexemption,
                    expedition_socialservice,
                    expedition_idlegalbasissocialservice,
                    expedition_legalbasissocialservice,
                    expedition_idstate,
                    expedition_state,
                    antecedent_idtypestudy,
                    antecedent_typestudy,
                    antecedent_institutionorigin,
                    antecedent_idstate,
                    antecedent_state,
                    antecedent_finishdate,
                    antecedent_document,
                    carrera_egreso,
                    archivo_curp,
                    archivo_certificado,
                    archivo_acta_examen,
                    archivo_titulo_grado,
                    archivo_cedula,
                    date_register
                ) VALUES (
                    " . $str($professional_curp)                            . ",
                    " . $strU($professional_name)                           . ",
                    " . $strU($professional_surname)                        . ",
                    " . $strU($professional_secondsurname)                  . ",
                    " . $strL($professional_email)                          . ",
                    " . $str($nacionalidad)                                 . ",
                    " . $str($controlinvoice)                               . ",
                    " . $int($institution_cveinstitution)                   . ",
                    " . $str($institution_nameinstitution)                  . ",
                    " . $int($course_cvecourse)                             . ",
                    " . $str($course_name)                                  . ",
                    " . $dt($course_startdate)                              . ",
                    " . $dt($course_finishdate)                             . ",
                    " . $int($course_idreconnaissanceauthorization)         . ",
                    " . $str($course_reconnaissanceauthorization)           . ",
                    " . $dt($expedition_date)                               . ",
                    " . $int($expedition_iddegreemodality)                  . ",
                    " . $str($expedition_degreemodality)                    . ",
                    " . $str($expedition_degreemodality_details)            . ",
                    " . $dt($expedition_dateprofessionalexam)               . ",
                    " . $dt($expedition_dateexemption)                      . ",
                    " . $int($expedition_socialservice)                     . ",
                    " . $int($expedition_idlegalbasissocialservice)         . ",
                    " . $str($expedition_legalbasissocialservice)           . ",
                    " . $str($expedition_idstate)                           . ",
                    " . $str($expedition_state_text)                        . ",
                    " . $int($antecedent_idtypestudy)                       . ",
                    " . $str($antecedent_typestudy)                         . ",
                    " . $strU($antecedent_institutionorigin)                . ",
                    " . $str($antecedent_idstate)                           . ",
                    " . $str($antecedent_state_text)                        . ",
                    " . $dt($antecedent_finishdate)                         . ",
                    " . $str($antecedent_document)                          . ",
                    " . $str($carrera_egreso)                               . ",
                    " . $str($archivo_curp)                                 . ",
                    " . $str($archivo_certificado)                          . ",
                    " . $str($archivo_acta_examen)                          . ",
                    " . $str($archivo_titulo_grado)                         . ",
                    " . $str($archivo_cedula)                               . ",
                    NOW()
                ) RETURNING id_titledata";

        $res = @$con->query($sql);
        if (!$res) {
            $err = pg_last_error($db);
            $con->closeDB();
            error_log("insertPreregistro SQL error: " . $err);
            return "error: " . $err;
        }
        $row = pg_fetch_row($res);
        $con->closeDB();
        if ($row) return (int)$row[0];
        return "error";
    }

   public function getPreregistrosPendientes(){
        $con = new DBconnection();
        $con->openDB();
        $res = $con->query("
            SELECT id_titledata, professional_name, professional_surname,
                   professional_secondsurname, professional_email,
                   course_name, status,
                   TO_CHAR(date_register, 'DD/MM/YYYY HH24:MI') AS fecha_registro
            FROM titledata
            WHERE status = 1
            ORDER BY id_titledata DESC
        ");
        
        $data = array();
        if ($res) {
            while($row = pg_fetch_assoc($res)) {
                $data[] = $row;
            }
        }
        
        $con->closeDB();
        return $data;
    }

    public function getPreregistroById($id){
        $con = new DBconnection();
        $con->openDB();
        $res = $con->query("SELECT * FROM titledata WHERE id_titledata = " . (int)$id);
        
        $row = null;
        if ($res) {
            $row = pg_fetch_assoc($res);
        }
        
        $con->closeDB();
        return $row ?: null;
    }

    public function promoverPreregistro($prereg_id, $id_titledata){
        $con = new DBconnection();
        $con->openDB();
        $res = $con->query("
            UPDATE titledata
            SET status = 2
            WHERE id_titledata = " . (int)$prereg_id . "
            RETURNING id_titledata
        ");
        $row = pg_fetch_row($res);
        $con->closeDB();
        return $row ? true : false;
    }

    public static function validarPreregistro($post, $files){
        $errores = array();

        $tz  = new DateTimeZone('America/Mexico_City');
        $hoy = new DateTime('today', $tz);

        if (empty(trim($post['professional_name'] ?? '')))
            $errores['professional_name'] = 'El nombre es obligatorio.';

        if (empty(trim($post['professional_surname'] ?? '')))
            $errores['professional_surname'] = 'El primer apellido es obligatorio.';

        $regexAcento = '/[áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙäëïöüÄËÏÖÜâêîôûÂÊÎÔÛñÑ]/u';

        if (!empty($post['professional_name']) && preg_match($regexAcento, $post['professional_name']))
            $errores['professional_name'] = 'El nombre no debe contener acentos.';

        if (!empty($post['professional_surname']) && preg_match($regexAcento, $post['professional_surname']))
            $errores['professional_surname'] = 'El apellido paterno no debe contener acentos.';

        if (!empty($post['professional_secondsurname']) && preg_match($regexAcento, $post['professional_secondsurname']))
            $errores['professional_secondsurname'] = 'El apellido materno no debe contener acentos.';

        $email = trim($post['professional_email'] ?? '');
        $regexEmail = '/^[A-Za-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/';
        if (!preg_match($regexEmail, $email))
            $errores['professional_email'] = 'El correo electrónico no es válido.';

        $nac = $post['nacionalidad'] ?? '';
        if (!in_array($nac, ['Mexicana','Extranjera']))
            $errores['nacionalidad'] = 'Selecciona una nacionalidad.';

        if ($nac === 'Mexicana') {
            $curp = strtoupper(trim($post['professional_curp'] ?? ''));
            $regexCurp = '/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/';
            if (empty($curp))
                $errores['professional_curp'] = 'El CURP es obligatorio para ciudadanos mexicanos.';
            elseif (!preg_match($regexCurp, $curp))
                $errores['professional_curp'] = 'El CURP no tiene el formato correcto (18 caracteres).';
            if (empty($files['archivo_curp']['name']))
                $errores['archivo_curp'] = 'Debes adjuntar tu CURP.';
        }

        if (!in_array($post['course_type'] ?? '', ['Maestria','Doctorado']))
            $errores['course_type'] = 'Selecciona el grado del título.';

        if (empty($post['course_cvecourse'] ?? ''))
            $errores['course_cvecourse'] = 'Selecciona el área del título.';

        $fInicio  = trim($post['course_startdate'] ?? '');
        $fDefensa = trim($post['expedition_dateprofessionalexam'] ?? '');

        if (empty($fInicio)) {
            $errores['course_startdate'] = 'La fecha de inicio de grado es obligatoria.';
        } else {
            $dtInicio = DateTime::createFromFormat('Y-m-d', $fInicio, $tz);
            if (!$dtInicio) {
                $errores['course_startdate'] = 'Formato de fecha de inicio inválido.';
            } elseif ($dtInicio > $hoy) {
                $errores['course_startdate'] = 'La fecha de inicio no puede ser una fecha futura.';
            }
        }

        if (empty($fDefensa)) {
            $errores['expedition_dateprofessionalexam'] = 'La fecha de defensa/examen es obligatoria.';
        } else {
            $dtDefensa = DateTime::createFromFormat('Y-m-d', $fDefensa, $tz);
            if (!$dtDefensa) {
                $errores['expedition_dateprofessionalexam'] = 'Formato de fecha de defensa inválido.';
            } elseif (
                !empty($fInicio) &&
                !isset($errores['course_startdate']) &&
                isset($dtInicio) &&
                $dtDefensa <= $dtInicio
            ) {
                $errores['expedition_dateprofessionalexam'] = 'La fecha de defensa debe ser posterior a la fecha de inicio.';
            }
        }

        if (!in_array($post['expedition_iddegreemodality'] ?? '', ['Tesis','Tesina','Promedio','Presentacion y Defensa','Portafolio de Evidencias']))
            $errores['expedition_iddegreemodality'] = 'Selecciona una modalidad de titulación.';

        $cedula = trim($post['antecedent_document'] ?? '');
        if (empty($cedula))
            $errores['antecedent_document'] = 'La cédula profesional es obligatoria.';
        elseif (!preg_match('/^[0-9]{6,8}$/', $cedula))
            $errores['antecedent_document'] = 'La cédula debe ser numérica (6 a 8 dígitos).';

        foreach ([
            'archivo_certificado'  => 'el certificado del grado anterior',
            'archivo_acta_examen'  => 'el acta de examen del grado anterior',
            'archivo_titulo_grado' => 'el título de grado anterior',
            'archivo_cedula'       => 'la cédula profesional'
        ] as $campo => $desc) {
            if (empty($files[$campo]['name']))
                $errores[$campo] = "Debes adjuntar $desc.";
        }

        return $errores;
    }
}