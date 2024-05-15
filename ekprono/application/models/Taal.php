<?php
class Application_Model_Taal extends My_Model
{
    protected $_name = 'taal';
    protected $_id   = 'id'; //primary key
    
    public function getTaal($where=NULL){
        $where="status=1";
        $talen = parent::getAll($where,"id");
        $talen_array=array();
	foreach ( $talen as $t ) {
            $talen_array[$t['id']] = $t['code'];
        }
        return $talen_array;
     }
}
?>

