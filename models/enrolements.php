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
            . "(users,courses,status,grade,regdate) "
            . " values(:users,:courses,:status,:grade,:regdate); ";

        $values = array();
        $values[":users"] = $obj->users;
	    $values[":courses"] = $obj->courses;
	    $values[":status"] = 1;
	    $values[":grade"] = 0;
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
		,'users' => $row->users
		,'courses' => $row->courses
		,'status' => $row->status
		,'grade' => $row->grade
		,'regdate' => $row->regdate
		

            );
        }

        return $data;
    }


    public function delete($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "delete from enrolements where users = ".$obj->users." and courses = ".$obj->courses;
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        return 'ok';
    } 


    public function checkuserenrolement($obj){        

        $requesthandler =  new requesthandler();

        $db = dbhandler::getInstance();
        $sql = "select courses from enrolements where users = ".$obj->users." and courses = ".$obj->courses;
                                         
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        
        $courses = null;
        foreach ($results as $row) {            
                $courses = $row->courses;
        }

        return $courses;
    }


    public function getenroledstudents(){
        $requesthandler =  new requesthandler();
        $db = dbhandler::getInstance();
        $id = requestHandler::get()->id;

        $sql = "select a.*,  b.name, b.lastname
                from enrolements a
                inner join users b on a.users = b.id
                where a.courses = $id;";
                
                                
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id,                
                'users' => $row->users,
                'name' => $row->name,
                'lastname' => $row->lastname,
                'grade' => $row->grade
            );
        }

        return $data;
    }

    public function updategrade(){
        $db = dbhandler::getInstance();
        $enroled_id = requestHandler::get()->enroled_id;        
        $grade = requestHandler::get()->grade;

        $sql = "update enrolements "
            . " set grade = $grade where  id = $enroled_id";
                        
        $sth = $db->dbh->prepare($sql);
        $sth->execute();

        $rowsupdates = $sth->rowCount();
        if($rowsupdates == 0){
            return array('update'=>false, "records"=>$rowsupdates);
        }else{
            return array('update'=>true, "records"=>$rowsupdates);
        }
    }
    
}
