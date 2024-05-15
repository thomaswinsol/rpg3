<?php

class My_Mail 
{

    protected $mail;

    public function init()
    {
        require_once '/www/zendsvr/htdocs/PHPMailer/PHPMailer/src/Exception.php';
        require_once '/www/zendsvr/htdocs/PHPMailer/PHPMailer/src/PHPMailer.php';
        require_once '/www/zendsvr/htdocs/PHPMailer/PHPMailer/src/SMTP.php';        
        $this->mail = new PHPMailer\PHPMailer\PHPMailer();
        $this->mail->SMTPDebug=0;
        //$this->mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_CONNECTION;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = 'smtp-service@winsolgroup.onmicrosoft.com';                     //SMTP username
        //$mail->Username   = 'kris.holvoet@winsol.eu';
        $this->mail->Password   = '!u%98d:5.9@jb.+&P*?GL+yQxU*av+Z';                               //SMTP password
        //$mail->Password   = 'Winsol8870!';                                                
        $this->mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
    }
    
    public function setFrom($address, $name=null) {
        $this->init();
        //if(APPLICATION_ENV=='staging') {
            //$address = 'anthony.degrande@winsol.eu';
            //$address = 'kris.planckaert@winsol.eu';
            //Zend_Debug::dump($address);exit;
            $this->mail->setFrom($address, $name);
        /*} else {
            $this->mail->setFrom('smtp-service@winsolgroup.onmicrosoft.com', 'SMTP Service Winsol');
        }*/
    }
    
    public function addTo($address, $name=null) {
        $this->mail->addAddress($address, $name);
    }    
    
    public function addCc($address, $name=null) {
        $this->mail->addCC($address, $name);
    }
    
    public function addBcc($address, $name=null) {
        $this->mail->addBCC($address, $name);
    }
    
    public function setSubject($subject) {
        $this->mail->Subject = utf8_decode($subject);
    }
    
    public function setBodyHtml($html) {
        //$this->mail->Body = utf8_decode($html);
        //$this->mail->AltBody = utf8_decode($html);
        $this->mail->Body = ($html);
        $this->mail->AltBody = ($html);
    }
    
    public function setBodyText($text) {
        $this->mail->AltBody = utf8_decode($text);
    }    
    
    public function addAttachment($at) {
        
    }
    
    public function createAttachment($filename) {
        $this->mail->addAttachment($filename);
    }
    
    public function send() {
        $this->mail->send();
    }
    

}
