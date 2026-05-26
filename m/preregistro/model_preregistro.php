<?php
date_default_timezone_set("America/Mexico_City");

if(file_exists('./m/db_connection.php')){
    require_once './m/db_connection.php';
}else if(file_exists('../../m/db_connection.php')){
    require_once  '../../m/db_connection.php';
}else if(file_exists('../../../m/db_connection.php')){
    require_once  '../../../m/db_connection.php';
}

class actualizar{

    public function getInstitution(){ 
        $con=new DBconnection();
        $con->openDB(); 

        $inst = $con->query("SELECT cve, name FROM  institutions WHERE status=1");

        $data = array();

        while($row = pg_fetch_array($inst)){
            $dat = array(
                "cve"=>$row["cve"],
                "name"=>$row["name"]
            );
            $data[] = $dat;
        }
        $con->closeDB();
        return $data;
    }

     public function getArea($program){
        $con=new DBconnection();
        $con->openDB();

        $areas = $con->query("SELECT cve, name FROM  courses WHERE status=1 AND type = " . $program);

        $data = array();

        while($row = pg_fetch_array($areas)){
            $dat = array(
                "cve"=>$row["cve"],
                "name"=>$row["name"]
            );
            $data[] = $dat;
        }
        $con->closeDB();
        return $data;
    }

    public function getAreaAd($id_titledata){
        $con=new DBconnection();
        $con->openDB();

        $dataTitleR = $con->query("SELECT course_cvecourse, course_name FROM registerinfo WHERE id_titledata=". $id_titledata);

        $data = array();

        while($row = pg_fetch_array($dataTitleR)){
            $dat = array(
                "cve" =>$row["course_cvecourse"],
                "name" =>$row["course_name"]
            );
            $data[] = $dat;
        }
        $con->closeDB();
        
        return $data;
    }

    public function getState(){
        $con=new DBconnection();
        $con->openDB();

        $inst = $con->query("SELECT id, name FROM  states");

        $data = array();

        while($row = pg_fetch_array($inst)){
            $dat = array(
                "id"=>$row["id"],
                "name"=>$row["name"]
            );
            $data[] = $dat;
        }
        $con->closeDB();
        return $data;
    }

   public function getRegister($id_titledata){
        $con=new DBconnection();
        $con->openDB();

        $dataTitleR = $con->query("SELECT id_titledata, controlinvoice, professional_curp, professional_name, professional_surname, professional_secondsurname, professional_email, institution_cveinstitution, institution_nameinstitution, course_cvecourse, course_name, DATE(course_startdate) AS course_startdate, DATE(course_finishdate) AS course_finishdate, course_idreconnaissanceauthorization, course_reconnaissanceauthorization, DATE(expedition_date) AS expedition_date, expedition_iddegreemodality, expedition_degreemodality, expedition_degreemodality_details, DATE(expedition_dateprofessionalexam) AS expedition_dateprofessionalexam, expedition_socialservice, expedition_idlegalbasissocialservice, expedition_legalbasissocialservice, expedition_idstate, expedition_state, antecedent_institutionorigin, antecedent_idtypestudy, antecedent_typestudy, antecedent_idstate, antecedent_state, DATE(antecedent_finishdate) AS antecedent_finishdate, antecedent_document, status FROM registerinfo WHERE id_titledata=". $id_titledata);
        $data = array();

        while($row = pg_fetch_array($dataTitleR)){
            $dat = array(
                "id_titledata" =>$row["id_titledata"],
                "controlinvoice" =>$row["controlinvoice"],
                "professional_curp" =>$row["professional_curp"],
                "professional_name" =>$row["professional_name"], 
                "professional_surname" =>$row["professional_surname"],
                "professional_secondsurname" =>$row["professional_secondsurname"],
                "professional_email" =>$row["professional_email"],
                "institution_cveinstitution" =>$row["institution_cveinstitution"],
                "institution_nameinstitution" =>$row["institution_nameinstitution"],
                "course_cvecourse" =>$row["course_cvecourse"],
                "course_name" =>$row["course_name"],
                "course_startdate" =>$row["course_startdate"],
                "course_finishdate" =>$row["course_finishdate"],
                "course_idreconnaissanceauthorization" =>$row["course_idreconnaissanceauthorization"],
                "course_reconnaissanceauthorization" =>$row["course_reconnaissanceauthorization"],
                "expedition_date" =>$row["expedition_date"],
                "expedition_iddegreemodality" =>$row["expedition_iddegreemodality"],
                "expedition_degreemodality" =>$row["expedition_degreemodality"],
                "expedition_degreemodality_details" =>$row["expedition_degreemodality_details"],
                "expedition_dateprofessionalexam" =>$row["expedition_dateprofessionalexam"],
                "expedition_socialservice" =>$row["expedition_socialservice"],
                "expedition_idlegalbasissocialservice" =>$row["expedition_idlegalbasissocialservice"],
                "expedition_legalbasissocialservice" =>$row["expedition_legalbasissocialservice"],
                "expedition_idstate" =>$row["expedition_idstate"],
                "expedition_state" =>$row["expedition_state"],
                "antecedent_institutionorigin" =>$row["antecedent_institutionorigin"],
                "antecedent_idtypestudy" =>$row["antecedent_idtypestudy"],
                "antecedent_typestudy" =>$row["antecedent_typestudy"],
                "antecedent_idstate" =>$row["antecedent_idstate"],
                "antecedent_state" =>$row["antecedent_state"],
                "antecedent_finishdate" =>$row["antecedent_finishdate"],
                "antecedent_document" =>$row["antecedent_document"],
                "status" =>$row["status"]
            );
            $data[] = $dat;
        }
        $con->closeDB();
        
        return $data;
    }

