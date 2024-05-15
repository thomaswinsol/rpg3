<?php
class Application_Model_Menu extends My_Model
{
    protected $_name = 'menu';
    protected $_id   = 'id'; //primary key
    const STATUS = 1;

    public function getMenu($role=NULL){
        if (empty($role)) {
            return ;
        }
        $sql = $this->db
            ->select()
            ->from(array('a' => 'menu'), array('id', 'label', 'module', 'controller', 'action', 'params', 'status') )
            ->join(array('b' => 'menu_role'), ' a.id = b.idmenu  ', array('idrole') );
            $sql->where ('b.idrole = '.(int)$role);
            $sql->where ('a.status = '.self::STATUS);
            $data = $this->db->fetchAll($sql);
            return $data;
    }
}
?>
