<?php

class Courses
{
    public function __construct()
    {
    }
   
    public function select()
    {

        $requesthandler =  new requesthandler();
        $db = dbhandler::getInstance();
        $sql = "select 
                        a.id,
                        a.title,
                        a.description,
                        c.name as 'type',
                        c.id as 'typeid',
                        a.semester,
                        a.ects,
                        Concat(b.name ,\" \", b.lastname) as professorname,
                        b.id as professorid
                        from courses a
                        inner join users b on a.user_id = b.id
                        inner join coursetypes c on a.type = c.id                        
                    ";

                if(requestHandler::get()!=null){
                    $id = requestHandler::get()->id;            
                    $sql .= " where a.id=". $id;
                }   
                $sql .= " order by a.id";
                        
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id,
                'title' => $row->title,
                'description' => $row->description,
                'type' => $row->type,
                'typeid' => $row->typeid,
                'semester' => $row->semester,
                'ects' => $row->ects,
                'professorname' => $row->professorname,
                'professorid' => $row->professorid
            );
        }

        return $data;
    }


    public function delete($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "delete from courses where id = $obj->id";
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $rowsupdates = $sth->rowCount();
        if($rowsupdates == 0){
            return array('delete'=>false, "records"=>$rowsupdates);
        }else{
            return array('delete'=>true, "records"=>$rowsupdates);
        }
    }

    public function update($obj)
    {
        $db = dbhandler::getInstance();
        $sql = "update courses "
            . " set user_id=:user_id, title=:title, type=:type, description=:description, semester=:semester, ects=:ects "
            . " where id=:id";

        $sth = $db->dbh->prepare($sql);
        $sth->execute(array(
            ':user_id' => $obj->user_id,
            ':title' => $obj->title,
            ':type' => $obj->type,
            ':description' => $obj->description,
            ':semester' => $obj->semester,
            ':ects' => $obj->ects,
            ':id' => $obj->id
        ));

        $rowsupdates = $sth->rowCount();
        if($rowsupdates == 0){
            return array('update'=>false, "records"=>$rowsupdates);
        }else{
            return array('update'=>true, "records"=>$rowsupdates);
        }
        
    }

    public function insert($students)
    {
        $db = dbhandler::getInstance();
        $sql = "insert courses "
            . "(user_id, title, type, description, semester,ects) "
            . "values(:user_id, :title, :type, :description, :semester, :ects); ";

        $sth = $db->dbh->prepare($sql);
        $sth->execute(array(
            ':user_id' => $students->user_id,
            ':title' => $students->title,
            ':type' => $students->type,
            ':description' => $students->description,
            ':semester' => $students->semester,
            ':ects' => $students->ects
        ));

        return array('insert'=>false, "records"=>$db->dbh->lastInsertId());
        
    }
}
