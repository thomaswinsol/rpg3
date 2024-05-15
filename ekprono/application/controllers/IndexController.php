<?php
class IndexController extends My_Controller_Action
{

    public function indexAction()
    {
    	/*define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
		define('DB_PORT', getenv('OPENSHIFT_MYSQL_DB_PORT'));
		define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
		define('DB_PASS', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));*/

		/*echo DB_HOST;
		ECHO "xxx";
		echo DB_PORT;
		ECHO "xxx";
		echo DB_USER;
		ECHO "xxx";
		echo DB_PASS;*/
		
        $mobile=$this->checkmobile();
        if ($mobile) {
            $this->_helper->redirector('prono', 'index');
        }   
        else {        
            $this->_helper->redirector('prono', 'index');
        }
        
    }

    public function mailAction()
    {
         $id = $this->_getParam('id'); 
         $this->sendmail($id);
         die("ok");
    }

	private function sendmail($id) {
    			$mail = new My_Controller_Plugin_Mail();	
                $templateName = My_Controller_Plugin_Mail::TEMPLATE_EKPRONO;

                $data=array();
                //$id = $this->_getParam('id');    	
			    $deelnemerModel = new Application_Model_Deelnemer();
        		$data[0] = $deelnemerModel->getOne($id);
                
        		$deelnemerinputModel = new Application_Model_Deelnemerinput();
        		$where="id_deelnemer in (".(int)$id.")";
        		$data[1] = $deelnemerinputModel->getAll($where);
        
        		$ploegenModel = new Application_Model_Ploeg();
        		$data[2] = $ploegenModel->getPloegen();           

        		$wedstrijdModel = new Application_Model_Wedstrijd();
        		$data[3] = $wedstrijdModel->getAll();

                ob_clean();
                $deelnemerModel->buildPdf($data,$id);       
                $mail->send($templateName,$data);  
    }


    public function homeAction()
    {
        $this->flashMessenger->setNamespace('Errors');
        $this->view->flashMessenger = $this->flashMessenger->getMessages();
    }

    public function pronowijzigenAction()
    {
        die("De pronostiek is afgesloten");   
        $id = $this->_getParam('id');
        if (empty($id)) {
                $this->_helper->redirector('prono', 'index', 'default');
        }
        $mobile=$this->checkmobile();
        if ($mobile) {
            $this->_helper->redirector('prono2', 'index', 'default');
        }    	    	 
    	$ploegenModel = new Application_Model_Ploeg();
        $this->view->ploegen= $ploegenModel->getAll(); 
        $this->view->ploegen2= $ploegenModel->getPloegen(); 
            
        $this->flashMessenger->setNamespace('Errors');
        $this->view->flashMessenger = $this->flashMessenger->getMessages();
        
        $wedstrijdModel = new Application_Model_Wedstrijd();
        $this->view->wedstrijden = $wedstrijdModel->getAll();
        
        
        $deelnemerModel = new Application_Model_Deelnemer();
        $this->view->deelnemer = $deelnemerModel->getOne($id);
        
        $this->view->result2 = $deelnemerModel->getScoreGroep($id);            
        $this->view->result = $deelnemerModel->getWedstrijden($id,48);
        
        $deelnemerinputModel = new Application_Model_Deelnemerinput();
        $where="id_deelnemer in (".(int)$id.")";
        $this->view->deelnemerinput = $deelnemerinputModel->getAll($where);
       
    }


    public function pronoAction()
    {
        //die("De pronostiek is afgesloten");   
        $mobile=$this->checkmobile();
        if ($mobile) {
            $this->_helper->redirector('prono2', 'index', 'default');
        }    	    	 
    	$ploegenModel = new Application_Model_Ploeg();
        $this->view->ploegen= $ploegenModel->getAll();            
            
        $this->flashMessenger->setNamespace('Errors');
        $this->view->flashMessenger = $this->flashMessenger->getMessages();
        
        $wedstrijdModel = new Application_Model_Wedstrijd();
        $this->view->wedstrijden = $wedstrijdModel->getAll();
    }

    public function prono2Action()
    {
        //die("De pronostiek is afgesloten");        	    	 
    	$ploegenModel = new Application_Model_Ploeg();
        $this->view->ploegen= $ploegenModel->getAll();
            
        $this->flashMessenger->setNamespace('Errors');
        $this->view->flashMessenger = $this->flashMessenger->getMessages();
        
        $wedstrijdModel = new Application_Model_Wedstrijd();
        $this->view->wedstrijden = $wedstrijdModel->getAll();
    }
    
