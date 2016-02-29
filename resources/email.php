<?php

require '/library/PHPMailer/PHPMailerAutoload.php';

class email_sender
{

    private $mail;

    function __construct()
    {
        $this->mail = new PHPMailer;

        $this->mail->isSMTP();
        $this->mail->Host = 'smtp-mail.outlook.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'comp3013@outlook.com';
        $this->mail->Password = 'UCLdatabase';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;

        $this->mail->setFrom('from@example.com', 'Mailer');
    }

    function send($recipient, $subject, $html_body)
    {
        $this->mail->addAddress($recipient);

        $this->mail->isHTML(true);

        $this->mail->Subject = $subject;
        $this->mail->Body = $html_body;
        //$this->mail->AltBody = $txt_body;

        return $this->mail->send();
    }

    function get_error_info(){
        return $this->mail->ErrorInfo;
    }
}

//$sender=new email_sender();
//$sender->send('ganzhexiaxiaohao@163.com','test','body');