<?php
class Application_Model_Afdeling extends My_Model
{
    protected $_name = 'afdeling'; //table name
    protected $_id   = 'id'; //primary key
    
	    
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