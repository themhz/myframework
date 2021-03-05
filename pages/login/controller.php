<?php
include_once "models/users.php";

class methods
{    

    public function authentication()
    {
        $users = new Users();
        $data = $users->check();

        if($data!=""){
            responseHandler::respond($data);
        }else{
            responseHandler::respond("nouser");
        }
                                

    }

 

}
