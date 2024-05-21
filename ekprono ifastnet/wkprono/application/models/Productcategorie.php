<?php
class Application_Model_Productcategorie extends My_Model
{
    protected $_name = 'product_categorie'; //table name
    protected $_id   = 'id'; //primary key

    public function save($data,$id = NULL)
    {
        $dbFields = array(
                'idcategorie'=> (int)$data['idcategorie'],
                'idproduct'  => (int)$data['idproduct'],
        );
        $this->deleteCategorieByProductId($data['idcategorie'],$data['idproduct']);
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

    public function deleteCategorieByProductId($id,$productid)
    {
        return parent::delete('idcategorie = '. (int)$id. ' and idproduct='.(int)$productid );
    }


    public function getCategorieForProduct($id=null)
    {
            $locale= Zend_Registry::get('Zend_Locale');
            $taalcode=(!empty($locale))?substr($locale,0,2):'nl';

            $sql = $this->db
            ->select()
            ->from(array('c' => 'categorie'), array('id', 'label', 'status') )
            ->join(array('p' => 'product_categorie'), ' c.id = p.idcategorie  ', array('id as c.id', 'idproduct') )
            ->join(array('v' => 'categorie_vertaling'), ' c.id = v.categorie_id  ', array('titel','vertaald', 'taal_id') )
            ->join(array('t' => 'taal'), ' t.id = v.taal_id  ', array('code') );;

            $sql->where ('t.code = '."'".$taalcode."'");

        If (!empty($id)) {
            $sql->where ('p.idproduct = '.$id);
        }

        $data = $this->db->fetchAll($sql);
        return $data;
    }


}