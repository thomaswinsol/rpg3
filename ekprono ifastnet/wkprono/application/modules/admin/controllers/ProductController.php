<?php
class admin_ProductController extends My_Controller_Action
{
    public function detailAction()
    {
         $id = (int) $this->_getParam('id');
         if (!empty($id)) {
              $params['controller'] = $this->getRequest()->getControllerName();
              $params['productid']  = $id;
              //Productfoto's
              $params['productdetail']  = "F";
              $this->view->fotoform = new admin_Form_Autocomplete(null,$params);
              $productfotoModel = new Application_Model_Productfoto();
              $this->view->productfotos=$productfotoModel->getAll("idproduct=".$id);
              //Productcategorieën
              $params['productdetail']  = "C";
              $this->view->categorieform = new admin_Form_Autocomplete(null,$params);
              $productcategorieModel = new Application_Model_Productcategorie();
              $this->view->productcategorie=$productcategorieModel->getCategorieForProduct($id);
         }
         $this->view->id=$id;
         parent::detailAction();
    }

    public function selecteerAction()
    {
        $productdetail = $this->_getParam('productdetail');
        $id = (int) $this->_getParam('id');
        $productid = (int) $this->_getParam('productid');

        if (trim($productdetail)=='F') {
            $productfotoModel = new Application_Model_Productfoto();
            $dbFields=array("idproduct"=>$productid,"idfoto"=>$id);
            $productfotoModel->save($dbFields);
        }
        if (trim($productdetail)=='C') {
            $productcategorieModel = new Application_Model_Productcategorie();
            $dbFields=array("idproduct"=>$productid,"idcategorie"=>$id);
            $productcategorieModel->save($dbFields);
        }
        $params = array('id' => $productid);
        $this->_helper->redirector('detail', 'product', 'admin', $params);
    }

    public function deleteproductdetailAction() {
            $formData = $this->_request->getPost();
            $this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout->disableLayout();
            $data = $formData;
            if (!empty($data)) {
                if (isset($data['btn_deletefoto_x']) || isset($data['btn_deletefoto'])){
                        $productfotoModel = new Application_Model_Productfoto();
                        $productfotoModel->deleteById($data['foto'],'id');
                }
                if (isset($data['btn_deletecategorie_x']) || isset($data['btn_deletecategorie'])){
                        $productcategorieModel = new Application_Model_Productcategorie();
                        $productcategorieModel->deleteById($data['cat'],'id');
                }
            }
            $params = array('id' => $data['productid']);
            $this->_helper->redirector('detail', 'product', 'admin', $params);
    }
    
}

