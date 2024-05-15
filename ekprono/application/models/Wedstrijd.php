<?php
class Application_Model_Wedstrijd extends My_Model
{
    protected $_name = 'wedstrijden'; //table name
    protected $_id   = 'id'; //primary key

    
    public function save($data,$id = NULL)
    {
    	if (trim($data['uitslag1'])=="") {
    		$dbFields = array(
                'uitslag' => "",
        	);
    	}
    	else {
        	$dbFields = array(
                'uitslag' => trim($data['uitslag1'])."-".trim($data['uitslag2']),
        	);
    	}
        return $this->update($dbFields,$id);                               
    }    
    
    public function save2($data,$id = NULL)
    {
    	if (trim($data['uitslag1'])=="") {
    		$dbFields = array(
    			'thuis'=> $data['thuis'],
        		'uit'=> $data['uit'],
                'uitslag' => "",
        	);
    	}
    	else {   		
        	$dbFields = array(
        		'thuis'=> $data['thuis'],
        		'uit'=> $data['uit'],
                'uitslag' => trim($data['uitslag1'])."-".trim($data['uitslag2']),
        	);
    	}
        return $this->update($dbFields,$id);                               
    }    
    
	public function getAll($where=null,$order=null)
    {       
    	$result = parent::getAll($where);
    	$wedstrijden=array();
    	foreach ($result as $r) {
    		$wedstrijden[$r['groep']][]=$r;
    	}
        return $wedstrijden;
    }
    
    
	public function getAantalDoelpunten($where=null,$order=null)
    {       
    	$result = parent::getAll($where);
		$counter=0;
    	foreach ($result as $r) {
    		if (!empty($r['uitslag'])) {
    			$score= explode("-", $r['uitslag']);
    			$counter+=(int)$score[0];
    			$counter+=(int)$score[1];
    		} 
    	}
        return $counter;
    }
    
	public function getScoreForWedstrijd($id)
    {
            $sql = $this->db
            ->select()
            ->from(array('d' => 'deelnemers'), array('id','naam') )
            ->join(array('i' => 'deelnemers_input'), ' i.id_deelnemer = d.id  ', array('input1','input2','score') )
            ->where ('i.id_wedstrijd='.(int)$id)
            ->group(array('id','naam') )
            ->order(array('score desc','input1','input2') );
            $data = $this->db->fetchAll($sql);
            return $data;
    }
    
	public function getScoreForGroep($id)
    {
            $sql = $this->db
            ->select()
            ->from(array('d' => 'deelnemers'), array('id','naam') )
            ->join(array('i' => 'deelnemers_input'), ' i.id_deelnemer = d.id  ', array('input1','input2','sum(score) as score') )
            ->join(array('w' => 'wedstrijden'), ' i.id_wedstrijd = w.id  ', array('groep') )
            ->where ('w.groep='.(int)$id)
            ->group(array('id','naam') )
            ->order(array('score desc','input1','input2') );
            $data = $this->db->fetchAll($sql);
            return $data;
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
    


}