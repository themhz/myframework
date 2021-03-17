<h1>Κανονισμός Σπουδών</h1>
        <p class="peisodos">
            Συνδεθείτε με τα συνθηματικά σας
        </p>
                   
         <table class="login">
               <tr>
                  <td>User name</td><td><input type="text" id="email"  value=""></td>
               </tr>
               <tr>
                  <td>Password</td><td><input type="password" id="password" value=""></td>
               </tr>            
               <tr><td rowpan="2"><button id="btnlogin" onclick="login()">Υποβολή</button></td></tr>
         </table>        
      

<script>

   document.addEventListener('readystatechange', function(evt) {
         if(evt.target.readyState == "complete"){            
            document.getElementById("nav2").style.display="none";
            document.getElementById("login").style.display="none";            
         }
         
   });


   function login() {
      //document.getElementById("btnlogin").disabled = true;
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            if(response=="nouser"){
               alert("Λάθος username ή password")   
            }else{               
               window.location.replace("default");

            }
            
            
         }
      };
      xhttp.open("POST", "/myframework/login/authentication?format=raw", true);
      xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      xhttp.send(JSON.stringify({
         "email": document.getElementById("email").value,
         "password": document.getElementById("password").value
      }));
   }
</script>