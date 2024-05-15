<?php

class admin_Form_Gebruikerstatus extends My_Form {

    public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        $this->setAction('/admin/gebruiker/detail');

        $whererole="id<=2";
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity() ) {
            $gebruiker= $auth->getIdentity();
            $userrole = $gebruiker->idrole;
            if ($userrole==3){
                $whererole=null;
            }
        }

        $elem = $this->createElement('select','status');
	$elem->setLabel("lblstatus")
	     ->addMultiOptions(array('1' => 'Actief' , '2' => 'Inactief') )
             ->setRequired(true)
	     ->setSeparator('');
	$this->addElement($elem);


        $model = new Application_Model_Gebruikerrole();
        $defaultOptions = array('key'=> 'id', 'value' =>'role');
        $roles  = $model->buildSelect($defaultOptions,$whererole);
        $elem = new Zend_Form_Element_Select('idrole');
        $elem->setLabel('lblrole')
                 ->setMultiOptions($roles)
                 ->addValidator('NotEmpty', TRUE)
                 ->setRequired(true);
        $this->addElement($elem);

        $this->setElementDecorators($this->elementDecorators);

        // element button
        $this->addElement(new Zend_Form_Element_Button('Opslaan', array(
            'type'=>"submit",
            'label'=>'btnOpslaan',
            'required'=> false,
            'ignore'=> true,
            'decorators'=>$this->buttonDecorators
            )));

    }

}

?>