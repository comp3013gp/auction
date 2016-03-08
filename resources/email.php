<?php

require_once("library/PHPMailer/PHPMailerAutoload.php");

class email_sender
{

    private $mailers = array();
    private $accounts = [ 'comp3013@outlook.com','comp3013-11@outlook.com','comp3013-11-1@outlook.com','comp3013-11-2@outlook.com','comp3013-11-3@outlook.com'];
    private $error_info;

    function __construct()
    {
        shuffle($this->accounts);
        $this->create_senders();
    }

    function create_senders()
    {
        for ($i = 0; $i < count($this->accounts); $i++) {
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';
            $mail->SMTPAuth = true;
            $mail->Username = $this->accounts[$i];
            $mail->Password = 'UCLdatabase';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('comp3013-1@outlook.com', 'comp3013-11');
            $this->mailers[$i] = $mail;
        }
    }

    function send($recipient, $subject, $html_body)
    {
        foreach ($this->mailers as $mailer) {
            $mailer->ClearAllRecipients();
            $mailer->addAddress($recipient);

            $mailer->isHTML(true);

            $mailer->Subject = $subject;
            $mailer->Body = $html_body;
            //$mailer->AltBody = $txt_body;
            if ($mailer->send()) {
                return true;
            } else {
                $this->error_info = $this->error_info . $mailer->Sender . " : " . $mailer->ErrorInfo . " \n";
            }
        }


        return false;
    }

    function get_error_info()
    {
        return $this->error_info;
    }
}

//$sender = new email_sender();
//if ($sender->send('ganzhexiaxiaohao@163.com', 'comp3013-test', 'comp3013-test body')) {
//    echo 'ok';
//} else {
//    echo $sender->get_error_info();
//}