<?php
class My_Controller_Plugin_Navigation extends Zend_Controller_Plugin_Abstract
{
/**
 * 
 * @param \Zend_Controller_Request_Abstract $request
 * @return \Zend_Navigation
 */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        //make navigation
        $role=null;
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity() ) {
            $gebruiker= $auth->getIdentity();
            $role = $gebruiker->idrole;
        }

        $container = new Zend_Navigation;
        $urls = array (
         //array ( 'label'=> 'Wedstrijden','module'=>'default', 'action'=> 'wedstrijden', 'controller'=> 'index', 'params'=> array() ),
         array ( 'label'=> 'Pronostikeer', 'module'=>'default', 'action'=> 'prono', 'controller'=> 'index', 'params'=> array() ),
         array ( 'label'=> 'Spelregels', 'module'=>'default', 'action'=> 'spelregels', 'controller'=> 'index', 'params'=> array() ),
         //array ( 'label'=> 'Klassement', 'module'=>'default', 'action'=> 'klassement', 'controller'=> 'index', 'params'=> array() ),
         array ( 'label'=> 'Deelnemers', 'module'=>'default', 'action'=> 'deelnemers', 'controller'=> 'index', 'params'=> array() ),
         array ( 'label'=> 'Admin', 	 'module'=>'default', 'action'=> 'index', 	   'controller'=> 'admin', 'params'=> array() ),
        );
        if (!empty($role)) {
        	$urls[3]=array ( 'label'=> 'Deelnemers', 'module'=>'default', 'action'=> 'deelnemers', 'controller'=> 'admin', 'params'=> array() );
        	$urls[4]=array ( 'label'=> 'Uitslagen', 'module'=>'default', 'action'=> 'wedstrijden', 'controller'=> 'admin', 'params'=> array() );
        	$urls[5]=array ( 'label'=> 'Ploegen', 'module'=>'default', 'action'=> 'ploegen', 'controller'=> 'admin', 'params'=> array() );
        	$urls[6]=array ( 'label'=> 'Logout', 'module'=>'default', 'action'=> 'logout', 'controller'=> 'gebruiker', 'params'=> array() );
        }
        foreach  ($urls as $url) {
            $param=null;
            if (!empty($url['params'])) {
                /*$urlparam=explode(',',$url['params']);
                $param[$urlparam[0]]=1;*/
            	$param['view']=1;
            }
            
            $page = new Zend_Navigation_Page_Mvc(array(
                'label' => $url['label'] ,
                'module' => $url['module'],
                'action'=> $url['action'],
                'controller'=> $url['controller'],
                //'route'=> 'default',
                'params'=> $param,
            ));
            $container->addPage($page);
        }
        Zend_Registry::set('Zend_Navigation', $container);
        return $container;
        
    }
}

?>
