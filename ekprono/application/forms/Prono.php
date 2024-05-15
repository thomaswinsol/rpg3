<?php
class Application_Form_Prono extends My_Form  {
    
        public function init(){
         // set the defaults
         $this->setMethod(Zend_Form::METHOD_POST);
         //$this->setAttrib('enctype', 'multiparts/form-data');
         $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
         // element naam
         $this->addElement(new Zend_Form_Element_Text('naam',array(
            'label'=>"naam",
            'required'=>true,
            'filters' => array('StringTrim')
            )));
            
         // element naam
         $this->addElement(new Zend_Form_Element_Text('email',array(
            'label'=>"email",
         	'validators' => array(
                            array('EmailAddress',true)),
            'required'=>true,
            'filters' => array('StringTrim')
            )));
             
          for ($ii=1; $ii<=51; $ii++) {
          		$field=$ii."_1";
          		$this->addElement(new Zend_Form_Element_Text($field,array(
            		'required'=>true,
            		'filters' => array('StringTrim')
            	)));
                        $field=$ii."_2";
          		$this->addElement(new Zend_Form_Element_Text($field,array(
            		'required'=>true,
            		'filters' => array('StringTrim')
            	)));
          }
          
		//Winnaar
          	$field="52";
          	$this->addElement(new Zend_Form_Element_Text($field,array(
            	'required'=>true,
            	'filters' => array('StringTrim')
            	)));
            	//Aantal doelpunten
            	$field="53";
          		$this->addElement(new Zend_Form_Element_Text($field,array(
            		'required'=>true,
            		'filters' => array('StringTrim')
            	)));

          
         $this->setElementDecorators($this->elementDecorators);  
        }
        
		public function isValid($data){   	
    		$valid = parent::isValid($data);
    		
    		if ($valid) {
    			$deelnemerModel = new Application_Model_Deelnemer();
    			$data['email']=strtolower($data['email']);
    			$deelnemer=$deelnemerModel->getOneAlpha($data['email'],'email');
    			if (!empty($deelnemer)) {
    				$this->getElement('naam')->addError('Er werd reeds gepronostikeerd voor dit e-mail adres.');
    				$valid=false;
    			}    			
    		}
    		if ($valid) {
    			$arr=array();
                        $ploegModel = new Application_Model_Ploeg();
                        $ploegen= $ploegModel->getPloegen();
                        $groepen= $ploegModel->getGroepen();
                        $arr2=array();
    			for ($ii=37; $ii<=44; $ii++) {
    				if (isset($arr[$data[$ii."_1"]]) ) {
    					$this->getElement('naam')->addError('Achtste finales: Fouten ->wedstrijd '.$ii.":".$ploegen[$data[$ii."_1"]]. " reeds geselecteerd.");
    					$valid=false;
    					break;
    				}	
                    if (isset($arr[$data[$ii."_2"]]) ) {
    					$this->getElement('naam')->addError('Achtste finales: Fouten ->wedstrijd '.$ii.":".$ploegen[$data[$ii."_2"]]. " reeds geselecteerd.");
    					$valid=false;
    					break;
    				}	
    				$arr[$data[$ii."_1"]]=1;
    				$arr[$data[$ii."_2"]]=1;
                    $grp=$groepen[$data[$ii."_1"]];
                    $arr2[$grp]=!isset($arr2[$grp])?1:$arr2[$grp]+1;
                    $grp=$groepen[$data[$ii."_2"]];
                    $arr2[$grp]=!isset($arr2[$grp])?1:$arr2[$grp]+1;                    
    			}   
                if ($valid){
                    $g=array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F');
                    for ($ii=1;$ii<=6; $ii++){
                        if ($arr2[$ii]>3){
                            $this->getElement('naam')->addError('Achtste finales: Fouten ->groep '.$g[$ii].":". " er zijn 4 ploegen geselecteerd van groep ".$g[$ii]);
    					    $valid=false;
                            break;
                        }
                    }
                }

    			$arr=array();
    			for ($ii=45; $ii<=48; $ii++) {
    				if (isset($arr[$data[$ii."_1"]]) ) {
    					$this->getElement('naam')->addError('Kwartfinale: Fouten ->wedstrijd '.$ii.":".$ploegen[$data[$ii."_1"]]. " reeds geselecteerd.");
    					$valid=false;
    					break;
    				}
				if (isset($arr[$data[$ii."_2"]]) ) {
    					$this->getElement('naam')->addError('Kwartfinale: Fouten ->wedstrijd '.$ii.":".$ploegen[$data[$ii."_2"]]. " reeds geselecteerd.");
    					$valid=false;
    					break;
    				}
    				$arr[$data[$ii."_1"]]=1;
    				$arr[$data[$ii."_2"]]=1;
    			}   
    			
    			$arr=array();
    			for ($ii=49; $ii<=50; $ii++) {
    				if (isset($arr[$data[$ii."_1"]]) ) {
    					$this->getElement('naam')->addError('Halve finale: Fouten ->wedstrijd '.$ii.":".$ploegen[$data[$ii."_1"]]. " reeds geselecteerd.");
    					$valid=false;
    					break;
    				}
				if (isset($arr[$data[$ii."_2"]]) ) {
    					$this->getElement('naam')->addError('Halve finale: Fouten ->wedstrijd '.$ii.":".$ploegen[$data[$ii."_2"]]. " reeds geselecteerd.");
    					$valid=false;
    					break;
    				}
    				$arr[$data[$ii."_1"]]=1;
    				$arr[$data[$ii."_2"]]=1;
    			}   
                        
                        $arr=array();
    			for ($ii=51; $ii<=51; $ii++) {
    				if (isset($arr[$data[$ii."_1"]]) ) {
    					$this->getElement('naam')->addError('Finale: Fouten ->wedstrijd '.$ii."-".$ploegen[$data[$ii."_1"]]. " reeds geselecteerd.");
    					$valid=false;
    					break;
    				}
				if (isset($arr[$data[$ii."_2"]]) ) {
    					$this->getElement('naam')->addError('Finale: Fouten ->wedstrijd '.$ii."-".$ploegen[$data[$ii."_2"]]. " reeds geselecteerd.");
    					$valid=false;
    					break;
    				}
    				$arr[$data[$ii."_1"]]=1;
    				$arr[$data[$ii."_2"]]=1;
    			}  
                        if ($data[52]==0 || $data[52]>999) {                                
    				$this->getElement('naam')->addError('Aantal doelpunten ongeldig');
    				$valid=false;    				
                        }

    			
    		}    	    	
    		return $valid;
    	}
    	

}
?>
