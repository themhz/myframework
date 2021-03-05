<?php

class Enrolements
{    

    public function __construct()
    {

    }

    public function insert($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "insert enrolements "
            . "( user_id,course_id,status,grade,regdate) "
            . " values(:user_id,:course_id,:status,:grade,:regdate); ";

        $values = array();
        $values[":user_id"] = $obj->user_id;
	$values[":course_id"] = $obj->course_id;
	$values[":status"] = $obj->status;
	$values[":grade"] = $obj->grade;
	$values[":regdate"] = date("Y-m-d H:i:s");
	
        
        $sth = $db->dbh->prepare($sql);
        $sth->execute($values);
        return $db->dbh->lastInsertId();

    }

    public function select()
    {

        $requesthandler =  new requesthandler();

        $db = dbhandler::getInstance();
        $sql = "select * from enrolements ";                    
        
                            
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
		,'course_id' => $row->course_id
		,'status' => $row->status
		,'grade' => $row->grade
		,'regdate' => $row->regdate
		

            );
        }

        return $data;
    }


    public function delete($id)
    {
        $db = dbhandler::getInstance();
        $sql = "delete from enrolements where id = $id";
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        return 'ok';
    }

    public function update($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "update enrolements "
            . " set user_id=:user_id,course_id=:course_id,status=:status,grade=:grade,regdate=:regdate ";                


        $sql .= " where id=:id";
        
        $values = array();
        $values[":user_id"] = $obj->user_id;
	$values[":course_id"] = $obj->course_id;
	$values[":status"] = $obj->status;
	$values[":grade"] = $obj->grade;
	$values[":regdate"] = $obj->regdate;
	


        $values[":id"] = $obj->id;
        
        $sth = $db->dbh->prepare($sql);
        $sth->execute($values);
    }    
    
}