        private function checkmobile(){
	   if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) return true;
	   if(preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"])) return true;
	   if(isset($_SERVER["HTTP_USER_AGENT"])){
	      $badmatches = array("OfficeLiveConnector","MSIE\  8\.0","OptimizedIE8","MSN\ Optimized","Creative\ AutoUpdate","Swapper");	 
	      foreach($badmatches as &$badstring){
	        if(preg_match("/".$badstring."/i",$_SERVER["HTTP_USER_AGENT"])) return  false;
	      }
	      $uamatches = array("midp", "j2me", "avantg", "docomo", "novarra",  "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\  ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia",  "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-",  "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg",  "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp",  "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox",  "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo",  "sgh", "gradi", "jb", "\d\d\di", "moto","webos");	 
	      foreach($uamatches as &$uastring){
	        if(preg_match("/".$uastring."/i",$_SERVER["HTTP_USER_AGENT"])) return  true;
	      }
	   }
	   return false;
	}
        
	public function ajaxSaveFormAction() {
            $this->_helper->layout->disableLayout();
            //$this->_helper->viewRenderer->setNoRender();
            $formData  = $this->_request->getPost();
            parse_str($formData['data'], $data);

            $error=0;
            $messages=null;
            $form = new Application_Form_Prono;
            if (!$form->isValid($data)) {
                $error=1;
                $messages=$this->printMessages($form->getMessages());
            }
            else {
            	$postParams= $data;
            	$deelnemerModel = new Application_Model_Deelnemer();
            	$fields=array("naam"=>$postParams['naam'],"email"=>$postParams['email'],"team"=>$postParams['team']);
            	$id= $deelnemerModel->save($fields);

                $eId = uniqid($id . '', true);
                $eId=str_replace(".","",$eId);
                        $dbFields = array(
                            'eid' => $eId,
                        );
                        $deelnemerModel->save3 ($dbFields, $id);


            
           	$deelnemerinputModel = new Application_Model_Deelnemerinput();
            	for ($ii=1; $ii<=51; $ii++) {
            		$index1=$ii."_1";
            		$index2=$ii."_2";
            		if (isset($postParams[$index1])) {
            			$fields=array("id_deelnemer"=>$id, "id_wedstrijd"=>$ii, "input1"=>$postParams[$index1],"input2"=>$postParams[$index2]);
            			$deelnemerinputModel->save($fields);
            		}
            	}
            	for ($ii=52; $ii<=53; $ii++) {
            		if (isset($postParams[$ii])) {
            			$fields=array("id_deelnemer"=>$id, "id_wedstrijd"=>$ii, "input1"=>$postParams[$ii],"input2"=>0);
            			$deelnemerinputModel->save($fields);
            		}
            	}
            	$this->sendmail($id);
    		}
            $this->view->error=$error;
            $this->view->messages=$messages;
    }
    
    public function ajaxGetStandingAction() {
            $this->_helper->layout->disableLayout();
            //$this->_helper->viewRenderer->setNoRender();
            $formData  = $this->_request->getPost();
            parse_str($formData['data'], $data);
            $form = new Application_Form_Prono;            
            $postParams= $data;  
            //print_r($postParams);
            //die("ok");
           	$wedstrijdModel = new Application_Model_Wedstrijd();
                $gamesModel = new Application_Model_Games();
                $sessionid=session_id();
                $gamesModel->delete($sessionid);
            	for ($ii=1; $ii<=36; $ii++) {
            		$index1=$ii."_1";
            		$index2=$ii."_2";
            		if (isset($postParams[$index1]) and trim($postParams[$index1])<>"" and trim($postParams[$index2])<>"") {
                        $w=$wedstrijdModel->getOne($ii);                                
            			$fields=array("sessionid"=>$sessionid,"hteam"=>$w['hteam'], "ateam"=>$w['ateam'], "groep"=>$w['groep'],"hscore"=>$postParams[$index1],"ascore"=>$postParams[$index2]);
            			$gamesModel->insert($fields);
            		}
            	}
                
                $result=array();
                for ($ii=1 ;$ii<=6; $ii++) {
                        $result[$ii]=$gamesModel->getStanding($sessionid, $ii); 
                }
                $this->view->result=$result;
                $this->view->groep=array("A","B","C","D","E","F","G","H");
                

	$wedstrijdModel = new Application_Model_Wedstrijd();
	$where="groep=9";
    $this->view->wedstrijden = $wedstrijdModel->getAll($where);
        
    $achtstefinalesModel = new Application_Model_Achtstefinales();
    $achtstefinales = $achtstefinalesModel->getAchtstefinales();
    $this->view->achtstefinales=$achtstefinales;
    }
    
    
	private function printMessages($messages) {
    	$msg=" ";
    	if (!empty($messages)) {
    		$msg .="<div class='msg_error'>";
    		foreach ($messages as $key => $value) {
    			$msg.= "<p>";
    			$msg.= $key." : ";
    			foreach ($value as $v) {
    				$msg.= $v."<br/>";
    			}
    			$msg.= "</p>";
    		}
    		$msg .="</div>";
    	}
    	return $msg;
    }
    
