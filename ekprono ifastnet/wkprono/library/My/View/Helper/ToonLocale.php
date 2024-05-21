<?php
class Zend_View_Helper_ToonLocale extends Zend_View_Helper_Abstract
{
    public function ToonLocale()
    {
        $html=null;
        $zendlocale= Zend_Registry::get('Zend_Locale');
       
        $locale[0] = array('locale'=>'nl_BE','omschrijving'=>'Nederlands');
        $locale[1] = array('locale'=>'fr_BE','omschrijving'=>'Français');

        $module    = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        $controller= Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $action    = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $params    = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        //var_dump($params);
        $paramurl=null;
        if (is_array($params)) {
            $inarray= array("controller","action","module","lang","error_handler");
            foreach ($params as $key => $value){
                if (!in_array($key, $inarray)) {
                    try {
                    $paramurl .="/".trim($key)."/".$value;
                    }
                    catch (Exception $e)
                    {
                        $paramurl="";
                    }
                }
            }
        }
        if (trim($module)=='default') {
            $url="/".trim($controller)."/".trim($action).trim($paramurl);
        } else {
            $url="/".trim($module)."/".trim($controller)."/".trim($action).trim($paramurl);
        }
        if (!empty($locale)) {
            $html .= "<ul>";
            foreach ($locale as $l) {
                $class= (trim($l['locale'])==$zendlocale)?"class='active'":'';
                $html .= "<li " . $class. ">";
                $html.= 
                "<a  href='".$this->view->baseUrl().$url.'/lang/'.$this->view->escape($l['locale']).
                "'>".$this->view->escape($this->view->translate($l['omschrijving'])) ."</a>"."&nbsp;&nbsp;";
                $html .= "</li>";
            }
            $html .= "<ul>";
        }        
        return $html;
    }

}

