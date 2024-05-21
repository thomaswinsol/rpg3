<?php
/**
 * Abstract controller
 * Provides listing, edit and delete functionality
 */
abstract class My_Controller_Action extends Zend_Controller_Action
{
    protected $context;
    protected $baseUrl='/ekprono/wkprono/public';
    protected $flashMessenger = NULL;
    protected $mail;
    
    public function init()
    {
        $this->mail = new My_Controller_Plugin_Mail();
        $defaultNamespace = new Zend_Session_Namespace ();
        $module = $this->getRequest()->getModuleName();

        //$this->context = $_SESSION ['context'];        
        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->flashMessenger->setNamespace('Errors');       
    }    
   

    public function __destruct()
    {
        $this->SaveContext ();
    }

    public function SaveContext()
    {
        $_SESSION ['context'] = $this->context;
    }


    public function getFullUrl(){
        $bootstrap = $this->getInvokeArg('bootstrap');
        $options = $bootstrap->getOptions();
        return $options['website']['params']['url']; 
    }



   
}
