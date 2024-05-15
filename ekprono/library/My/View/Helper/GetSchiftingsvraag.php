<?php
/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetSchiftingsvraag extends Zend_View_Helper_Abstract
{
		
        public function GetSchiftingsvraag ($id){
           $finale=array("1"=>"Schiftingsvraag");
           $htm=null;        	
			 $htm  = "<div class='hoofding'>". $this->view->translate($finale[$id]) ."</div>";
			 $htm .= "<div class='groep'>";
			 $htm .= "<table width='100%' cellspacing='1' cellpadding='1' border='0' style='background-color:whitesmoke;'>";
			 $htm .= "<tbody>";
					
		 		
			 		$htm .= "<tr>";
					$htm .= "<td colspan=2 style='text-align:center;'>";
					$htm .= $this->view->translate("Aantal doelpunten gescoord tijdens het hele EK").": ";
					$htm .= "<input type='number' class='schifting' min='1' max='999' name='53' id='53' class='onlyDecimals' size='4' maxlength='3'>";
			 		$htm .= "</td>";		 		
			 		$htm .= "</tr>";
			 		//$htm .= "<tr>";
					//$htm .= "<td colspan='2' style='text-align:center;'>";					
					
			 		//$htm .= "</td>";		 		
			 		//$htm .= "</tr>";

			 $htm .= "</tbody>";
			 $htm .= "</table>";
			 $htm .= "</div>";

           return $htm;
        }
        


}

