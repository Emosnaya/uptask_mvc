<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

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
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '05816bfb40ab4b';
        $mail->Password = '8df7e31bc2f0ce';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has Creado tu cuenta en Uptask, solo debes confirmarla en el siguiente enlace<p>";
        $contenido .= "<p>Presiona aquí: <a href= 'http://localhost:3000/confirmar?token=" . $this->token . "' >Confirmar cuenta</a></p>";
        $contenido .= "<p> Si tu no creaste esta cuenta, puedes ignorar este mensaje";
        $contenido .= '</hmtl>';

        $mail->Body = $contenido;

        //Enviar email

        $mail->send();
    }

}