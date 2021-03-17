<?php

class Studentprogress{

    public function select()
    {
        
        $db = dbhandler::getInstance();
        $sql = "select a.*, b.name rolename , count(a.id) lessons, semester
                    from users a
                    inner join role b on a.role = b.id
                    inner join enrolements c on c.users = a.id
                    inner join semester d on d.users = a.id                    
                    where a.role = 3                    
                    ";                                        
        
         $data= array();
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



    public function getEnrolledCourses(){
        $db = dbhandler::getInstance();
        $sql = "select b.* from enrolements a
        inner join courses b on b.id = a.courses
        where a.users = ".requestHandler::get()->id."
        order by semester asc
        ;";                                        
                                    
        

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        $data= array();        
        foreach ($results as $row) {
            $data['EnrolledCourses'][] = array(
                'id' => $row->id,
                'users' => $row->users,
                'title' => $row->title,
                'courses_type' => $row->courses_type,
                'description' => $row->description,
                'semester' => $row->semester,
                'ects' => $row->ects
            );
        }

        return $data;
    }



    public function getBasicEnrolledCoursesWithPassGrade(){
        $db = dbhandler::getInstance();
        $sql = "select b.* from enrolements a
        inner join courses b on b.id = a.courses
        where a.users = ".requestHandler::get()->id." 
         and grade>=5 and courses_type = 1 
        order by semester asc 
        ;";                                        
                                    
        

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        
        $data= array();        
        if(count($results)>0){
            foreach ($results as $row) {
                $data['BasicEnrolledCoursesWithPassGrade'][] = array(
                    'id' => $row->id,
                    'users' => $row->users,
                    'title' => $row->title,
                    'courses_type' => $row->courses_type,
                    'description' => $row->description,
                    'semester' => $row->semester,
                    'ects' => $row->ects
                );
            }
        }else{
            $data['BasicEnrolledCoursesWithPassGrade'][] = array();
        }
        

        return $data;        
    }

    public function getOptionalEnrolledCoursesWithPassGrade(){
        $db = dbhandler::getInstance();
        $sql = "select b.* from enrolements a
        inner join courses b on b.id = a.courses
        where a.users = ".requestHandler::get()->id." 
         and grade>=5 and courses_type = 2
        order by semester asc 
        ;";                                        
                                    
        

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        
        $data= array();
        if(count($results)>0){
            foreach ($results as $row) {
                $data['OptionalEnrolledCoursesWithPassGrade'][] = array(
                    'id' => $row->id,
                    'users' => $row->users,
                    'title' => $row->title,
                    'courses_type' => $row->courses_type,
                    'description' => $row->description,
                    'semester' => $row->semester,
                    'ects' => $row->ects
                );
            }
        }else{
            $data['OptionalEnrolledCoursesWithPassGrade'][] = array();
        }    

        return $data;        
    }
    

    public function getBasicCoursesforDegree(){
        $db = dbhandler::getInstance();
        $sql = "select * from courses where id not in
                (select b.id from enrolements a
                    inner join courses b on b.id = a.courses
                    where a.users = ".requestHandler::get()->id." and grade>=5  
                )
                and courses_type = 1
        ;";                                        
                                    
        
        $data= array();
        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        

        if(count($results)>0){
            foreach ($results as $row) {
                $data['BasicCoursesforDegree'][] = array(
                    'id' => $row->id,
                    'users' => $row->users,
                    'title' => $row->title,
                    'courses_type' => $row->courses_type,
                    'description' => $row->description,
                    'semester' => $row->semester,
                    'ects' => $row->ects
                );
            }
        }else{
            $data['BasicCoursesforDegree'][] = array();
        }    

        return $data;        
    }


    public function getOptionCoursesforDegree(){
        $db = dbhandler::getInstance();
        $sql = "select * from courses where id not in
                (select b.id from enrolements a
                    inner join courses b on b.id = a.courses
                    where a.users = ".requestHandler::get()->id." and grade>=5  
                )
                and courses_type = 2
        ;";                                        
                                    
        

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        $data= array();

        if(count($results)>0){
            foreach ($results as $row) {
                $data['OptionCoursesforDegree'][] = array(
                    'id' => $row->id,
                    'users' => $row->users,
                    'title' => $row->title,
                    'courses_type' => $row->courses_type,
                    'description' => $row->description,
                    'semester' => $row->semester,
                    'ects' => $row->ects
                );
            }
        }else{
            $data['OptionCoursesforDegree'][] = array();
        }    
        
        return $data;        
    }


    public function getEctsGathered(){
        $db = dbhandler::getInstance();
        $sql =  "select sum(ects) ects from courses
                where id in(
                    select courses from enrolements
                    where users = ".requestHandler::get()->id."
                )
                ;";           
                         
                
        $data= array();

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        
        
        if(count($results)>0 && isset($results[0]->ects)){
            foreach ($results as $row) {
                $data['EctsGathered'][] = array(                    
                    'ects' => $row->ects
                );
            }
        }else{            
            $data['EctsGathered'][] = array(                                              
                'ects' => 0
            );
        }
        
        return $data;        
    }


    public function getEctsforgraduation(){
        $db = dbhandler::getInstance();
        $sql = "select 40-sum(ects) ects from courses
                where id in(
                    select courses from enrolements
                    where users = ".requestHandler::get()->id."
                )
                ;";                                        
                         
                
        $data= array();

        $sth = $db->dbh->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(PDO::FETCH_OBJ);
        
        
        if(count($results)>0 && isset($results[0]->ects)){
            foreach ($results as $row) {
                $data['Ectsforgraduation'][] = array(                     
                    'ects' => $row->ects
                );
            }
        }else{            
            $data['Ectsforgraduation'][] = array(                    
                'ects' => 0
            );
        }

        return $data;        
    }

    


}