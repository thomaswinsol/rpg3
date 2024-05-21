<?php
class admin_Form_Autocomplete extends My_Form
{

    protected $_controller;
    protected $_productid;
    protected $_productdetail;

    public function __construct($id = NULL, $params = NULL)
    {
	    $this->_controller = $params['controller'];
            if (isset($params['productid'])) {
                $this->_productid     = $params['productid'];
                $this->_productdetail = $params['productdetail'];
            }
            parent::__construct($id);
    }

    public function init()
    {
        if ( !empty($this->_productid) ) {
            
            $location='/admin/product/selecteer/productid/'.$this->_productid.'/productdetail/'.$this->_productdetail.'/id/';
            if (trim($this->_productdetail)=="F") {
                $label = "lblFoto";
                $source  ='/admin/foto/autocomplete';
                $elem = new ZendX_JQuery_Form_Element_AutoComplete("AutocompleteF", array('label' => $label, 'size'=>30 , 'maxlength'=>8));
            }
            else {
                $label = "lblCategorie";
                $source  ='/admin/categorie/autocomplete';
                $elem = new ZendX_JQuery_Form_Element_AutoComplete("AutocompleteC", array('label' => $label, 'size'=>30 , 'maxlength'=>8));
            }
        }
        else {
            if (trim(strtolower($this->_controller))=='gebruiker') {
                 $source  ='/admin/'. trim($this->_controller). '/autocomplete/id/email';
            }
            else {
                $source  ='/admin/'. trim($this->_controller). '/autocomplete';
            }
            $location='/admin/'. trim($this->_controller). '/detail/id/';
            $label = "lbl".trim(ucfirst($this->_controller));
            $elem = new ZendX_JQuery_Form_Element_AutoComplete("Autocomplete", array('label' => $label, 'size'=>30 , 'maxlength'=>8));
        }
        
       
	$elem->setJQueryParam('source', $source);
	$elem->setJQueryParams( array("minLength"=>0, "select" => new Zend_Json_Expr(
	    							"function(event, ui) {
                                                                        location.href='$location'+ui.item.id; }") ));
     	$elem->setDecorators($this->formJQueryElements);
  	$this->addElements( array ($elem));
        
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