<?php
class Application_Model_Deelnemer extends My_Model
{
    protected $_name = 'deelnemers'; //table name
    protected $_id   = 'id'; //primary key
    
	public function save($data,$id = NULL)
    {
    	$currentTime =  date("Y-m-d H:i:s", time());
        $dbFields = array(
                'naam'     => $data['naam'],
                'email'    => strtolower($data['email']),
                'team'     => strtoupper($data['team']),
        		'timestamp'=> $currentTime,
        		'afdeling' => 1,
                'status'   => 1,
        	    'betaald'  => 0
        );

        return $this->insert($dbFields);                               
    }    
    
    public function save2($data,$id = NULL)
    {
    	$currentTime =  date("Y-m-d H:i:s", time());
        $dbFields = array(
        		'afdeling' => (int)$data['afdeling'],
                'betaald'  => (int)$data['betaald'],
        );

        return $this->update($dbFields,$id);                               
    }  

    public function save3($data,$id = NULL)
    {
    	$currentTime =  date("Y-m-d H:i:s", time());
        $dbFields = array(
        		'eid' => trim($data['eid']),
        );
        return $this->update($dbFields,$id);                               
    }    
    
    public function getScore($id=null)
    {
            $sql = $this->db
            ->select()
            ->from(array('d' => 'deelnemers'), array('id','naam', 'email','status','timestamp','afdeling','team','betaald') )
            ->join(array('a' => 'afdeling'), ' d.afdeling = a.id  ', array('a.naam as naamafdeling') )
            ->join(array('s' => 'deelnemers_input'), ' d.id = s.id_deelnemer  ', array('sum(score) as score') )            
            ->join(array('w' => 'wedstrijden'), ' s.id_wedstrijd = w.id  ', array() )
            ->group(array('id','naam', 'email','status','timestamp','afdeling', 'team', 'betaald', 'naamafdeling') )
            ->order(array('sum(score) desc') );
            $data = $this->db->fetchAll($sql);
            if (!empty($id)) {
            	$sql->where ('w.id <=36'); 
            }
            return $data;
    }

