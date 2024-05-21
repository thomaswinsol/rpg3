<?php 
class Zend_View_Helper_formatTimestamp extends Zend_View_Helper_Abstract
{

    public function formatTimestamp($data)
    {
    	
    	if (substr($data,2,1)=='/') {
    		$jaar = "20".substr($data, 6, 2);
    		$maand = substr($data, 3, 2);
    		$dag = substr($data, 0, 2);
    		$uur = substr($data, 10, 2);
    		$min = substr($data, 13, 2);
    		$sec = substr($data, 16, 2);
    	}
    	else {
    		$jaar = substr($data, 0, 4);
    		$maand = substr($data, 5, 2);
    		$dag = substr($data, 8, 2);
    		$uur = substr($data, 11, 2);
    		$min = substr($data, 14, 2);
    		$sec = substr($data, 17, 2);
    	}
    	
    	
    	$data =$dag. '-' . $maand .'-'.$jaar." ".$uur.':'.$min  ;
 		return ($data);
    }

}
?>