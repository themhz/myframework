<?php

class Studentprogress{

    public function select()
    {
        
        $db = dbhandler::getInstance();
        $sql = "select a.*, b.name rolename , count(a.id) lessons, semester
                    from users a
                    inner join role b on a.role = b.id
                    inner join enrolements c on c.user_id = a.id
                    inner join semester d on d.user_id = a.id                    
                    where a.role = 3                    
                    ";                                        
        
                            
         if(requestHandler::get()!=null){
             $id = requestHandler::get()->id;            
             $sql .= " and a.id=". $id;
         }

         $sql .= " group by a.id 
         order by semester asc, a.lastname asc";

         
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id,
                'name' => $row->name,
                'lastname' => $row->lastname,
                'email' => $row->email,
                'password' => $row->password,
                'role' => $row->role,
                'mobilephone' => $row->mobilephone,
                'address' => $row->address,
                'birthdate' => $row->birthdate,
                'regdate' => $row->regdate,
                'am' => $row->am,
                'rolename' => $row->rolename,
                'lessons' => $row->lessons,
                'semester' =>$row->semester

            );
        }

        return $data;
    }



    public function getentrolements(){
        $db = dbhandler::getInstance();
        $sql = "select b.id,
                        b.title,
                        b.description,
                        b.ects,
                        a.grade, 
                        c.name type                        
                        from enrolements a
                    inner join courses b on b.id = a.course_id
                    inner join coursetypes c on c.id=b.type
                where 1=1
                ";                                        
        
                            
        if(isset(requestHandler::get()->id) &&  requestHandler::get()->id!=""){
            $id = requestHandler::get()->id;
            $sql .= " and a.user_id=". $id;
        }

         if(isset(requestHandler::get()->type) &&  requestHandler::get()->type!=""){
            $type = requestHandler::get()->type;
            $sql .= " and b.type=". $type;
        }
        

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id,
                'title' => $row->title,
                'description' => $row->description,
                'ects' => $row->ects,
                'grade' => $row->grade,
                'type' => $row->type
            );
        }

        return $data;        
    }



    public function remainingenrolements(){
        $db = dbhandler::getInstance();
        $sql = "select distinct b.id,
                        b.title,
                        b.description,
                        b.ects,                        
                        c.name type                        
                        from courses b
                    left join enrolements a on b.id = a.course_id
                    inner join coursetypes c on c.id=b.type
                where 1=1
                ";                                        
        
                            
        if(isset(requestHandler::get()->id) &&  requestHandler::get()->id!=""){
            $id = requestHandler::get()->id;
            $sql .= " and (a.user_id!=". $id." or a.user_id is null) ";
        }

         if(isset(requestHandler::get()->type) &&  requestHandler::get()->type!=""){
            $type = requestHandler::get()->type;
            $sql .= " and b.type=". $type;
        }
        
        
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        
        $data['data'][] = array();

        foreach ($results as $row) {
            $data['data'][] = array(
                'id' => $row->id,
                'title' => $row->title,
                'description' => $row->description,
                'ects' => $row->ects,                
                'type' => $row->type
            );
        }

        return $data;        
    }




}