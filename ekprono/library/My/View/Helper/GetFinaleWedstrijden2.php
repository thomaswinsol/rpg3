<?php
/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetFinaleWedstrijden2 extends Zend_View_Helper_Abstract
{
		
        public function GetFinaleWedstrijden2($id, $ploegen, $wedstrijden, $input){
           $finale=array("8"=>"AchtsteFinale","4"=>"Kwartfinale","2"=>"Halvefinale","1"=>"Finale");
           $htm=null;        	
			 $htm  = "<div class='hoofding'>". $finale[$id] ."</div>";
			 $htm .= "<div class='groep'>";
			 $htm .= "<table width='100%' cellspacing='1' cellpadding='1' border='0'>";
			 $htm .= "<tbody>";

			
			 
		     if (!empty($wedstrijden)) {
		     	foreach ($wedstrijden as $w) {			
		     		$field1=$w['id']."_1";
			 		$field2=$w['id']."_2";			 		
			 		$htm .= "<tr>";
			 		$htm .= "<td width='8%'>".trim($w['id'])."</td>";			 	
			 		$htm .= "<td width='30%' align='right'>";
			 		if (substr($ploegen[$input[$w['id']]['input1']],0,1)=='*') {
			 			$htm .= "<span style='text-decoration:line-through;color:red;'> ".substr($ploegen[$input[$w['id']]['input1']],1,30)."</span>";
			 		}
			 		else {
			 			$htm .= " ".$ploegen[$input[$w['id']]['input1']];
			 		}			 		
			 		$htm .= " - ";
                                        if (substr($ploegen[$input[$w['id']]['input2']],0,1)=='*') {
			 			$htm .= "<span style='text-decoration:line-through;color:red;'> ".substr($ploegen[$input[$w['id']]['input2']],1,30)."</span>";
			 		}
			 		else {
			 			$htm .= " ".$ploegen[$input[$w['id']]['input2']];
			 		}
			 		$htm .= "</td>";
			 		$htm .= "<td width='4%' align='center'></td>";			 		
			 		$htm .= "<td width='42%' align='left'>";
			 		$htm .= " ".$w['thuis']."";
			 		$htm .= "  ";
			 		$htm .= " ".$w['uit']." ";		 		
			 		$htm .= "</td>";
			 		$htm .= "</tr>";
			 		
		     	}
		     }	
			 $htm .= "</tbody>";
			 $htm .= "</table>";
			 $htm .= "</div>";

           return $htm;
        }


}

