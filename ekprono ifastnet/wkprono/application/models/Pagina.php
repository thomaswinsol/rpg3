<?php
class Application_Model_Pagina extends My_Model
{
    protected $_name  = 'pagina';
    protected $_sName = 'pagina_vertaling.pagina';
    protected $_id    = 'id';

    protected $lang_fields  = array('titel', 'teaser', 'inhoud');
    protected $model_fields = array();
    protected $status = '';

    public function save($data,$id = NULL)
    {
    	$currentTime =  date("Y-m-d H:i:s", time());
        $isUpdate = FALSE;
        $dbFields = array(
        	'label'      => $data['label'],
                'status'     => (int)$data['status'],
        );
        
        if (!empty($id)) {
        	$isUpdate = TRUE;
        	$this->update($dbFields,$id);
                $this->savetranslation($data, $id);
        	return $id;
        }
        $id = $this->insert($dbFields);
        $this->savetranslation($data, $id);
    }

    public function savetranslation($data,$id = NULL)
    {
        $vertalingModel = new Application_Model_Paginavertaling();
        $vertalingModel->deleteById($id, "pagina_id");
        foreach ($data['translation'] as $key => $value) {
            $translated= !empty($value['titel'])?1:0;
            $dbFields=array(
                "pagina_id"   => $id,
                "taal_id"     => $key,
                "titel"       => trim($value['titel']),
                "teaser"      => trim($value['teaser']),
                "inhoud"      => trim($value['inhoud']),
                "vertaald"    => $translated
            );
            $vertalingModel->save($dbFields);
        }
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


    public function getLangFields()
    {
        return $this->lang_fields;
    }

    public function getModelFields()
    {
        return $this->model_fields;
    }

    public function getStatus()
    {
        return $this->status;
    }

     public function getAutocomplete($where=NULL){
        $page = parent::getAll($where);

	$matches = array();
        foreach ( $page as $p ) {
                        $p['id']  =trim($p['id']);
        		$p['naam']=trim($p['label']);
        		$p['value'] = trim($p['id']);
                        if ($p['status']) {
                            $p['label'] = trim($p['label']);
                        } else {
                            $p['label'] = "<span style='text-decoration:line-through;'>".trim($p['label'])."</span>";
                        }
                        
			$matches[] = $p;
        }
        return $matches;
     }

    public function getNavigation($status=null)
    {
          $locale= Zend_Registry::get('Zend_Locale');
          $taalcode=(!empty($locale))?substr($locale,0,2):'nl';

            $sql = $this->db
            ->select()
            ->from(array('p' => 'pagina'), array('id', 'label', 'status') )
            ->join(array('v' => 'pagina_vertaling'), ' p.id = v.pagina_id  ', array('titel','vertaald', 'taal_id') )
            ->join(array('t' => 'taal'), ' t.id = v.taal_id  ', array('code') );
            $sql->where ('t.code = '."'".$taalcode."'");
            if (!empty($status)) {
                $sql->where ('p.status = '.(int)$status);
            }
            $data = $this->db->fetchAll($sql);
            $counter=0;

            $url=array();
            foreach ($data as $d) {
                $url[$counter]['label']=ucfirst($d['titel']);
                $url[$counter]['module']='default';
                $url[$counter]['action']='getpagina';
                $url[$counter]['controller']='pagina';
                $url[$counter]['params']="id,".$d['id'];
                $counter++;
            }

            return $url;
    }

    public function getpagina($id=null)
    {
          $locale= Zend_Registry::get('Zend_Locale');
          $taalcode=(!empty($locale))?substr($locale,0,2):'nl';

            $sql = $this->db
            ->select()
            ->from(array('p' => 'pagina'), array('id', 'label', 'status') )
            ->join(array('v' => 'pagina_vertaling'), ' p.id = v.pagina_id  ', array('titel','teaser', 'inhoud','vertaald', 'taal_id') )
            ->join(array('t' => 'taal'), ' t.id = v.taal_id  ', array('code') );
            $sql->where ('t.code = '."'".$taalcode."'");
            $sql->where ('p.id = '.(int)$id);
            $data = current($this->db->fetchAll($sql));
            return $data;
    }

}