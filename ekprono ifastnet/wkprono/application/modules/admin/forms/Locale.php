<?php
class admin_Form_Locale extends My_Form {

    public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        $this->setAction('/admin/setting/setlocalelijst');

        $localeModel = new Application_Model_Locale();
        $defaultOptions = array('key'=> 'id', 'value' =>'omschrijving', 'emptyRow' => False);
        $locale = $localeModel->buildSelect($defaultOptions);
        $elem = $this->createElement('MultiCheckbox','locale');
		   	$elem->setLabel("lbllocale")
			->addMultiOptions($locale )
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

    public function populate(array $values)
    {
		parent::populate($values);
    }
}
?>