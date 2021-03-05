<?php 

include_once "models/courses.php";

 
 class methods{     
     
    public function getcourses()
    {
        $obj = new Courses();
        $data = $obj->select();
        responseHandler::respond($data);

    }

    public function update(){
        $obj = new Courses();

        if(requestHandler::get()!=null){
            $posts = requestHandler::get();
        }
        //print_r($posts);
        $data = $obj->update($posts);
        responseHandler::respond($data);
    }


    public function insert(){
        $obj = new Courses();

        if(requestHandler::get()!=null){
            $posts = requestHandler::get();
        }

        $data = $obj->insert($posts);
        responseHandler::respond($data);
    }


    public function delete(){
        $obj = new Courses();

        if(requestHandler::get()!=null){
            $posts = requestHandler::get();
        }
                
        $data = $obj->delete($posts);
        responseHandler::respond($data);
    }

   


 }  

 