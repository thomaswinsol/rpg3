<?php
class Application_Form_Login extends My_Form {

    public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);

        $this->setAction('/ekprono/wkprono/public/gebruiker/login');

        // element naam
        $this->addElement(new Zend_Form_Element_Text('email',array(
            'label'=>"E-mail",
            'required'=>true,
            'size'=>35,
            // filters
            'validators' => array(
                            array('EmailAddress',true)),
            'filters' => array('StringTrim')
            )));

        // element wachtwoord
        $this->addElement(new Zend_Form_Element_Password('paswoord',array(
            'label'=>"Wachtwoord",
            'required'=>true,
            // filters
            'filters' => array('StringTrim')
            )));

         $this->setElementDecorators($this->elementDecorators);
         
         // element button
        $this->addElement(new Zend_Form_Element_Button('inloggen', array(
            'type'=>"submit",
            'label'=>'Inloggen',
            'required'=> false,
            'ignore'=> true,
            'decorators'=>$this->buttonDecorators
            )));

    }

}

?>