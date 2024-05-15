<?php
/**
* Description of Auth
*
* @author webmaster
*/
    class My_Auth_Auth extends Zend_Controller_Plugin_Abstract {
        private $_includeAuthActions = array(
            'winkelmand'  => array('winkelmandbestellen'),
        );


    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
                
        // If user is not logged in and is  requesting the bestelwinkelmand action
        // - redirect to registration page
        if (!$auth->hasIdentity() )
        {
            if($request->getModuleName()!='default') {
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $redirector->gotoUrl('/winkelmand/noaccess/error/2');
            }
            $controllerName = $request->getControllerName();
            $actionName     = $request->getActionName();           

            if (array_key_exists($controllerName,$this->_includeAuthActions)
                    && in_array($actionName,$this->_includeAuthActions[$controllerName])) {
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $redirector->gotoUrl('/winkelmand/noaccess/error/1');
            }
        }
        else {
            $acl = Zend_Registry::get('Zend_Acl');
            $gebruiker= $auth->getIdentity();
            $gebruikerroleModel = new Application_Model_Gebruikerrole();
            $role = $gebruikerroleModel->getOne($gebruiker->idrole);
            $resource = $request->getModuleName().'-'.$request->getControllerName();
            if($acl->has($resource)) {
                //role is een veld binnen onze user tabel
                $isAllowed = $acl->isAllowed($role['role'], // -> role, moet uit Db komen
                                $resource,
                                $request->getActionName());
                if(!$isAllowed) {
                    $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                    $redirector->gotoUrl('/winkelmand/noaccess/error/2');
                }
            }
        }
       
    }

}
?>