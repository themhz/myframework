<?php 

 
 class methods{     
     public $test = "";
     public function test(){
        
        global $test;
        $test = "this is a test variable";
        echo "calling include method";

    }
   


 }  

 