<?php
/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_GetGroepsWedstrijden extends Zend_View_Helper_Abstract
{
	
	public function GetGroepsWedstrijden($wedstrijden, $groepsid, $input=1, $admin=1, $value=null){
           $groepen=array("1"=>"A","2"=>"B","3"=>"C","4"=>"D","5"=>"E","6"=>"F","7"=>"G","8"=>"H");
           $counter=0;
           $htm=null;
           if (!empty($wedstrijden[$groepsid])){           	
			 $htm  = "<div class='hoofding'>".$this->view->translate("GROEP")." ". $groepen[$groepsid] ."</div>";
			 $htm .= "<div class='groep'>";
			 $htm .= "<table width='100%' cellspacing='1' cellpadding='1' border='0'>";
			 $htm .= "<tbody>";
			 $baseUrl="/ekprono/wkprono/public";
                   //$baseUrl="";
			 foreach ($wedstrijden[$groepsid] as $w) {
			 	
			 	
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
			 		$htm .= "<td width='18%'>".trim($w['Datum'])." ".trim($w['Uur'])."</td>";			 		 	
			 		$htm .= "<td width='30%' align='right'>".$this->view->translate(trim($w['thuis']));
			 		$htm .= "</td>";
                                        $img = str_replace("ë","e", $w['thuis']);
			 		$htm .= "<td width='10%' align='right'>"." <img width='24'  src='" .$baseUrl."/images/vlaggen/".trim($img) .".png'>";
			 		$htm .= "</td>";
			 		$htm .= "<td width='4%' align='center'>-</td>";
			 		
			 		$htm .= "<td width='10%' align='left'>";
                                        $img = str_replace("ë","e", $w['uit']);
			 		$htm .= "<img width='24'  src='" .$baseUrl."/images/vlaggen/".trim($img) .".png'> ";
			 		$htm .= "</td>";
			 		
			 		$htm .= "<td width='42%' align='left'>";
			 		$htm .= $this->view->translate(trim($w['uit']));
			 		$htm .= "</td>";
			 		$htm .= "<td width='5%' align='center'>";
			 		if ($input) {
                                                if (!empty($value)) {
                                                        $htm .= "<input type='number' class='onlyDecimals' size='1' maxlength='2' min='0' max='25' value='".trim($value[$w['id']]['input1'])."' name='". trim($w['id'])."_1' id='". trim($w['id'])."_1'>";
                                                }
                                                else {
                                                        $htm .= "<input type='number' class='onlyDecimals' size='1' maxlength='2' min='0' max='25' name='". trim($w['id'])."_1' id='". trim($w['id'])."_1'>";
                                                }                                                
			 			
			 		}
			 		$htm .= "</td>";
			 		if ($input) {
			 			$htm .= "<td width='6%' align='center'> - </td>";
			 			$htm .= "<td width='5%' align='center'>";	
                                                if (!empty($value)) {
                                                    $htm .= "<input type='number' class='onlyDecimals' size='1' maxlength='2' min='0' max='25' value='".trim($value[$w['id']]['input2'])."' name='". trim($w['id']) ."_2' id='". trim($w['id']) ."_2'>";
                                                }
                                                else {
                                                    $htm .= "<input type='number' class='onlyDecimals' size='1' maxlength='2' min='0' max='25' name='". trim($w['id']) ."_2' id='". trim($w['id']) ."_2'>";
                                                }

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

