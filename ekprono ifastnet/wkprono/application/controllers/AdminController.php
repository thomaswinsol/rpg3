<?php

class AdminController extends My_Controller_Action
{

    public function indexAction()
    {
    	$this->view->flashMessenger = $this->flashMessenger->getMessages();
	$form = new Application_Form_Login;        	
        $this->view->form=$form;
    }
    
    public function deelnemersAction()
    {
    	$deelnemerModel = new Application_Model_Deelnemer();
        $this->view->result= $deelnemerModel->getDeelnemers();
        $id = $this->_getParam('id'); 
        if (!empty($id)) {
        	$deelnemer=$deelnemerModel->getOne($id);
        	$form = new Application_Form_Deelnemer;        	
        	$form->populate($deelnemer);
        	$this->view->form=$form;
        }
    }
    
    public function ploegenAction()
    {
    	$ploegenModel = new Application_Model_Ploeg();
    	$where="status in (0,1)";
        $this->view->result= $ploegenModel->getAll($where);
        $id = $this->_getParam('id');  
        if (!empty($id)) {
        	$ploeg=$ploegenModel->getOne($id);
        	$form = new Application_Form_Ploeg;        	
        	$form->populate($ploeg);
        	$this->view->form=$form;
        }
    }
    
    public function wedstrijdenAction()
    {
    	$id = $this->_getParam('id'); 
    	
    	$this->view->id=$id;
    	$ploegenModel = new Application_Model_Ploeg();
        $this->view->ploegen= $ploegenModel->getAll();           

        $wedstrijdModel = new Application_Model_Wedstrijd();
        $this->view->wedstrijden = $wedstrijdModel->getAll();
        if (!empty($id)) {
        	$this->view->wedstrijd=$wedstrijdModel->getOne($id);
        }
    }
    
    
	public function ajaxSaveForm_oldAction() {
            $this->_helper->layout->disableLayout();
            //$this->_helper->viewRenderer->setNoRender();
            $formData  = $this->_request->getPost();
            parse_str($formData['data'], $data);

            $error=0;
            $messages=null;
             
            	$wedstrijdModel = new Application_Model_Wedstrijd();             
            	$id= $wedstrijdModel->save($data , $data['id']);
            	
            	$deelnemerinputModel = new Application_Model_Deelnemerinput(); 
            	$where="id_wedstrijd=".(int)$data['id'];
            	$wedstrijd=$deelnemerinputModel->getWedstrijd($where);
            	$DWV1=$this->DWV($data['uitslag1'],$data['uitslag2']);
                $doelsaldo1=$this->getdoelsaldo($data['uitslag1'],$data['uitslag2']);
            	if (!empty($wedstrijd)){
            		foreach ($wedstrijd as $key=>$value) {
            			if (trim($data['uitslag1'])=="") {		
            				$score=0;
            			} else {            				
            				$score=$this->getscore($data['uitslag1'],$data['uitslag2'],$value['input1'],$value['input2']);
            				if ($score==0) {
                                                $doelsaldo2=$this->getdoelsaldo($value['input1'],$value['input2']); 
                                                if ($doelsaldo1==$doelsaldo2) {
                                                        $score=7;
                                                }
                                                else {
                                                        $DWV2=$this->DWV($value['input1'],$value['input2']);
                                                        if ($DWV1==$DWV2) {
                                                                $score=5;
                                                        }
                                                }
            				}
            			}
            			$deelnemerinputModel->save2($score,$data['id'],$key);
            		}
            	}
    		 
            $this->view->error=$error;
            $this->view->messages=$messages;
    }
    

