<?php
class Application_Model_Achtstefinales extends My_Model
{
    protected $_name = 'achstefinales'; //table name
    protected $_id   = 'id'; //primary key
    
	    
    public function getAchtstefinales()
    {
            $sql = $this->db
            ->select()
            ->from(array('w' => 'achstefinales'), array('poules', 'wb','wc','we','wf') );
            $data = $this->db->fetchAll($sql);
            $result=array();
            if (!empty($data)) {
                    foreach ($data as $d) {
                            $result[trim($d['poules'])]=$d;
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
    


}