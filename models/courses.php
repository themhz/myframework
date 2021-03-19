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
                        c.id as 'courses_type',
                        a.semester,
                        a.ects,
                        Concat(b.name ,\" \", b.lastname) as professorname,
                        b.id as users
                        from courses a
                        inner join users b on a.users = b.id
                        inner join courses_type c on a.courses_type = c.id                        
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
                'courses_type' => $row->courses_type,
                'semester' => $row->semester,
                'ects' => $row->ects,
                'professorname' => $row->professorname,
                'users' => $row->users
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
            . " set users=:users, title=:title, courses_type=:courses_type, description=:description, semester=:semester, ects=:ects "
            . " where id=:id";

        $sth = $db->dbh->prepare($sql);
        $sth->execute(array(
            ':users' => $obj->users,
            ':title' => $obj->title,
            ':courses_type' => $obj->courses_type,
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
            . "(users, title, courses_type, description, semester, ects) "
            . "values(:users, :title, :courses_type, :description, :semester, :ects); ";

        $sth = $db->dbh->prepare($sql);
        $sth->execute(array(
            ':users' => $students->users,
            ':title' => $students->title,
            ':courses_type' => $students->courses_type,
            ':description' => $students->description,
            ':semester' => $students->semester,
            ':ects' => $students->ects
        ));

        return array('insert'=>false, "records"=>$db->dbh->lastInsertId());
        
    }

    
    public function getRelations(){
        
        $db = dbhandler::getInstance();
        $dbname = Config::read('db.basename');
        $sql = "SELECT *  FROM information_schema.key_column_usage where constraint_schema = '$dbname' and table_name='courses' and REFERENCED_TABLE_NAME is not null";
                        
            
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
                
        foreach ($results as $row) {            
            $sql = "SELECT *  FROM ".$row->REFERENCED_TABLE_NAME;
              if($row->REFERENCED_TABLE_NAME=="users"){
                  $sql .=" where role = 2 ";
              }
            $sth = $db->dbh->prepare($sql);
            $sth->execute();
            $results = $sth->fetchAll(PDO::FETCH_ASSOC);
            $data[$row->REFERENCED_TABLE_NAME][] = $results;
                // $data[] = array(
                // 'COLUMN_NAME' => $row->COLUMN_NAME,                                
                // 'REFERENCED_TABLE_NAME' => $row->REFERENCED_TABLE_NAME,
                // 'REFERENCED_COLUMN_NAME' => $row->REFERENCED_COLUMN_NAME
                // );
        }

        return $data;
    }

    public function selectwithStudentGrade()
    {

        $requesthandler =  new requesthandler();
        $db = dbhandler::getInstance();
        $id = requestHandler::get()->id;

        $sql = "select a.id, 
                        a.title, 
                        a.description, 
                        c.name as 'type', 
                        c.id as 'courses_type', 
                        a.semester, a.ects, 
                        Concat(b.name ,\" \", b.lastname) as professorname, 
                        b.id as users,
                        d.grade,
                        d.status,
                        e.name statusname
                    from courses a 
                    inner join users b on a.users = b.id 
                    inner join courses_type c on a.courses_type = c.id 
                    left join enrolements d on d.courses = a.id and d.users = $id
                    left join status e on e.id = d.status
                    order by a.id";
                
                                
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id,
                'title' => $row->title,
                'description' => $row->description,
                'type' => $row->type,
                'courses_type' => $row->courses_type,
                'semester' => $row->semester,
                'ects' => $row->ects,
                'professorname' => $row->professorname,
                'users' => $row->users,
                'grade' => $row->grade,
                'status' => $row->status,
                'statusname' => $row->statusname
            );
        }

        return $data;
    }


    public function selectStudentCourses()
    {

        $requesthandler =  new requesthandler();
        $db = dbhandler::getInstance();
        $id = requestHandler::get()->id;

        $sql = "select a.*, b.name, b.lastname from courses a
                    inner join users b on b.id = a.users
                    where a.users = $id;
        ";
                
                                
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id,                
                'title' => $row->title,
                'description' => $row->description,                
                'courses_type' => $row->courses_type,
                'semester' => $row->semester,
                'ects' => $row->ects                
            );
        }

        return $data;
    }    
    
}