    public function getScoreTeam()
    {
        $sql="select * from ( select d.id, naam, email, team, sum(score) as score, row_number() over (partition by team order by score desc) as team_rank from deelnemers d join deelnemers_input s on d.id=s.id_deelnemer 
              where team<>'' group by  d.id, naam,email, team ) ranks where team_rank <= 3";
        $data = $this->db->fetchAll($sql);
        if (empty($data)){
            return;
        }
        $teamscore=array();
        foreach ($data as $d){
                $team=trim($d['team']);
                if (!isset($teamscore[$team])){
                    $teamscore[$team]['score']=0;
                    $teamscore[$team]['naam']="";
                }
                $teamscore[$team]['score']+=$d['score'];
                if (trim($teamscore[$team]['naam'])<>''){
                    $teamscore[$team]['naam'].=",";
                }
                $teamscore[$team]['naam'].=trim($d['naam']);
        }
        return $teamscore;

    }

    

    
	public function getScoreGroep($id=null)
    {
            $sql = $this->db
            ->select()
            ->from(array('d' => 'deelnemers'), array('id','naam', 'email','status','timestamp','afdeling','betaald') )
            ->join(array('a' => 'afdeling'), ' d.afdeling = a.id  ', array('a.naam as naamafdeling') )
            ->join(array('s' => 'deelnemers_input'), ' d.id = s.id_deelnemer  ', array('sum(score) as score') )
            ->join(array('w' => 'wedstrijden'), ' s.id_wedstrijd = w.id  ', array('groep') )
            ->group(array('id','naam', 'email','status','timestamp','afdeling', 'betaald', 'naamafdeling','groep') )
            ->order(array('sum(score) desc') );
             $sql->where ('w.groep >=9'); 
             $sql->where ('w.groep <=13');
             $sql->where ('d.id ='.(int)$id);   
            $data = $this->db->fetchAll($sql);
            return $data;
    }
    
	public function getDeelnemers()
    {
            $sql = $this->db
            ->select()
            ->from(array('d' => 'deelnemers'), array('id','naam', 'email','status','timestamp','afdeling','betaald') )
            ->join(array('a' => 'afdeling'), ' d.afdeling = a.id  ', array('a.naam as naamafdeling') )
            ->join(array('s' => 'deelnemers_input'), ' d.id = s.id_deelnemer  ', array('sum(score) as score') )
            ->group(array('id','naam', 'email','status','timestamp', 'afdeling','betaald', 'naamafdeling') )
            ->order(array('naam asc') );
            $data = $this->db->fetchAll($sql);
            return $data;
    }
    
	public function getWedstrijden($id, $wedstrijdsnr=null)
    {
            $sql = $this->db
            ->select()
            ->from(array('w' => 'wedstrijden'), array('id as wid','thuis','uit','uitslag') )
            ->join(array('i' => 'deelnemers_input'), ' i.id_wedstrijd = w.id  ', array('input1','input2','score') )
            ->where ('i.id_deelnemer='.(int)$id)
            ->where ('w.uitslag<>""')            
            ->order(array('wid') );
            if (!empty($wedstrijdsnr)) {
            	 $sql->where ('w.id <='.(int)$wedstrijdsnr);  
            }
            $data = $this->db->fetchAll($sql);
            return $data;
    }
    
    
    public function getKampioenen($wedstrijdsnr=null)
    {
            $sql = $this->db
            ->select()
            ->from(array('w' => 'deelnemers_input'), array('id_deelnemer', 'input1','input2','score') )
            ->join(array('p' => 'ploegen'), ' w.input1 = p.id  ', array('p.naam as naam') );
            if (!empty($wedstrijdsnr)) {
            	 $sql->where ('w.id_wedstrijd ='.(int)$wedstrijdsnr);  
            }
            $data = $this->db->fetchAll($sql);
            $result=array();
            if (!empty($data)) {
                    foreach ($data as $d) {
                            $result[$d['id_deelnemer']]=$d['naam'];
                    }
            }            
            return $result;
    }
    
    public function getScoresPerFinale($wedstrijdsnr=null)
    {
            $sql = $this->db
            ->select()
            ->from(array('w' => 'deelnemers_input'), array('id_wedstrijd', 'id_deelnemer', 'input1','input2','score') );
            $data = $this->db->fetchAll($sql);
            $result=array();
            if (!empty($data)) {
                    foreach ($data as $d) {
                            $ii=0;
                            if ($d['id_wedstrijd']>=1 and $d['id_wedstrijd']<=48) {
                                    $ii=1;
                            }
                            if ($d['id_wedstrijd']>=49 and $d['id_wedstrijd']<=56) {
                                    $ii=2;
                            }
                            if ($d['id_wedstrijd']>=57 and $d['id_wedstrijd']<=60) {
                                    $ii=3;
                            }
                            if ($d['id_wedstrijd']>=61 and $d['id_wedstrijd']<=62) {
                                    $ii=4;
                            }
                            if ($d['id_wedstrijd']>=64 and $d['id_wedstrijd']<=64) {
                                    $ii=5;
                            }
                            if ($d['id_wedstrijd']==65) {
                                    $ii=6;
                            }
                            if ($d['id_wedstrijd']==66) {
                                    $ii=7;
                            }
                            if (!isset($result[$d['id_deelnemer']][$ii])){
                                    $result[$d['id_deelnemer']][$ii]=0;
                            }
                            $result[$d['id_deelnemer']][$ii]+=$d['score'];
                    }
            }            
            return $result;
    }
    
	
    /**
     * Insert
     * @return int last insert ID
     */
    public function insert(array $data)
    {
        return parent::insert($data);       
    }

    /**
     * Update
     * @return int numbers of rows updated
     */
    public function update(array $data,$id)
    {
        return parent::update($data, 'id = '. (int)$id);
    }
    


