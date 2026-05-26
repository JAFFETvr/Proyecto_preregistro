<?php
date_default_timezone_set("America/Mexico_City");
require_once '../models/db_connection.php';
require  'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';
require  'phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

header("Access-Control-Allow-Origin: *");

class emailManagement {
    public function sendSurveyEmail($email){
        $currentTime = date("Y-m-d H:i:s");
        $subject = "Encuesta Psicologia";
        $message = "Estimado usuario, con el fin de mejorar nuestros servicios le rogamos que dedique unos minutos a completar la siguiente encuesta: <a href='http://192.168.73.131/evaluaciondfa/views/quiz/psicologia.php?access=".sha1($email.$currentTime)."'>aqui</a>.";

        $send_email = $this->sendEmail($email,$subject,$message);
    }

    public function sendEmail($email,$subject,$message){
        try{
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPOptions = array(
                'ssl' => array(
                 'verify_peer' => false,
                 'verify_peer_name' => false,
                 'allow_self_signed' => true
                )
            );
            $mail->SMTPAuth = true;
            $mail->Username = "email.cpu.inaoe@gmail.com";
            $mail->Password = "inaoe123";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            $mail->CharSet="utf-8";

            $mail->SetFrom('email.cpu.inaoe@gmail.com', 'INAOE');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = "<div style='font-family: \"Nunito Sans\", Arial, sans-serif;  text-align: center;'>
                                <br>
                                <h3 style='font-size: 32px; color: #000; margin: 0;'>SERVICIOS INAOE</h3>
                                <p style='font-size: large; line-height: 1.8; color: #646464;'>".$message."
                                </p>
                            </div>";

            if($mail->send()){
                echo 'Message has been sent';
            }else{
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }catch(Exception $e){
            echo $e;
        }
    }
}

$obj = new emailManagement();

if (isset($_GET["opt"])){
    if($_GET['opt'] == "send_survey_email"){
        $obj->sendSurveyEmail($_GET['email']);
    }
}
?>