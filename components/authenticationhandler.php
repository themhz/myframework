<?php
/* 
 * Copyright (C) 2016 themhz
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
use \Firebase\JWT\JWT;

class authenticationHandler{

    static function login(){

        // $username = requesthandler::get('username')!=null? requesthandler::get('username') : "";
        // $inputPass = requesthandler::get('password')!=null? requesthandler::get('password'): "";

        // //echo requesthandler::get('username');
        
        // include __DIR__.'/../models/accounts.php';
        // $accounts =  new accounts();
        // $account = $accounts->select(1, 'id', 'ASC', 'username', "$username", 1);

        // //print_r($account);
        // //die();
        // $hashedPass = "";
        // $userName = "";
        // $fullName = "";
        // $userId = "";
        
        // if($account['total'][0]->count >0){
        //     $hashedPass = $account['rows'][0]['row']['password'];
        //     $userName = $account['rows'][0]['row']['username'];
        //     $fullName = $account['rows'][0]['row']['fullname'];
        //     $userId = $account['rows'][0]['row']['id'];
        // }
       
        // if(crypt($inputPass, $hashedPass) == $hashedPass){
            
        //     return array('status'=>true,'token'=> self::generateToken($userId, $userName, $fullName));

        // }else{
            
        //     return array('status'=>false,'data'=>"{'status' : 'fail','resp':{}}");
        // }

        return array('status'=>true,'token'=> '');
        
    }

    static function getUserDetails($id){
        include __DIR__.'/../models/accounts.php';
        $accounts =  new accounts();
        $account = $accounts->select(1, 'id', 'ASC', 'id', "$id", 1);
        return (object)$account['rows'][0]['row'];

        
    }

    static function authenticate(){
        return array('status'=>true,'data'=>'');
    }

    static function register(){
       
        $resp='';
        $status=false;
        $account = new stdClass();

        if($_SESSION['phrase'] == requesthandler::get('captcha')) {

            include __DIR__.'/../models/accounts.php';
            include __DIR__.'/../components/homepage/models/homepage.php';
            
            $account->fullname = requesthandler::get('fullname');
            $account->email    = requesthandler::get('email');
            $account->username = requesthandler::get('username');
            $account->maxstudents = requesthandler::get('maxstudents');
            $account->password = cryptPass(requesthandler::get('password'));

            $account->time_zone = requesthandler::get('timezones');

            $account->phone = "";
            $account->confirmed = 0;
            $account->country = "";
            $account->city = "";
            $account->address = "";

            $accounts =  new accounts();
            //$result = $accounts->checkUser($account->email);
            $result = $accounts->select(1, 'id', 'asc', 'email,username', "$account->email,$account->username", 1,'or');

                      
            if($result['total'][0]->count == 0){
            //if(count($result) == 0){
                //$account->regtoken = base64_encode(mcrypt_create_iv(32));
                //$account->regtoken = bin2hex(random_bytes(32));                
                $account->regtoken = '';
                $result = $accounts->insert($account);
                
                if($result == false){
                    $status = false;
                    $resp   = 'cant make the insert for some reason!!';
                }else{
                    $status = true;
                    //database insert status (if it has been inserted)
                    $resp   = 'Record insterted';
                }
            }else{
                //database result validatiion
                $status = false;
                $resp   = 'please choose another email or username. ';

            }

        }else{
                //captcha validatiion
                $status = false;
                $resp   = 'please type the captcha correct. If you cant understand the picture please try to refresh it by presing the "Refresh captcha" button.';
        }
        
        
        if($status == true){           
        
            $result = self::sendConfirmByemail($account->email, $account->fullname, $account->regtoken);
            $status = $result['status'];
            $resp = $result['resp'];
        }

        return  array('status' => $status, 'resp' =>$resp);
        //return $result;
    }

    static function unlock(){
        // //check if the link has the token
        // //validate the token in the database
        
        // //include __DIR__.'/../models/account_unlock_requests.php';
        // $account_unlock_requests =  new account_unlock_requests();

        // $account_unlock_request =  new stdClass();
        // $account_unlock_request->used = 1;
        // $account_unlock_request->passtoken = requesthandler::get('passtoken');

        // $result = $account_unlock_requests->update($account_unlock_request);


      
        // return $result;

    }

    static function unlockcheck(){

        // //check the captcha code
        // $status = false;
        // $resp = "";
        //  if($_SESSION['phrase'] == requesthandler::get('captcha')) {

        //     $token = '';
        //     include __DIR__.'/../models/accounts.php';
        //     include __DIR__.'/../models/account_unlock_requests.php';
            
        //     $account = new stdClass();
            
        //     $account->email = requesthandler::get('email');         

        //     //check if the e-mail exists, 
        //     $accounts =  new accounts();
        //     $account_unlock_requests =  new account_unlock_requests();
        //     $result = $accounts->checkUser($account->email);
            
        //     if(count($result)){
                
        //         //generate the token
        //         $token = bin2hex(openssl_random_pseudo_bytes(16));
              
        //         $account_unlock_request = new stdClass();
        //         $account_unlock_request->account_id = $result[0]->id;
        //         $account_unlock_request->time_zone = $result[0]->time_zone;
        //         $account_unlock_request->token = $token;
        //         $account_unlock_request->used = 0;
        //         $account_unlock_requests->insert($account_unlock_request);

        //         //send the link to the e-mail of the user 
        //         $mailresult = self::sendTokenByEmail($account->email, $result[0]->fullname, $token);
        //         $status = $mailresult['status'];
        //         $resp =   $mailresult['resp'];
        //     }else{
        //         //Account not found
        //         $status = false;
        //         $resp =  'Account not found';
        //     }

        // }else{
        //     //captcha code error
        //         $status = false;
        //         $resp =  'You didnt type the captcha code correctly';
        // }
               

        // return json_encode(array('status' => $status, 'resp' =>$resp));
                        
    }

    static function confirmUserEmail(){

        //self::sendConfirmByemail($email, $name, $token);

        include __DIR__.'/../models/accounts.php';
        $accounts =  new accounts();

        $requesthandler =  new requesthandler();
        $values = new stdClass();
        $values->confirmed = 1;

        $where = new stdClass();
        $where->regtoken = urldecode(requesthandler::get('confirmtoken'));
        //echo $where->regtoken.'<br>';
        //echo urldecode($_REQUEST["confirmtoken"]).'<br>';
        //echo 'HPp1IU4gF6mOj4vhtMaupr/4gko4gMhwIIgS++5rj/4=';
        //return;
        $return = $accounts->update($values, $where);

        //echo json_encode($return);
        Header('Location: '.Config::read('scriptUrl'));


    }

    static function sendTokenByEmail($email, $name, $token){
        // $mail = new PHPMailer\PHPMailer\PHPMailer;
        
        // $status = false;
        // $resp = "";
        // //$mail->SMTPDebug = 2;                               // Enable verbose debug output

        // $mail->isSMTP();                                    // Set mailer to use SMTP
        // $mail->Host = Config::read('mail_server');                  // Specify main and backup SMTP servers
        // $mail->SMTPAuth = true;                             // Enable SMTP authentication
        // $mail->Username = Config::read('mail_bot_sender');              // SMTP username
        // $mail->Password = Config::read('mail_bot_sender_pass');                 // SMTP password
        // $mail->Port = 25;    
        // //$mail->Port = 465;

        // $mail->SMTPOptions = array(
        //     'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true
        //     )
        // );                                // TCP port to connect to
        
        // //$mail->SMTPDebug = 2;

        // $mail->setFrom(Config::read('mail_bot_sender'), 'fcmsoft');
        // $mail->addAddress($email, $name);     // Add a recipient

        // //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        // $mail->isHTML(true);                                  // Set email format to HTML

        // $mail->Subject = 'Your password reset request ';
        // $mail->Body    = 'this is the token generated '.Config::read('scriptUrl').'?resetpass=true&html=true&passtoken='. $token;
        // $mail->AltBody = 'this is the token generated '.Config::read('scriptUrl').'?resetpass=true&html=true&passtoken='. $token;   
      
        // if(!$mail->send()) {

        //     $status = false;
        //     $resp = 'Message could not be sent.'. $mail->ErrorInfo;            
        // } else {

        //     $status = true;
        //     $resp = 'Message has been sent';
        // }

        // return  array('status' => $status, 'resp' =>$resp);
    }

    static function sendConfirmByemail($email, $name, $token){
 
        // $mail = new PHPMailer\PHPMailer\PHPMailer;

        // //$mail->SMTPDebug = 2;                             // Enable verbose debug output

        // $mail->isSMTP();                                    // Set mailer to use SMTP
        // $mail->Host = Config::read('mail_server');                  // Specify main and backup SMTP servers
        // $mail->SMTPAuth = true;                             // Enable SMTP authentication
        // $mail->Username = Config::read('mail_bot_sender');              // SMTP username
        // $mail->Password = Config::read('mail_bot_sender_pass');                 // SMTP password
        
        // //$mail->SMTPSecure = 'tls';                        // Enable TLS encryption, `ssl` also accepted
        // $mail->isHTML(true);                                // Set email format to HTML
        // //$mail->Port = 465;
        // $mail->Port = 25;

        // $mail->SMTPOptions = array(
        //     'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true
        //     )
        // );                                // TCP port to connect to
        
        // //$mail->SMTPDebug = 2;

        // $mail->setFrom(Config::read('mail_bot_sender'), 'fcmsoft');
        // $mail->addAddress($email, $name);     // Add a recipient
        // $mail->addReplyTo(Config::read('mail_bot_sender'), 'fcmsoft');


        
        // //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        // $mail->isHTML(true);                                  // Set email format to HTML

        // $mail->Subject = 'Please confirm your e-mail by clicking the url ';
        // $href= Config::read('scriptUrl').'?confirm=true&register=true&confirmtoken='. urlencode($token);
        // $mail->Body    = "<a href='$href'>Please click on this url so we can confirm that you are you or follow the url: $href</a>";
        // $mail->AltBody = 'Please click on this url so we can confirm that you are you: '.$href;

        // $status = false;
        // if(!$mail->send()) {
        //     $status = false;
        //     $resp  = 'Message could not be sent.';
        //     $resp .='Mailer Error: ' . $mail->ErrorInfo;

        // } else {
        //     $status = true;
        //     $resp   = 'Message has been sent';
        // }
            
        
        // return  array('status' => $status, 'resp' =>$resp);
    
    }

    static function generateToken($userId, $userName, $fullName){

            //     //$tokenId    = base64_encode(mcrypt_create_iv(32));
            //     $tokenId    = base64_encode(random_bytes(32));                
            //     $issuedAt   = time();              
            //     $notBefore  = $issuedAt;  //Adding 10 seconds
            //     $expire     = $notBefore + (7 * 24 * 60 * 60);//Adding a week // + 7200; // Adding 60 seconds
            //     $serverName = Config::read('scriptUrl'); /// set your domain name

            //     /*
            //      * Create the token as an array
            //      */

            //     $data = array('iat' => $issuedAt,    // Issued at: time when the token was generated
            //         'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            //         'iss'  => $serverName,       // Issuer
            //         'nbf'  => $notBefore,        // Not before
            //         'exp'  => $expire,           // Expire
            //         'data' => array(                  // Data related to the logged user you can set your required data
            //                     'id'   => $userId, // id from the users table
            //                     'username' => "$userName", //  name
            //                     'fullname' => "$fullName" //  name
            //                   )
            //     );

            //   $secretKey = base64_decode(Config::read('SECRET_KEY'));
            //   /// Here we will transform this array into JWT:
            //   $jwt = JWT::encode(
            //             $data, //Data to be encoded in the JWT
            //             $secretKey, // The signing key
            //             Config::read('ALGORITHM')
            //            );

            //   $unencodedArray = array('jwt' => $jwt);

            //   return  "{'status' : 'success','resp':".json_encode($unencodedArray)."}";

    }

    static function resetpass(){
        // include __DIR__.'/../models/accounts.php';
        // include __DIR__.'/../models/account_unlock_requests.php';
        // $accounts =  new accounts();
        // $account_unlock_requests =  new account_unlock_requests();
        // $msg = "";

        // $password = cryptPass(requesthandler::get('password'));
        
        // $user = $account_unlock_requests->selectUserByToken(requesthandler::get('passtoken'));

        
        // if(count($user)>0){

        //     $account = new stdClass();
        //     $account->id = $user[0]->account_id;
        //     $account->password = $password;

        //     self::unlock();
        //     $accounts->updatePass($account);
        //     $msg = "password was updated";
        // }else{
        //     $msg = "password has already been updated";
        // }
        
        
        return  "{'status' : 'success','resp': ''}";
    }

}