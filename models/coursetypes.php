<?php

class Coursetypes
{    

    public function __construct()
    {

    }

    public function insert($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "insert coursetypes "
            . "( name) "
            . " values(:name); ";

        $values = array();
        $values[":name"] = $obj->name;
	
        
        $sth = $db->dbh->prepare($sql);
        $sth->execute($values);
        return $db->dbh->lastInsertId();

    }

    public function select()
    {

        $requesthandler =  new requesthandler();

        $db = dbhandler::getInstance();
        $sql = "select * from coursetypes ";                    
        
                            
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
		,'name' => $row->name
		

            );
        }

        return $data;
    }


    public function delete($id)
    {
        $db = dbhandler::getInstance();
        $sql = "delete from coursetypes where id = $id";
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        return 'ok';
    }

    public function update($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "update coursetypes "
            . " set name=:name ";                


        $sql .= " where id=:id";
        
        $values = array();
        $values[":name"] = $obj->name;
	


        $values[":id"] = $obj->id;
        
        $sth = $db->dbh->prepare($sql);
        $sth->execute($values);
    }    
    
}
