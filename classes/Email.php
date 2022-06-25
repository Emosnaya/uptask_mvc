<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email {
    protected $nombre;
    protected $email;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){

        try {
            $mail = new PHPMailer();
            //Server setting                   //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = 465;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                         //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                 //Enable SMTP authentication
            $mail->Username   = $_ENV['EMAIL'];                  //SMTP username
            $mail->Password   = $_ENV['PASS'];                               //SMTP password
                     //Enable implicit TLS encryption
                                               //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($_ENV['EMAIL'], 'UpTask');
            $mail->addAddress($this->email, $this->nombre);     //Add a recipient
        
            //Content
            $mail->isHTML(true);  
            $mail->CharSet = 'UTF-8';

            $contenido = '<html>';
            $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has Creado tu cuenta en Uptask, solo debes confirmarla en el siguiente enlace<p>";
            $contenido .= "<p>Presiona aquí: <a href= 'https://mysterious-ridge-57985.herokuapp.com/confirmar?token=" . $this->token . "' >Confirmar cuenta</a></p>";
            $contenido .= "<p> Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
            $contenido .= '</hmtl>';                                //Set email format to HTML
            $mail->Subject = 'Confirma tu cuenta';
            $mail->Body = $contenido;
        
            $mail->send();
        } catch (Exception $e) {
            echo "error";
        }
        
    }

    public function enviarInstrucciones(){

        

        try {
            $mail = new PHPMailer();
            //Server settings                    //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $_ENV['EMAIL'];                     //SMTP username
            $mail->Password   = $_ENV['PASS'];                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($_ENV['EMAIL'], 'UpTask');
            $mail->addAddress($this->email, $this->nombre);     //Add a recipient
        
            //Content
            $mail->isHTML(true);  
            $mail->CharSet = 'UTF-8';
            $contenido = '<html>';
            $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Parece que has olvidado tu password<p>";
            $contenido .= "<p>Presiona aquí: <a href= 'https://mysterious-ridge-57985.herokuapp.com//reestablecer?token=" . $this->token . "' >Reestablecer Password</a></p>";
            $contenido .= "<p> Si tu no solicitaste esto, puedes ignorar este mensaje</p>";
            $contenido .= '</hmtl>';
            $mail->Subject = 'Reestablece tu Password';
            $mail->Body = $contenido;
        
            $mail->send();
        } catch (Exception $e) {
            echo "Error";
        }
    }
}