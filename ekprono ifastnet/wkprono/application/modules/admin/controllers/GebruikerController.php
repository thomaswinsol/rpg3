<?php
class admin_GebruikerController extends My_Controller_Action
{
    public function detailAction()
    {
         $id = (int) $this->_getParam('id');        
         
         if (!empty($id)) {
            $params=array();
            $gebruikermodel=  new Application_Model_Gebruiker();
            $gebruiker = $gebruikermodel->getOne($id);
            $form  = new admin_Form_Gebruikerstatus($id,$params);
            $form->populate($gebruiker);
            $params['idrole'] = $gebruiker['idrole'];
         }         

         $this->view->form = $form;
         if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();
            if (!$form->isValid($postParams)) {
                return;
            }
            $formData  = $this->_request->getPost();
            $gebruikermodel=  new Application_Model_Gebruiker();
            $dbFields=array("status"=>$formData['status'],"idrole"=>$formData['idrole']);
            $gebruikermodel->update($dbFields, $formData['id']);
            $this->_helper->redirector('lijst', 'gebruiker', 'admin');
         }
    }
}

