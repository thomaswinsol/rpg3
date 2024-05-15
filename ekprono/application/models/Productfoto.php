<?php
class Application_Model_Productfoto extends My_Model
{
    protected $_name = 'product_foto'; //table name
    protected $_id   = 'id'; //primary key

    public function save($data,$id = NULL)
    {
        $dbFields = array(
                'idfoto'     => (int)$data['idfoto'],
                'idproduct'  => (int)$data['idproduct'],
        );
        $this->deleteFotoByProductId($data['idfoto'],$data['idproduct']);
        $id = $this->insert($dbFields);
    }

    /**
     * Insert
     * @return int last insert ID
     */
    public function insert($data)
    {
        return parent::insert($data);       
    }

    /**
     * Update
     * @return int numbers of rows updated
     */
    public function update($data,$id)
    {
        return parent::update($data, 'id = '. (int)$id);
    }

    public function deleteFotoByProductId($id,$productid)
    {
        return parent::delete('idfoto = '. (int)$id. ' and idproduct='.(int)$productid );
    }

    public function getFotosForProductId($id=null)
    {
            $sql = $this->db
            ->select()
            ->from(array('a' => 'foto'), array('a.id', 'fileName', 'filePath') )
            ->join(array('b' => 'product_foto'), ' a.id = b.idfoto  ', array('idproduct') );
            $sql->where ('b.idproduct ='. (int)$id);
            $data = $this->db->fetchAll($sql);
            return $data;
    }
    


}