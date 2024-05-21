<?php
/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetGroepsWedstrijden2 extends Zend_View_Helper_Abstract
{
		
        public function GetGroepsWedstrijden2($wedstrijden, $groepsid, $input, $noimage=null){
           $groepen=array("1"=>"A","2"=>"B","3"=>"C","4"=>"D","5"=>"E","6"=>"F","7"=>"G","8"=>"H");
           $counter=0;
           $htm=null;
           if (!empty($wedstrijden[$groepsid])){           	
			 $htm  = "<div class='hoofding'>"."GROEP ". $groepen[$groepsid] ."</div>";
			 $htm .= "<div class='groep'>";
			 $htm .= "<table width='100%' cellspacing='1' cellpadding='1' border='0'>";
			 $htm .= "<tbody>";
			 
			 foreach ($wedstrijden[$groepsid] as $w) {
			 	
			 	
			 	$htm .= "<tr>";
			 		$htm .= "<td width='8%'>".trim($w['id'])."</td>";
			 		$htm .= "<td width='18%'>".trim($w['Datum'])." ".trim($w['Uur'])."</td>";
                                        $htm .= "<td width='30%' align='right'>".trim($w['thuis']);
                                        $htm .= "</td>";
			 		if (!$noimage) {
                                                $img = str_replace("ë","e", $w['thuis']);
			 			$htm .= "<td width='10%' align='right'>"." <img width='24'  src='/wkprono/public/images/vlaggen/".trim($img) .".png'>";
                                                $htm .= "</td>";
			 		}
			 		$htm .= "<td width='4%' align='center'>-</td>";
                                        if (!$noimage) {
                                            $htm .= "<td width='10%' align='left'>";
                                            $img = str_replace("ë","e", $w['uit']);
                                            $htm .= "<img width='24'  src='/wkprono/public/images/vlaggen/".trim($img) .".png'> ";
                                            $htm .= "</td>";
                                        }
			 		$htm .= "<td width='42%' align='left'>";			 		
			 		$htm .= trim($w['uit']);
			 		$htm .= "</td>";
			 		$htm .= "<td width='5%' align='center'>";
			 		$htm .=  $input[$w['id']]['input1'];
			 		$htm .= "</td>";
			 		$htm .= "<td width='6%' align='center'> - </td>";
			 		$htm .= "<td width='5%' align='center'>";
			 		$htm .=  $input[$w['id']]['input2'];
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

