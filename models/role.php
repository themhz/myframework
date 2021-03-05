<?php

class Role
{
    public function __construct()
    {
    }

    public function insert($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "insert roles "
            . "(name) "
            . "values(:name); ";

        $sth = $db->dbh->prepare($sql);
        $sth->execute(array(
            ':name' => $obj->name
        ));

        return $db->dbh->lastInsertId();
    }

    public function select()
    {

        $requesthandler =  new requesthandler();

        $db = dbhandler::getInstance();
        $sql = "select * from role";                    
                    
        
                            
         if(requestHandler::get()!=null){
             $id = requestHandler::get()->id;            
             $sql .= " where id=". $id;
         }   

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id,
                'name' => $row->name
            );
        }

        return $data;
    }


    public function delete($id)
    {
        $db = dbhandler::getInstance();
        $sql = "delete from roles where id = $id";
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        return 'ok';
    }

    public function update($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "update roles "
            . " set name=:name "
            . " where id=:id";

        $sth = $db->dbh->prepare($sql);
        $sth->execute(array(
            ':name' => $obj->name,
            ':id' => $obj->id
        ));
    }

    
    
}
