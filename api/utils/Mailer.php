<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        // Configuración SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'encabojuan@gmail.com';
        $this->mail->Password = $this->getSecretKeyEmail();
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Corrección aquí
        $this->mail->Port = 587;

        // Configuración del remitente
        $this->mail->setFrom('tu-email@gmail.com', 'Tu Nombre');
    }
    private function getSecretKeyEmail()
    {
        global $parametros;
        return $parametros['key_email'];
    }
    public function sendMail($to, $subject, $body) {
        try {
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->isHTML(true); // Asegura que el correo se envíe como HTML

            $this->mail->Body = $body;
            $this->mail->send();
            return "Correo enviado correctamente";
        } catch (Exception $e) {
            return "El correo no pudo ser enviado. Error: {$this->mail->ErrorInfo}";
        }
    }
}
