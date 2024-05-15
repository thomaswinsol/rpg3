<?php
class GebruikerController extends My_Controller_Action
{
    /*
    * Login
    */
    public function loginAction()
    {
		//$this->_helper->layout->disableLayout();  
		$this->flashMessenger->setNamespace('Errors');
        $this->view->flashMessenger = $this->flashMessenger->getMessages(); 
        $form = new Application_Form_Login();
        if (!$this->getRequest()->isPost()) {
        	$this->view->form = $form; 
            return false;
        }
        
        $this->_helper->viewRenderer->setNoRender();
        $formData = $values = $this->_request->getPost();
        if (!$form->isValid($formData)) {
            $this->flashMessenger->setNamespace('Errors');
            $this->flashMessenger->addMessage('-Invalid user or password');
            $this->_helper->redirector('index', 'admin');
        }

        $adapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table::getDefaultAdapter() // set earlier in Bootstrap
        );

        $adapter->setTableName('gebruiker'); 
        $adapter->setIdentityColumn('email'); 
        $adapter->setCredentialColumn('paswoord'); 
        $adapter->setIdentity($values['email']);
        $adapter->setCredential($values['paswoord']); 
        $adapter->setCredentialTreatment('md5(?) AND status = 1');
        //$adapter->setCredentialTreatment('? AND status = 1');
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter); 
       
        // authentication OK
        if ($result->isValid())
        { 
            $gebruikerModel = new Application_Model_Gebruiker();
            $gebruiker = $gebruikerModel->getOneByField('email', $formData['email']);
            
            $auth->getStorage()
                ->write($adapter->getResultRowObject(null, "password"));
            $identity = $adapter->getResultRowObject();  
            $this->_helper->redirector('deelnemers', 'admin');         
        } else
        {        
            $this->flashMessenger->setNamespace('Errors');
            $this->flashMessenger->addMessage('-Authentication failed');
            $this->_helper->redirector('index', 'admin');
        }
       
    }

        
    /**
     * Log out a user
     */
    public function logoutAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        Zend_Auth::getInstance()->clearIdentity();
        $this->SaveContext();
        $this->_helper->redirector('index','index');
    }
   
}