<?php

class Semester
{    

    public function __construct()
    {

    }

    public function insert($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "insert semester "
            . "( user_id,semester) "
            . " values(:user_id,:semester); ";

        $values = array();
        $values[":user_id"] = $obj->user_id;
	$values[":semester"] = $obj->semester;
	
        
        $sth = $db->dbh->prepare($sql);
        $sth->execute($values);
        return $db->dbh->lastInsertId();

    }

    public function select()
    {

        $requesthandler =  new requesthandler();

        $db = dbhandler::getInstance();
        $sql = "select * from semester ";                    
        
                            
         if(requestHandler::get()!=null){
             $id = requestHandler::get()->id;            
             $sql .= " where id=". $id;
         }   

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id
		,'user_id' => $row->user_id
		,'semester' => $row->semester
		

            );
        }

        return $data;
    }


    public function delete($id)
    {
        $db = dbhandler::getInstance();
        $sql = "delete from semester where id = $id";
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        return 'ok';
    }

    public function update($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "update semester "
            . " set user_id=:user_id,semester=:semester ";                


        $sql .= " where id=:id";
        
        $values = array();
        $values[":user_id"] = $obj->user_id;
	$values[":semester"] = $obj->semester;
	


        $values[":id"] = $obj->id;
        
        $sth = $db->dbh->prepare($sql);
        $sth->execute($values);
    }    
    
}
