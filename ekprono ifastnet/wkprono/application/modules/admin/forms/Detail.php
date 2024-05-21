<?php
class admin_Form_Detail extends My_Form
{
    protected $_langFields;
    protected $_modelFields;
    protected $_languages;
    protected $_controller;
    protected $_statustabel;
    
    protected $_textareafields = array('inhoud');

    public function __construct($id = NULL, $params = NULL)
    {
	    $this->_langFields = $params['langFields'];
            $this->_modelFields = $params['modelFields'];
            $this->_languages  = $params['languages'];
            $this->_controller = $params['controller'];
            $this->_statustabel  = $params['status'];
            parent::__construct($id);
    }

    public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        $action='/admin/'.trim($this->_controller).'/detail';
        $this->setAction($action);

         // element ID
        $this->addElement(new Zend_Form_Element_Hidden('ID',array(
            'size'=>5,
            'readonly'=>true,
            )));

        // element label
        $this->addElement(new Zend_Form_Element_Text('label',array(
            'label'=>"lbllabel",
            'required'=>true,
            'size'=>15,
            'maxlength'=>20,
            'filters' => array('StringTrim')
            )));
        // element status
        If (empty($this->_statustabel)) {
                $elem = $this->createElement('select','status');
		   	$elem->setLabel("lblstatus")
			->addMultiOptions(array('1' => 'Actief' , '0' => 'Inactief') )
                        ->setRequired(true)
			->setSeparator('');
			$this->addElement($elem);
        }
        else {
            $model = "Application_Model_".trim($this->_statustabel);
            $detailModel = new $model;
            $defaultOptions = array('key'=> 'id', 'value' =>'omschrijving');
            $statuslist   = $detailModel->buildSelect($defaultOptions);
            $elem = new Zend_Form_Element_Select('status');
            $elem->setLabel('lblstatus')
                 ->setMultiOptions($statuslist)
                 ->addValidator('NotEmpty', TRUE)
                 ->setRequired(true);
           $this->addElement($elem);

        }

        // model fields
        foreach ($this->_modelFields as $modelfield) {
                    $field    =$modelfield['name'];
                    $fields[]=$field;
                    if ($modelfield['type']=='decimal') {
                        $this->addElement(new Zend_Form_Element_Text($field,array(
                        'label'=>"lbl".$field,
                        'size'=>10,
                        'required'=>$modelfield['required'],
                        'maxlength'=>10,
                        'filters' => array('StringTrim') ,
                        'class'=>"onlyDecimals",
                        )));
                    }                    
        }
        $this->setElementDecorators($this->elementDecorators);
        // translation fields
        foreach ($this->_languages as $language) {
   
                    $fields=array();
            foreach ($this->_langFields as $langfield) {
                    $field=$langfield."_".$language;
                    $fields[]=$field;

                    if (in_array($langfield, $this->_textareafields)) {
                         $this->addElement(new Zend_Form_Element_Textarea($field,array(
                        'label'=>$langfield,
                        'filters' => array('StringTrim'),
                        'cols'=>30,
                        'rows'=>6,
                        'validators' => array( array('StringLength',true, array('max'=>255)))
                        )));
                    }
                    else {
                        $this->addElement(new Zend_Form_Element_Text($field,array(
                        'label'=>$langfield,
                        'size'=>30,
                        'maxlength'=>50,
                        'filters' => array('StringTrim')
                        )));
                    }
            }
            $this->addDisplayGroup($fields, 'groups'.$language, array("legend" => $language));

            $group = $this->getDisplayGroup('groups'.$language);
            $group->setDecorators(array(
                'FormElements',
                'Fieldset',
                array('HtmlTag',array('tag'=>'div', 'closeOnly=>true', 'style'=>'float:right;margin-right:70px;'))
        ));



        }

         // element button
        $this->addElement(new Zend_Form_Element_Button('Opslaan', array(
            'type'=>"submit",
            'label'=>'btnOpslaan',
            'required'=> false,
            'ignore'=> true,
            'decorators'=>$this->buttonDecorators
            )));

    }


    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table' ,'class' => 'frm_01','style' => 'width:30%;')),
            'Form',
        ));
    }


}