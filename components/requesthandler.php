<?php
//This is a basic class requesthandler that is used to get the posted and geted variables. I use both post and get from the same
//method since it is faster and more usefull. No typecasting is happening here.

class requestHandler
{

  public static function get($var = null)
  {
    
    //Get the requested variable even if it is posted as post or as get method
    $val = null;
    $value = null;    
    if ($var != null) {
      if (isset($_POST[$var])) {

        $val = $_POST[$var];
      } elseif (isset($_GET[$var])) {

        $val = $_GET[$var];
      } 
      $value = $val;

    } else {
      $value = json_decode(file_get_contents('php://input'));
      
    }

    return $value;
  }
}
