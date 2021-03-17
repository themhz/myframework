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


    public function getStatisticData()
    {
        $obj = new Studentprogress();
        $data[] = $obj->getEnrolledCourses();
        $data[] = $obj->getBasicEnrolledCoursesWithPassGrade();
        $data[] = $obj->getOptionalEnrolledCoursesWithPassGrade();        
        $data[] = $obj->getBasicCoursesforDegree();
        $data[] = $obj->getOptionCoursesforDegree();
        $data[] = $obj->getEctsforgraduation();
        $data[] = $obj->getEctsgathered();
        
        responseHandler::respond($data);
        
    } 



    
}
