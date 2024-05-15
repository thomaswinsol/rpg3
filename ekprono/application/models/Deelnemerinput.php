<?php
class Application_Model_Deelnemerinput extends My_Model
{
    protected $_name = 'deelnemers_input'; //table name
    protected $_id   = 'id'; //primary key
    
    
	public function getAll($where=null,$order=null)
    {       
    	$result = parent::getAll($where);
    	$input=array();
    	foreach ($result as $r) {
    		$input[$r['id_wedstrijd']]['input1']=$r['input1'];
    		$input[$r['id_wedstrijd']]['input2']=$r['input2'];
    		$input[$r['id_wedstrijd']]['score'] =$r['score'];
    	}
        return $input;
    }
    
	public function getWedstrijd($where=null,$order=null)
    {       
    	$result = parent::getAll($where);
    	$input=array();
    	foreach ($result as $r) {
    		$input[$r['id_deelnemer']]['input1']=$r['input1'];
    		$input[$r['id_deelnemer']]['input2']=$r['input2'];
    	}
        return $input;
    }
    
	public function save($data,$id = NULL)
    {
        $dbFields = array(
                'id_deelnemer'     => (int)$data['id_deelnemer'],
                'id_wedstrijd'     => (int)$data['id_wedstrijd'],
        		'input1' 		   => (int)$data['input1'],
        		'input2'   		   => (int)$data['input2'],
        		'score'            => 0,
        );

        return $this->insert($dbFields);                               
    }    
    
    
    public function save2($data,$id_wedstrijd,$id_deelnemer)
    {
        $dbFields = array(
                'score' => (int)$data,
        );
        return $this->update2($dbFields,$id_wedstrijd,$id_deelnemer);                               
    } 
    
    public function save3($data,$id)
    {
        $dbFields = array(
                'score' => (int)$data,
        );
        return $this->update($dbFields,$id);                               
    } 
    /**
     * Insert
     * @return int last insert ID
     */
    public function insert(array $data)
    {
        return parent::insert($data);       
    }

	public function getWedstrijden($id)
    {
            $sql = $this->db
            ->select()
            ->from(array('w' => 'wedstrijden'), array('id as wid','thuis','uit','uitslag') )
            ->join(array('i' => 'deelnemers_input'), ' i.id_wedstrijd = w.id  ', array('id as did','input1','input2','score') )
            ->where ('w.groep='.(int)$id)
            ->order(array('wid') );
            $data = $this->db->fetchAll($sql);
            return $data;
    }
    
    /**
     * Update
     * @return int numbers of rows updated
     */
    public function update(array $data,$id)
    {
        return parent::update($data, 'id = '. (int)$id);
    }
    
  	public function update2($data,$id_wedstrijd,$id_deelnemer)
    {
        return parent::update($data, 'id_wedstrijd = '. (int)$id_wedstrijd. ' and id_deelnemer ='. (int)$id_deelnemer);
    }


}