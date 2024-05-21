<?php
class Zend_View_Helper_ShowMessages extends Zend_View_Helper_Abstract
{

    public function ShowMessages($flashmessenger)
    {
        if (empty($flashmessenger)) {
            return null;
        }
        $message=null;
        $message .= '<div class="msg_ok">';
        $msg_error=null;
        if (!empty($flashmessenger)){
		foreach($flashmessenger as $v){                        
                        // error message
                        if (substr($v, 0 , 1) == '-') {
                            $msg_error='msg_error';
                        }
			$message .= $this->view->escape($v) . '<br />' .  PHP_EOL;                        
		}
                if (!empty($msg_error)){
                    $message= str_replace('msg_ok',$msg_error, $message);
                }
        }
        return $message;
    }

}


