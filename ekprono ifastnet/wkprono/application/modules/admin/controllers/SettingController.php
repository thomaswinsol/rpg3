<?php
class admin_SettingController extends My_Controller_Action
{    
    public function setlocalelijstAction()
    {

        $form = new admin_Form_Locale;
        $localeModel = new Application_Model_Locale();
        $locale= $localeModel->getLocaleForm();
        if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();
            if (!$form->isValid($postParams)) {
                return;
            }
            $formData  = $this->_request->getPost();

            $values = implode(",", $formData['locale']);
            $data['status']=0;
            $localeModel->updateLocale($data,$values,"not");
            $data['status']=1;
            $localeModel->updateLocale($data,$values);
            $this->_helper->redirector('setlocalelijst', 'setting', 'admin');
        }
        $form->populate($locale);
        $this->view->form= $form;
       
    }

    public function setfirmaAction()
    {
        $form = new admin_Form_Firma;
        $firmaModel = new Application_Model_Firma();
        $id=1;
        $firma= $firmaModel->getOne($id);
        $form->populate($firma);
        $this->view->form= $form;
        if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();
            if (!$form->isValid($postParams)) {
                return;
            }
            $formData  = $this->_request->getPost();
            $dbFields=array("Firma"     => $formData['Firma'],
                            "Straat"    => $formData['Straat'],
                            "Postcode"  => $formData['Postcode'],
                            "Gemeente"  => $formData['Gemeente'],
                            "Tel"       => $formData['Tel'],
                            "Fax"       => $formData['Fax'],
                            "Email"     => $formData['Email'],
                            "BTWnummer" => $formData['BTWnummer'],
                            "Website"   => $formData['Website'],
             );
            $firmaModel->save($dbFields,$id);
            $this->context['firma']=$formData;
            $this->SaveContext();
            $this->_helper->redirector('setfirma', 'setting', 'admin');
        }

    }
}

