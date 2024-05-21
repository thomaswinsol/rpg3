<?php
class Application_Form_Ploeg extends My_Form  {
    
        public function init(){
         // set the defaults
         $this->setMethod(Zend_Form::METHOD_POST);
         //$this->setAttrib('enctype', 'multiparts/form-data');
         $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);      
         $this->setAction('/wkprono/admin/ploegen');  
         $this->setAttrib('name', 'frmploeg');
         $this->setAttrib('id', 'frmploeg');
        
         $this->addElement(new Zend_Form_Element_Text('naam',array('label'=>"Naam",)));         
         $this->getElement('naam')->setAttribs(array("disabled" => "disabled", "readonly" => "readonly"));

       	 $this->addElement(new Zend_Form_Element_Checkbox('status',array('label'=>"Uitgeschakeld",)));
                       
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
