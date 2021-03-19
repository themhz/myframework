<?php
include_once "models/users.php";

class methods
{
    
    public function update(){
        $obj = new Users();

        if(requestHandler::get()!=null){
            $posts = requestHandler::get();
        }        
        $data = $obj->updateProfile($posts);                
          
        $obj->check();

        responseHandler::respond($data);
    }    

}
