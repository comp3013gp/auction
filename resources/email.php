<?php

class email_sender
{

	private $headers;
	
    function __construct()
    {
		$this->headers = "MIME-Version: 1.0" . "\r\n";
		$this->headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    }

    function send($recipient, $subject, $html_body)
    {
		return mail($recipient, $subject, $html_body,$this->headers);
    }

    function send_with_log($recipient, $subject, $html_body){
        echo "  Sending email to $recipient ";
        $flag=$this->send($recipient, $subject, $html_body);
        if($flag!=false){
            echo " -- OK \n";
        }else{
            echo " -- ERROR \n";
        }

    }

    function get_error_info()
    {
        return $this->error_info;
    }
}

?>