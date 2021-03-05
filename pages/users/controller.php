<?php
include_once "models/users.php";
include_once "models/role.php";

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
        //print_r($posts);
        $data = $obj->update($posts);
        responseHandler::respond($data);
    }


    public function insert(){
        $obj = new Users();

        if(requestHandler::get()!=null){
            $posts = requestHandler::get();
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

    


 

}
