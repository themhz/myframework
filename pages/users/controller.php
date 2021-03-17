<?php
include_once "models/users.php";
include_once "models/role.php";
include_once "models/semester.php";

class methods
{


    public function getusers()
    {
        $users = new Users();        
        $data = $users->select();                        
        $data['relations'] = $users->getRelations();    
        
        responseHandler::respond($data);
    }

    public function getroles()
    {
        $users = new Role();
        $data = $users->select();
        responseHandler::respond($data);
    }

    public function getteachers()
    {
        $users = new Users();
        $data = $users->getteachers();
        responseHandler::respond($data);
    }


    
    public function update(){
        $obj = new Users();

        if(requestHandler::get()!=null){
            $posts = requestHandler::get();
        }        
        $data = $obj->update($posts);                

        $semester = new Semester();
        $doinsert = $semester->checkIfExists($posts);
        
        if($doinsert==true){            
            $semester->insert($posts);
        }

        responseHandler::respond($data);
    }


    public function insert(){
        $obj = new Users();

        if(requestHandler::get()!=null){
            $posts = requestHandler::get();
        }

        $semester = new Semester();
        $exists = $semester->checkIfExists($posts);
        if(!$exists){
            $semester->insert($posts);
        }

        $data = $obj->insert($posts);
        responseHandler::respond($data);
    }


    public function delete(){
        $obj = new Users();

        if(requestHandler::get()!=null){
            $posts = requestHandler::get();
        }
                
        $data = $obj->delete($posts->id);
        responseHandler::respond($data);
    }


    public function getcurrentsemester(){
        $obj = new Semester();
        $data = $obj->getUserSemester();
        responseHandler::respond($data);
    }

    


 

}
