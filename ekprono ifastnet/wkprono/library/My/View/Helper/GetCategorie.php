<?php
/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetCategorie extends Zend_View_Helper_Abstract
{
		
        public function GetCategorie($productId){
            $productcategorieModel = new Application_Model_Productcategorie();
            $categorie = $productcategorieModel->getCategorieForProduct((int)$productId);
            if (!empty($categorie)) {
                foreach ($categorie as $c) {
                    echo '<br/>';
                    echo (!empty($c['titel']))?$this->view->escape($c['titel']):$this->view->escape($c['label']);
                }
            }
        }


}