    public function ajaxSaveFormAction() {
            $this->_helper->layout->disableLayout();
            //$this->_helper->viewRenderer->setNoRender();
            $formData  = $this->_request->getPost();
            parse_str($formData['data'], $data);

            $error=0;
            $messages=null;
             
            	$wedstrijdModel = new Application_Model_Wedstrijd();             
            	$id= $wedstrijdModel->save($data , $data['id']);
            	
            	$deelnemerinputModel = new Application_Model_Deelnemerinput(); 
            	$where="id_wedstrijd=".(int)$data['id'];
            	$wedstrijd=$deelnemerinputModel->getWedstrijd($where);

                $aantalDeelnemers=count($wedstrijd);
                $maxPunten=$aantalDeelnemers*3;

            	$DWV1=$this->DWV($data['uitslag1'],$data['uitslag2']);
                $doelsaldo1=$this->getdoelsaldo($data['uitslag1'],$data['uitslag2']);
            	if (!empty($wedstrijd)){
                    $aantalUitslag=0;
                    $aantalSaldo=0;
                    $aantalPloeg=0;
            		foreach ($wedstrijd as $key=>$value) {
            			if (trim($data['uitslag1'])=="") {		
            				$score=0;
            			} else {            				
            				$score=$this->getscore($data['uitslag1'],$data['uitslag2'],$value['input1'],$value['input2']);
            				if ($score==0) {
                                                $doelsaldo2=$this->getdoelsaldo($value['input1'],$value['input2']); 
                                                if ($doelsaldo1==$doelsaldo2) {
                                                        $score=7;
                                                        $aantalSaldo+=1;
                                                }
                                                else {
                                                        $DWV2=$this->DWV($value['input1'],$value['input2']);
                                                        if ($DWV1==$DWV2) {
                                                                $score=5;
                                                                $aantalPloeg+=1;
                                                        }
                                                }
            				}
                            else{
                                $aantalUitslag+=1;
                            }
            			}
            			
            		}

$totaal1=$aantalSaldo+$aantalUitslag+$aantalPloeg;
$totaal2=$aantalSaldo+$aantalUitslag;
$totaal3=$aantalUitslag;
                    foreach ($wedstrijd as $key=>$value) {
                        if (trim($data['uitslag1'])=="") {		
            				$score=0;
            			} else {
                            $score=$this->getscore($data['uitslag1'],$data['uitslag2'],$value['input1'],$value['input2']);
            				if ($score==0) {
                                                $doelsaldo2=$this->getdoelsaldo($value['input1'],$value['input2']); 
                                                if ($doelsaldo1==$doelsaldo2) {
                                                        $score=7;
                                                        $score =($maxPunten/($totaal2))*(1/3);
                                                        $score+=($maxPunten/($totaal1))*(1/3);
                                                        
                                                }
                                                else {
                                                        $DWV2=$this->DWV($value['input1'],$value['input2']);
                                                        if ($DWV1==$DWV2) {
                                                                $score=5;
                                                                $score =($maxPunten/($totaal1))*(1/3);
                                                        }
                                                }
            				}
                            else{
                                $score=($maxPunten/$totaal3)*(1/3);
                                $score+=($maxPunten/($totaal2))*(1/3);
                                $score+=($maxPunten/($totaal1))*(1/3);

                            }
                        }
                        $deelnemerinputModel->save2($score,$data['id'],$key);
                    }
            	}
    		 
            $this->view->error=$error;
            $this->view->messages=$messages;
    }

    
	public function ajaxSaveForm2Action() {
            $this->_helper->layout->disableLayout();
            //$this->_helper->viewRenderer->setNoRender();
            $formData  = $this->_request->getPost();
            parse_str($formData['data'], $data);

            $error=0;
            $messages=null;            
            $ploegModel = new Application_Model_Ploeg(); 
            if (!empty($data['thuis'])) {            
            	$ploeg=$ploegModel->getOne($data['thuis']);
            	$data['t']=$data['thuis'];
            	$data['thuis']=trim($ploeg['naam']);
            	
            }
	    if (!empty($data['uit'])) {            
            	$ploeg=$ploegModel->getOne($data['uit']);
            	$data['u']=$data['uit'];
            	$data['uit']=trim($ploeg['naam']);
            }
            
            $wedstrijdModel = new Application_Model_Wedstrijd();    
            $wedstrijd= $wedstrijdModel->getOne($data['id']);
			if (empty($data['uitslag1']) and empty($data['uitslag2'])) {
				$this->calculatePunten($data,$wedstrijd);
			}            
            $id= $wedstrijdModel->save2($data , $data['id']);
            $this->view->error=$error;
            $this->view->messages=$messages;
    }
    
    private function calculatePunten($data,$wedstrijd)
    {
    		$groep=$wedstrijd['groep'];
    		$deelnemerinputModel = new Application_Model_Deelnemerinput(); 
    		$wedstrijden= $deelnemerinputModel->getWedstrijden($wedstrijd['groep']);
    		
    		$punten = array (9=>20,10=>40,11=>60,12=>80,13=>100);
    		
    		if (!empty($wedstrijden)) {
    			foreach ($wedstrijden as $w) {
    				
    				$score=$w['score'];
    				
    				if ($w['input1']==$data['t']) {
    						$score+=$punten[$groep];
    				}
    				else {
    					if ($w['input1']==$data['u']) {
    						$score+=$punten[$groep];
    					}
    				}
    				
    				if ($w['input2']==$data['t']) {
    						$score+=$punten[$groep];
    				}
    				else {
    					if ($w['input2']==$data['u']) {
    						$score+=$punten[$groep];
    					}
    				}
    				$deelnemerinputModel->save3($score,$w['did']);
    			}
    		}
    }
    

    
	public function ajaxSaveDeelnemerAction() {
            $this->_helper->layout->disableLayout();
            //$this->_helper->viewRenderer->setNoRender();
            $formData  = $this->_request->getPost();
            parse_str($formData['data'], $data);

            $error=0;
            $messages=null;
             
            $deelnemerModel = new Application_Model_Deelnemer();             
            $id= $deelnemerModel->save2($data , $data['id']);
 
            $this->view->error=$error;
            $this->view->messages=$messages;
    }
    
	public function ajaxSavePloegAction() {
            $this->_helper->layout->disableLayout();
            //$this->_helper->viewRenderer->setNoRender();
            $formData  = $this->_request->getPost();
            parse_str($formData['data'], $data);

            $error=0;
            $messages=null;
             
            $ploegModel = new Application_Model_Ploeg();             
            $id= $ploegModel->save2($data , $data['id']);
 
            $this->view->error=$error;
            $this->view->messages=$messages;
    }
    
    private function getscore($i1, $i2, $i3, $i4) {
    	if ((int)$i1<>(int)$i3) {
    		return 0;
    	}
   	if ((int)$i2<>(int)$i4) {
    		return 0;
    	}
    	return 10;
    }
    private function DWV($input1, $input2) {
    	//draw
    	if ((int)$input1==(int)$input2){
    		return 0;
    	}
    	//winst
    	if ((int)$input1>(int)$input2){
    		return 1;
    	}
    	//verlies
    	if ((int)$input1<(int)$input2){
    		return 2;
    	}
    }
    
    private function getdoelsaldo($input1, $input2) {
    	$doelsaldo= ((int)$input1-(int)$input2);
    	return $doelsaldo;    	
    }
   
}

