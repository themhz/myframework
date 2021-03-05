<?php 
    $page='';
    $directory='';
    if(isset($_REQUEST["page"])){
        $page = $_REQUEST['page'];
        $directory = getcwd()."/pages/$page";
    }

    //Check If the directory exists
    if(is_dir($directory)){
        //Load the directory
        include $directory.'/controller.php';
    }else{
        //Load the default directory
        include getcwd()."/pages/default/controller.php";
    }
    

    if(isset($_REQUEST["method"])){
     
        $method = stripslashes($_REQUEST["method"]);
        $obj= new methods; 
        call_user_func_array(array($obj, $method),array());
    
    }
