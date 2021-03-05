<?php
include_once "models/studentprogress.php";

class methods
{
    public function select()
    {
        $obj = new Studentprogress();
        $data = $obj->select();
        responseHandler::respond($data);
    } 


    public function getentrolements()
    {
        $obj = new Studentprogress();
        $data = $obj->getentrolements();
        responseHandler::respond($data);
    } 

    public function remainingenrolements()
    {
        $obj = new Studentprogress();
        $data = $obj->remainingenrolements();
        responseHandler::respond($data);
    } 


    

    

    
}