    public function spelregelsAction()
    {

    }
    
	public function klassementAction()
    {
    	$this->view->msgid = $this->_getParam('msg');  
    	//$this->view->view = $this->_getParam('view');
    	$id = $this->_getParam('id');  
    	$this->view->view = 1;
	    $deelnemerModel = new Application_Model_Deelnemer();
        $this->view->result= $deelnemerModel->getScore($id);
        $this->view->winner=$deelnemerModel->getKampioenen(52);
        $this->view->finales=$deelnemerModel->getScoresPerFinale();
        $this->view->resultteam= $deelnemerModel->getScoreTeam();
    }
    
    
    public function deelnemersAction()
    {
    	$this->view->msgid = $this->_getParam('msg');  
    	$this->view->view = $this->_getParam('view');
    	$id = $this->_getParam('id');  
    	//$this->view->view = 1;
	$deelnemerModel = new Application_Model_Deelnemer();
        $this->view->result= $deelnemerModel->getScore($id);
        $this->view->finales=$deelnemerModel->getScoresPerFinale();
    }
    
	public function detailAction()
    {
    	$id = $this->_getParam('id');  
  	$mail = $this->_getParam('mail'); 
	$deelnemerModel = new Application_Model_Deelnemer();
        $this->view->deelnemer = $deelnemerModel->getOne($id);
        
        $this->view->result2 = $deelnemerModel->getScoreGroep($id);        
        $this->view->finales=$this->getfinales();
        
        $this->view->result = $deelnemerModel->getWedstrijden($id,36);
        
        $deelnemerinputModel = new Application_Model_Deelnemerinput();
        $where="id_deelnemer in (".(int)$id.")";
        $this->view->deelnemerinput = $deelnemerinputModel->getAll($where);
        
        $ploegenModel = new Application_Model_Ploeg();
        $this->view->ploegen= $ploegenModel->getPloegen();           

        $wedstrijdModel = new Application_Model_Wedstrijd();
        $this->view->wedstrijden = $wedstrijdModel->getAll();
        $this->view->mail=$mail;
    }
    
	private function getfinales() {
    		$finales=array("9"=>"Achtste finale","10"=>"Kwartfinale","11"=>"Halve finale","12"=>"Troostfinale","13"=>"Finale");
    		return $finales;
    }
    
	public function ajaxFillKwartfinaleAction() {
        $this->_helper->layout->disableLayout();		 
        $formData  = $this->_request->getPost();
        parse_str($formData['data'], $data);     
        print_r($formData);
        die("ok");   
        $modelPloeg=  new Application_Model_Ploeg();
        $where ="id in (".$formData['a1'].",".$formData['a2'].")";
        $this->view->ploegen= $modelPloeg->getAll($where);
    }

	public function ajaxFillNextRoundAction() {
        $this->_helper->layout->disableLayout();		 
        $formData  = $this->_request->getPost();
        
        $modelWedstrijd = new Application_Model_Wedstrijd();
        $wedstrijd=$modelWedstrijd->getOne($formData['w']);
       
        $this->view->wedstrijd=$wedstrijd['volgende_wedstrijd'];
        $modelPloeg=  new Application_Model_Ploeg();
        $where ="id in (".(int)$formData['f1'].",".(int)$formData['f2'].")";
        $this->view->ploegen= $modelPloeg->getAll($where);
    }

	public function wedstrijdenAction()
    {
    	$id = $this->_getParam('id'); 
    	$this->view->doelpunten= $this->_getParam('doelpunten'); 
    	
    	$this->view->id=$id;
    	$ploegenModel = new Application_Model_Ploeg();
        $this->view->ploegen= $ploegenModel->getAll();           

        $wedstrijdModel = new Application_Model_Wedstrijd();
        $this->view->wedstrijden = $wedstrijdModel->getAll();
        $this->view->AantalDoelpunten = $wedstrijdModel->getAantalDoelpunten();
        if (!empty($id)) {
        	$wedstrijd=$wedstrijdModel->getOne($id);
        	$this->view->wedstrijd=$wedstrijd;
        	
        	if ($wedstrijd['groep']>8) {
        		$this->view->result2=$wedstrijdModel->getScoreForGroep($wedstrijd['groep']);
        	}
        	else {
        		$this->view->result=$wedstrijdModel->getScoreForWedstrijd($id);        	
        	}
        }
    }

}





