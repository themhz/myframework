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
            . "( users,semester) "
            . " values(:users,:semester); ";

        $values = array();
        $values[":users"] = $obj->id;
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



    
    public function getUserSemester()
    {

        $requesthandler =  new requesthandler();

        $db = dbhandler::getInstance();
        $sql = "select max(semester) semester from semester ";                    
        
                            
         if(requestHandler::get()!=null){
             $id = requestHandler::get()->id;            
             $sql .= " where users=". $id;
         }   

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        

        foreach ($results as $row) {
            $data['data'][] = array('semester' => $row->semester);
        }

        return $data;
    }

    public function checkIfExists($data){        
        $db = dbhandler::getInstance();
        $sql = "select count(a.semester) semester
                    from semester a
                    inner join users b on b.id = a.users
                where a.users = ".$data->id." and a.semester = ".$data->semester." and b.role = 3;";


        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        $doinsert = false;
        foreach ($results as $row) {                        
            if((int)$row->semester>0){
                $doinsert = false;
            }else{
                $doinsert = true;
            }
        }

        return $doinsert;
    }
    
}
