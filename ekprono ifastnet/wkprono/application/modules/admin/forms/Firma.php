<?php

class admin_Form_Firma extends My_Form {

    public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        $this->setAction('/admin/setting/setfirma');

         // element firma
        $this->addElement(new Zend_Form_Element_Text('Firma',array(
            'label'=>"lblFirma",
            'required'=>true,
            'size'=>50,
            'maxlength'=>50,
            'required'=>true,
            'filters' => array('StringTrim')
            )));

          // element straat
        $this->addElement(new Zend_Form_Element_Text('Straat',array(
            'label'=>"lblStraat",
            'required'=>true,
            'size'=>35,
            'maxlength'=>50,
            'required'=>true,
            'filters' => array('StringTrim')
            )));

           // element postcode
        $this->addElement(new Zend_Form_Element_Text('Postcode',array(
            'label'=>"lblPostcode",
            'required'=>true,
            'size'=>10,
            'required'=>true,
            'filters' => array('StringTrim')
            )));

           // element gemeente
        $this->addElement(new Zend_Form_Element_Text('Gemeente',array(
            'label'=>"lblGemeente",
            'required'=>true,
            'size'=>30,
            'required'=>true,
            'filters' => array('StringTrim')
            )));

           // element telefoon
        $this->addElement(new Zend_Form_Element_Text('Tel',array(
            'label'=>"lblTel",
            'required'=>true,
            'size'=>30,
            'required'=>true,
            'filters' => array('StringTrim')
            )));

           // element fax
        $this->addElement(new Zend_Form_Element_Text('Fax',array(
            'label'=>"lblFax",
            'required'=>true,
            'size'=>30,
            'required'=>true,
            'filters' => array('StringTrim')
            )));

            // element btw-nummer
        $this->addElement(new Zend_Form_Element_Text('BTWnummer',array(
            'label'=>"lblBTWnummer",
            'required'=>true,
            'size'=>30,
            'required'=>true,
            'filters' => array('StringTrim')
            )));

           // element email
        $this->addElement(new Zend_Form_Element_Text('Email',array(
            'label'=>"lblEmail",
            'required'=>true,
            'size'=>50,
            'required'=>true,
             'validators' => array(
                            array('EmailAddress',true)),
            'filters' => array('StringTrim')
            )));

           // element website
        $this->addElement(new Zend_Form_Element_Text('Website',array(
            'label'=>"lblFax",
            'required'=>true,
            'size'=>50,
            'required'=>true,
            'filters' => array('StringTrim')
            )));


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