public function buildPdf($data, $id){
 			try {
				// create new PDF document
				$pdf = new My_Tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); //default is UTF-8		
				$pdfPath = APPLICATION_PATH ;
				$templateFileName = 'pdf-default.phtml';
				$currentDate = date('d.m.Y');
 			}
   			catch (Exception $e){
            	die($e->getMessage());
        	}        		
        		 
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor("Winsol");
			$pdf->SetTitle('EKProno');
			$pdf->SetSubject('EKProno');
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			// set header and footer fonts
			$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN,'', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(array(PDF_FONT_NAME_DATA,'', PDF_FONT_SIZE_DATA));
			//set margins
			$pdf->SetMargins(5, 2, PDF_MARGIN_RIGHT); //PDF_MARGIN_TOP
			$pdf->SetHeaderMargin(0);
			$pdf->SetFooterMargin(0);
			//set auto page breaks
			//$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
			$pdf->SetAutoPageBreak(true,0);
			//set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			//initialize document
			//$pdf->AliasNbPages();
			// set font
			$pdf->SetFont("helvetica", "", 12);
			//$pdf->SetFont("Arial", "", 12);
			// add a page
			$pdf->AddPage("P","A4"); 

            $pdf->setFillColor(173,216,230);
			
            //print_R($data);
            //die("ok");
            $pdf->ln(6);
            $pdf->Cell(202,5,"EK Prono",0,0,'C');
            $pdf->ln(6);
            $pdf->Cell(100,5,"Deelnemer: ". trim($data[0]['naam']),0,0,'L');
            $pdf->Cell(2,10," " ,0,0,'C');
            $pdf->Cell(100,5,trim($data[0]['email']),0,0,'R');
            $pdf->ln(12);
            
        $groepen=array("1"=>"GROEP A","2"=>"GROEP B","3"=>"GROEP C","4"=>"GROEP D","5"=>"GROEP E","6"=>"GROEP F","7"=>"GROEP G","8"=>"GROEP H", 
                        "9"=>"Achtste finales", "10"=>"Kwartfinales", "11"=>"Halve finales", "12"=>"Finale");
           $counter=0;
           $input=$data[1];
           $ploegen=$data[2];
           $wedstrijden=$data[3];

           for ($ii=1; $ii<=12; $ii++){
                if ($ii==7){
                   $ii=9;
                }
                $counter=0;
                $groepsid=$ii;
           if (!empty($wedstrijden[$groepsid])){       
             $pdf->Cell(100,10,$groepen[$groepsid],1,0,'C',true);  	
             $pdf->Cell(2,10," " ,0,0,'C');  	
			 $pdf->Cell(100,10,$groepen[$groepsid+1],1,1,'C',true);

			 foreach ($wedstrijden[$groepsid] as $k=> $w) {
                    $counter++;
                    $pdf->Cell(7,5,$w['id'],1,0,'C');
                    $pdf->Cell(12,5,$w['Datum'],1,0,'C');
                    if ($groepsid<=6){
                        $pdf->Cell(28,5,$w['thuis'],'LTB',0,'C');                        
                        $pdf->Cell(10,5,"",'TB',0,'C');                        
                        $pdf->Cell(28,5,$w['uit'],'RTB',0,'C');
                        $pdf->Cell(15,5,$input[$w['id']]['input1']."-".$input[$w['id']]['input2'],1,0,'C');
                    }
                    else {
                        $pdf->Cell(15,5,substr($w['Uur'],0,5),1,0,'C');
                        $pdf->Cell(28,5,$ploegen[$input[$w['id']]['input1']],'LTB',0,'C');                        
                        $pdf->Cell(10,5,"",'TB',0,'C');                        
                        $pdf->Cell(28,5,$ploegen[$input[$w['id']]['input2']],'RTB',0,'C');
                        $w['thuis']=$ploegen[$input[$w['id']]['input1']];
                        $w['uit']=$ploegen[$input[$w['id']]['input2']];
                    }
                                                                  
                            $y1 = $pdf->gety();
							$path='/home/vol17_2/infinityfree.com/if0_35509187/htdocs/wkprono/public/images/vlaggen/';
                            $x=($groepsid<=6)?50:65;
                            $img = str_replace("ë","e", trim($w['thuis'])).".png";
							if (file_exists($path.$img)){
								$img1=trim($path.$img);
								$pdf->Image($img1, $x, $y1,6);
								$x=$x+8;								
							}
                            $img = str_replace("ë","e", trim($w['uit'])).".png";
							if (file_exists($path.$img)){
								$img1=trim($path.$img);
								$pdf->Image($img1, $x, $y1,6);
								$x=$x+10;								
							}

                            if ($groepsid<=6){
                                $pdf->Cell(2,5," " ,0,0,'C');
                            }
                            else {
                                $pdf->Cell(2,5," " ,0,0,'C');
                            }
                    
                    $w2=$wedstrijden[$groepsid+1];

                    if (isset($w2[$k]['id'])){
                            
                            $pdf->Cell(7,5,$w2[$k]['id'],1,0,'C');
                            $pdf->Cell(12,5,$w2[$k]['Datum'],1,0,'C');
                            if ($groepsid<=6){
                                $pdf->Cell(28,5,$w2[$k]['thuis'],'LTB',0,'C');
                                $pdf->Cell(10,5,"",'TB',0,'C');                                                
                                $pdf->Cell(28,5,$w2[$k]['uit'],'RTB',0,'C');                            
                                $pdf->Cell(15,5,$input[$w2[$k]['id']]['input1']."-".$input[$w2[$k]['id']]['input2'],1,0,'C');   
                            }else {
                                $pdf->Cell(15,5,substr($w2[$k]['Uur'],0,5),1,0,'C');
                                $pdf->Cell(28,5,$ploegen[$input[$w2[$k]['id']]['input1']],'LTB',0,'C');
                                $pdf->Cell(10,5,"",'TB',0,'C');                                                
                                $pdf->Cell(28,5,$ploegen[$input[$w2[$k]['id']]['input2']],'RTB',0,'C');
                                $w2[$k]['thuis']=$ploegen[$input[$w2[$k]['id']]['input1']];
                                $w2[$k]['uit']=$ploegen[$input[$w2[$k]['id']]['input2']];                                                           
                            }                            
                            $pdf->ln(5);
                            

                            $x=($groepsid<=6)?152:167;
                            $img = str_replace("ë","e", trim($w2[$k]['thuis'])).".png";
							if (file_exists($path.$img)){
								$img1=trim($path.$img);
								$pdf->Image($img1, $x, $y1,6);
								$x=$x+8;								
							}
                            $img = str_replace("ë","e", trim($w2[$k]['uit'])).".png";
							if (file_exists($path.$img)){
								$img1=trim($path.$img);
								$pdf->Image($img1, $x, $y1,6);
								$x=$x+10;								
							}
                    }
                    else {
                        $pdf->ln(5);
                    }
                    
                    
             }
             
             if ($ii==5){
                 $pdf->ln(5);
             }
             $ii=$ii+1;   
             $pdf->ln(5);             
           }

           }
           
             $pdf->Cell(100,10,"EK Winnaar",1,0,'C',true);  	
             $pdf->Cell(2,10," " ,0,0,'C');  	
			 $pdf->Cell(100,10,"Aantal Doelpunten",1,1,'C',true);
             $pdf->Cell(100,5,$ploegen[$input[52]['input1']],'1',0,'C');
             $pdf->Cell(2,10," " ,0,0,'C');
             $pdf->Cell(100,5,$input[53]['input1'],'1',0,'C');
             $pdf->lastPage();
                            $y1 = $pdf->gety();
                            $x=70;
                            $img = str_replace("ë","e", trim($ploegen[$input[52]['input1']])).".png";
							if (file_exists($path.$img)){
								$img1=trim($path.$img);
								$pdf->Image($img1, $x, $y1,6);								
							}
            $pdf->ln(15);
            $pdf->Cell(202,5,$this->formatTimestamp($data[0]['timestamp']),0,0,'C');
            ob_clean();
            $filepath="/home/vol17_2/infinityfree.com/if0_35509187/htdocs/wkprono/public/uploads/".trim($data[0]['eid']).".pdf";
			$pdf->Output($filepath,'F');
            //$fileName = 'label1.pdf';
			//$pdf->Output($fileName,'D');    	
    }
    

    private function formatTimestamp($data)
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