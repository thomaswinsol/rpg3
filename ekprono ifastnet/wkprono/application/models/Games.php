<?php
class Application_Model_Games extends My_Model
{
    protected $_name = 'games'; //table name
    protected $_id   = 'id'; //primary key

    
     

    

    
    public function getStanding($sessionid, $id=1)
    {
            $sql=
            "SELECT
                  t.id as id, tname AS Team, Sum(P) AS P,Sum(W) AS W,Sum(D) AS D,Sum(L) AS L,
                  SUM(F) as F,SUM(A) AS A,SUM(GD) AS GD,SUM(Pts) AS Pts
                  FROM(
                  SELECT
                    hteam Team,
                    1 P,
                    IF(hscore > ascore,1,0) W,
                    IF(hscore = ascore,1,0) D,
                    IF(hscore < ascore,1,0) L,
                    hscore F,
                    ascore A,
                    hscore-ascore GD,
                    CASE WHEN hscore > ascore THEN 3 WHEN hscore = ascore THEN 1 ELSE 0 END PTS
                  FROM games where sessionid='". $sessionid."' and groep= ".(int)$id. "
                  UNION ALL
                  SELECT
                    ateam,
                    1,
                    IF(hscore < ascore,1,0),
                    IF(hscore = ascore,1,0),
                    IF(hscore > ascore,1,0),
                    ascore,
                    hscore,
                    ascore-hscore GD,
                    CASE WHEN hscore < ascore THEN 3 WHEN hscore = ascore THEN 1 ELSE 0 END
                  FROM games where sessionid='". $sessionid."' and groep= ".(int)$id. "
                ) as tot
                JOIN teams t ON tot.Team=t.id
                GROUP BY id, Team
                ORDER BY SUM(Pts) DESC, SUM(GD) DESC, SUM(GD) DESC , SUM(F) DESC , SUM(W) DESC";
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
    
    
    public function delete($id)
    {
        return parent::delete('sessionid= '."'". $id."'");
    }


}