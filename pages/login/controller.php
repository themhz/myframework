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


    public function logout(){
        session_destroy();
        header("Location:login");

    }

 

}
