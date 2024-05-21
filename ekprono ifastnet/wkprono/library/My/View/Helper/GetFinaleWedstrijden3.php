<?php
/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetFinaleWedstrijden3 extends Zend_View_Helper_Abstract
{
		
        public function GetFinaleWedstrijden3($wedstrijden, $id, $input=1, $admin=1){
           $finale=array("9"=>"AchtsteFinale","10"=>"Kwartfinale","11"=>"Halvefinale","12"=>"Finale");
           $counter=0;
           $htm=null;
           if (!empty($wedstrijden[$id])){           	
			 $htm  = "<div class='hoofding'>"." ". $this->view->translate(trim($finale[$id])) ."</div>";
			 $htm .= "<div class='groep'>";
			 $htm .= "<table width='100%' cellspacing='1' cellpadding='1' border='0'>";
			 $htm .= "<tbody>";
			 
			 foreach ($wedstrijden[$id] as $w) {
			 	
			 	
			 	$htm .= "<tr>";
			 		$htm .= "<td width='8%'>";
			 		if (!$input) {
			 			if (!$admin) {
			 				$htm .="<a href='".$this->view->baseUrl()."/index/wedstrijden/id/".trim($w['id'])."'>";
			 			}	
			 			else {
			 				$htm .="<a href='".$this->view->baseUrl()."/admin/wedstrijden/id/".trim($w['id'])."'>";
			 			}	
			 		}
			 			$htm .= trim($w['id']);
			 		if (!$input) {
			 			$htm .="</a>";	
			 		}
			 		$htm .="</td>";			 	
			 		$htm .= "<td width='30%' align='right'>".$this->view->translate(trim($w['thuis']));
			 		$htm .= "</td>";
			 		$htm .= "<td width='4%' align='center'>-</td>";
			 		$htm .= "<td width='42%' align='left'>";
			 		$htm .= $this->view->translate(trim($w['uit']));
			 		$htm .= "</td>";
			 		$htm .= "<td width='5%' align='center'>";
			 		if ($input) {
			 			$htm .= "<input type='text' class='onlyDecimals' size='1' maxlength='2' name='". trim($w['id'])."_1' id='". trim($w['id'])."_1'>";
			 		}
			 		$htm .= "</td>";
			 		if ($input) {
			 			$htm .= "<td width='6%' align='center'> - </td>";
			 			$htm .= "<td width='5%' align='center'>";			 		
			 			$htm .= "<input type='text' class='onlyDecimals' size='1' maxlength='2' name='". trim($w['id']) ."_2' id='". trim($w['id']) ."_2'>";
			 		}
			 		else {
			 			$htm .= "<td width='11%' align='center' colspan='2'>".trim($w['uitslag']);
			 		}
			 		$htm .= "</td>";
			 	$htm .= "</tr>";
			 }
			 
			 $htm .= "</tbody>";
			 $htm .= "</table>";
			 $htm .= "</div>";
           }
           return $htm;
        }

}

