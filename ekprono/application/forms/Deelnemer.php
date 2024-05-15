<?php
class Application_Form_Deelnemer extends My_Form  {
    
        public function init(){
         // set the defaults
         $this->setMethod(Zend_Form::METHOD_POST);
         //$this->setAttrib('enctype', 'multiparts/form-data');
         $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);      
         $this->setAction('/wkprono/admin/deelnemers');  
         $this->setAttrib('name', 'frmdeelnemer');
         $this->setAttrib('id', 'frmdeelnemer');
        
         $this->addElement(new Zend_Form_Element_Text('naam',array('label'=>"Naam",)));
         
         $this->getElement('naam')->setAttribs(array("disabled" => "disabled", "readonly" => "readonly"));
          // afdeling
         $afdModel = new Application_Model_Afdeling();
         $defaultOptions = array('key'=> 'id', 'value' =>'naam', 'emptyRow' => False);
         $afd = $afdModel->buildSelect($defaultOptions, null, "naam");
         $elem = new Zend_Form_Element_Select('afdeling');
         $elem->setLabel('Afdeling')
              ->setMultiOptions($afd);
         $this->addElement($elem);
        
        
       	 $this->addElement(new Zend_Form_Element_Checkbox('betaald',array('label'=>"Betaald?",)));
                       
         $this->setElementDecorators($this->elementDecorators);  
         
         $this->addElement(new Zend_Form_Element_Hidden('id',array()));
         
         $this->addElement(new Zend_Form_Element_Button('Opslaan', array(
            'type'=>"submit",
            'required'=> false,
            'ignore'=> true,
            'decorators'=>$this->buttonDecorators
            )));
        }
        
		public function isValid($data){   	
    		$valid = parent::isValid($data);    		
    			
    		return $valid;
    	}
    	

}
?>
