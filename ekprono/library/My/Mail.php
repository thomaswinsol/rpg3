<?php

class My_Mail 
{

    protected $mail;


    public function init()
    {
        require_once '../library/PHPMailer/src/Exception.php';
        require_once '../library/PHPMailer/src/PHPMailer.php';
        require_once '../library/PHPMailer/src/SMTP.php';        
        $this->mail = new PHPMailer\PHPMailer\PHPMailer();
        //$this->mail->SMTPDebug=1;
        //$this->mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_CONNECTION;                      //Enable verbose debug output
        //$this->mail->isSMTP();                                        //Send using SMTP
        //$this->mail->Host       = 'smtp.gmail.com';                   //Set the SMTP server to send through
        //$this->mail->SMTPAuth   = true;                               //Enable SMTP authentication
        //$this->mail->Username   = 'thomasvanhuysse0@gmail.com';     //SMTP username
                                      
        //$this->mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        //$this->mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    $this->mail->setLanguage('en');
    $this->mail->SMTPDebug = 1;
    $this->mail->isSMTP();
    $this->mail->Host = 'smtp.gmail.com';
    $this->mail->SMTPAuth = true;
    //$this->mail->Username = 'thomas.vanhuysse76@gmail.com';
    $this->mail->Username = 'ekprono24@gmail.com';
    //$this->mail->Password = 'cprs lxkv ktxi hvpj';
    $this->mail->Password="hrjx ntfi nezc tblk";     
    $this->mail->SMTPSecure = 'tls';
    $this->mail->Port = 587;
    $this->mail->CharSet = 'iso-8859-1';
    $this->mail->Encoding = '8bit';
    $this->mail->IsHTML(true);   
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
    		//if(!$this->mail) $this->init();	
    		$this->mail->addAddress($address, $name);    	        
    }    
    
    public function addCc($address, $name=null) {
        $this->mail->addCC($address, $name);
    }
    
    public function addBcc($address, $name=null) {
        $this->mail->addBCC($address, $name);
    }
    
    public function setSubject($subject) {
        $this->mail->Subject = trim($subject);
    }
    
    public function setBodyHtml($html,$encoding=null,$mime=null) {        
        	$this->mail->Body = $html;
        	$this->mail->AltBody = $html;
    }
    
    public function setBodyText($text) {
        $this->mail->AltBody = trim($text);
    }    
    
    public function addAttachment($at) {
        
    }
    
    public function createAttachment($filename) {
        $this->mail->addAttachment($filename);
    }
    
    public function send() {
    	try {  
        	$this->mail->send();
    	}
    	catch (Exception $e) {
    		die($e);
		}
    }
    
    public function setHeaderEncoding($string){
    	
    	
    }
    
    private function error() {
    	$filename="/www/zendsvr/htdocs/mailerror/".date("YmdHis").".txt";
    	$myfile = fopen($filename, "w") or die("Unable to open file!");
		$txt = $this->mail->Subject;
		fwrite($myfile, $txt);		
		fclose($myfile);    
    }
    

}