     public function insertRegister (
        $controlinvoice, 
        $professional_curp, 
        $professional_name, 
        $professional_surname, 
        $professional_secondsurname, 
        $professional_email, 
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
        $expedition_socialservice, 
        $expedition_idlegalbasissocialservice, 
        $expedition_legalbasissocialservice, 
        $expedition_idstate, 
        $expedition_state, 
        $antecedent_institutionorigin, 
        $antecedent_idtypestudy, 
        $antecedent_typestudy, 
        $antecedent_idstate, 
        $antecedent_state, 
        $antecedent_finishdate, 
        $antecedent_document
    ) {
        $con = new DBconnection();
        $con->openDB();

         // Construir la consulta SQL
    $sqlQuery = "INSERT INTO titledata (controlinvoice, professional_curp, professional_name, professional_surname, professional_secondsurname, professional_email, institution_cveinstitution, institution_nameinstitution, course_cvecourse, course_name, course_startdate, course_finishdate, course_idreconnaissanceauthorization, course_reconnaissanceauthorization, expedition_date, expedition_idDegreeModality, expedition_degreeModality, expedition_degreemodality_details, expedition_dateProfessionalExam, expedition_socialService, expedition_idLegalBasisSocialService, expedition_legalBasisSocialService, expedition_idState, expedition_state, antecedent_institutionorigin, antecedent_idtypestudy, antecedent_typestudy, antecedent_idstate, antecedent_state, antecedent_finishdate, antecedent_document, date_register) 
    VALUES ('".$controlinvoice."', '" .$professional_curp."', '" .$professional_name."', '" .$professional_surname."', '" .$professional_secondsurname."', '" .$professional_email."', " .$institution_cveinstitution.", '" .$institution_nameinstitution."', " .$course_cvecourse. ", '"  .$course_name."', '" .$course_startdate."', '" .$course_finishdate."', " .$course_idreconnaissanceauthorization.", '" .$course_reconnaissanceauthorization."', '" .$expedition_date."', " .$expedition_iddegreemodality.", '" .$expedition_degreemodality."', '" .$expedition_degreemodality_details."', '" .$expedition_dateprofessionalexam."', " .$expedition_socialservice.", " .$expedition_idlegalbasissocialservice.", '" .$expedition_legalbasissocialservice."', '" .$expedition_idstate."', '" .$expedition_state."', '" .$antecedent_institution."', " .$antecedent_idtypestudy.", '" .$antecedent_typestudy."', '" .$antecedent_idstate."', '" .$antecedent_state."', '" .$antecedent_finishdate."', '".$antecedent_document."', NOW()) RETURNING id_titledata";

    // Ejecutar la consulta
    $titleData = $con->query($sqlQuery);

    // Validar si se obtuvo el ID
    $validateTitleData = pg_fetch_row($titleData);

    if ($validateTitleData > 0) {
        // Si la consulta fue exitosa, cerrar la conexión y devolver el ID
        $con->closeDB();
        return $validateTitleData[0];
    } else {
        
       return "error: Consulta ejecutada: " . $sqlQuery ;  // Devolver el error y la consulta
    } 
    }



   public function traceDocument($id_titledata, $controlinvoice, $user){
        $descrip = 'Creado por '.$user;
        $con = new DBconnection();
        $con->openDB();
        $traceDocument = $con->query("INSERT INTO traceelectronicdocument 
                            (fk_documentdata, status, description, controlinvoice, date)
                            VALUES (".$id_titledata.", 100, '".$descrip."', '".$controlinvoice."', NOW()) 
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

        // 1. Actualizar registerinfo
        $updateDelete = $con->query("
            UPDATE registerinfo 
            SET status = 0 
            WHERE id_titledata = ".$registerinfo_id." 
            RETURNING id_titledata
        ");

        $validateUpdateDelete = pg_fetch_row($updateDelete);

        if ($validateUpdateDelete) {

            // 2. Actualizar traceelectronicdocument
            $updateTrace = $con->query("
                UPDATE traceelectronicdocument 
                SET fk_documentdata = ".$new_titledata_id." 
                WHERE fk_registerinfo = ".$registerinfo_id."
            ");

            $con->closeDB();
            return $validateUpdateDelete[0];

        } else {
            $con->closeDB();
            return "error"; 
        }
    }
}
?>