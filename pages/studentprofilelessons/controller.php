<?php
include_once "models/courses.php";
include_once "models/enrolements.php";

class methods
{


    public function getlessons()
    {
        $obj = new Courses();        
        $data = $obj->selectwithStudentGrade();                        
        $data['relations'] = $obj->getRelations();    
        
        responseHandler::respond($data);
    }

    public function enrollcorse(){
        $obj = new Enrolements(); 
        if(requestHandler::get()!=null){
            if($obj->checkuserenrolement(requestHandler::get()) != requestHandler::get()->courses){
                $data = $obj->insert(requestHandler::get());
            }
            
        }        
        
        
        responseHandler::respond($data);
    }

    public function abandonecorse(){
        $obj = new Enrolements();
        if(requestHandler::get()!=null){ 
            $data = $obj->delete(requestHandler::get());         
        }                       
        
        responseHandler::respond($data);
    }    

 

}
