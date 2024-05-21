<?php
/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetFinaleWedstrijd extends Zend_View_Helper_Abstract
{
		
        public function GetFinaleWedstrijd($id, $wedstrijden){
           $finale=array("1"=>"Winnaar");
           $htm=null;        	
			 $htm  = "<div class='hoofding'>". $this->view->translate(trim($finale[$id])) ."</div>";
			 $htm .= "<div class='groep'>";
			 $htm .= "<table width='100%' cellspacing='1' cellpadding='1' border='0'>";
			 $htm .= "<tbody>";

		     if (!empty($wedstrijden)) {
		     	foreach ($wedstrijden as $w) {
		     		$field1=$w['id'];		 		
			 		$htm .= "<tr>";
			 		$htm .= "<td width='8%'>".trim($w['id'])."</td>";			 	
			 		$htm .= "<td  width='40%' align='right'>";
			 		$options=null;		 		
			 		$htm .=  $this->view->formSelect($field1,null,array('class'=>$finale[$id]),$options );
			 		$htm .= " ".$w['thuis'];
			 		$htm .= "</td>";		 		
			 		$htm .= "<td wiwidth='50%'dth='50%'></td>";
			 		$htm .= "</tr>";
			 		
		     	}
		     }	
			 $htm .= "</tbody>";
			 $htm .= "</table>";
			 $htm .= "</div>";

           return $htm;
        }
        


}

