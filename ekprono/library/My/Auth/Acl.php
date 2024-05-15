<?php
/**
* Description of Acl
*
* @author webmaster
*/
class My_Auth_Acl extends Zend_Controller_Plugin_Abstract {
    //put your code here

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $acl = new Zend_Acl();
        $roles = array('USER', 'DEALER', 'ADMIN');
        $controllers = array('admin-index', 'admin-product', 'admin-foto', 'admin-categorie', 'admin-pagina', 'admin-gebruiker', 'admin-setting');
        foreach($roles as $role) {
            $acl->addRole($role);
        }

        foreach($controllers as $controller) {
            //$acl->addResource($controller); -> nieuwe manier
            $acl->add(new Zend_Acl_Resource($controller));
        }
        $acl->allow('ADMIN');  //acces to everything
        $acl->allow('DEALER'); //acces to everything
        $acl->deny('DEALER', 'admin-setting');
        Zend_Registry::set('Zend_Acl', $acl);

    }
}

?>
