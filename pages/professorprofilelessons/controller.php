<?php
include_once "models/courses.php";
include_once "models/enrolements.php";

class methods
{


    public function getlessons()
    {
        $obj = new Courses();        
        $data = $obj->selectStudentCourses();
        
        responseHandler::respond($data);
    }

    public function getenroledstudents(){
        $obj = new Enrolements();        
        $data = $obj->getenroledstudents();
        
        responseHandler::respond($data);
    }

    public function updategrade(){
        $obj = new Enrolements();        
        $data = $obj->updategrade();
        
        responseHandler::respond($data);
    }

 

}
