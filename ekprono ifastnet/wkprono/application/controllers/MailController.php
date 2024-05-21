<?php

class MailController extends Zend_Controller_Action
{
	
	public function init() {
		
	}

        
	public function mailAction()
    {
                $mail = new My_Controller_Plugin_Mail();	
                $templateName = My_Controller_Plugin_Mail::TEMPLATE_WKPRONO;

                $data=array();
                $id = $this->_getParam('id');    	
				$deelnemerModel = new Application_Model_Deelnemer();
        		$data[0] = $deelnemerModel->getOne($id);
        
        		$deelnemerinputModel = new Application_Model_Deelnemerinput();
        		$where="id_deelnemer in (".(int)$id.")";
        		$data[1] = $deelnemerinputModel->getAll($where);
        
        		$ploegenModel = new Application_Model_Ploeg();
        		$data[2] = $ploegenModel->getPloegen();           

        		$wedstrijdModel = new Application_Model_Wedstrijd();
        		$data[3] = $wedstrijdModel->getAll();       
    	 	
                $mail->send($templateName,$data);  
                die("ok");
    }

}

