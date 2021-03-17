<h1>Χρήστες Διδάσκοντες-Μαθητές-Γραματεια</h1>
<!--Εδώ έχουμε το table  `-->

<table id="table">   
    <tbody id="tbody"></tbody>
</table>


<!-- Εδώ έχουμε το κώδικα του popup -->
<!-- The Modal -->
<div id="myModal" class="modal">
   <!-- Modal content -->
   <div class="modal-content">
      <span id="closepopup" class="close">X</span>
      <div class="modal-frame">
         <table>
            <tr>
               <td>Όνομα</td>
               <td><input type="text" value="" id="name"></td>
            </tr>
            <tr>
               <td>Επίθετο</td>
               <td><input type="text" value="" id="lastname"></td>
            </tr>
            <tr>
               <td>Email</td>
               <td><input type="text" value="" id="email"></td>
            </tr>
            <tr>
               <td>Κωδικός πρόσβασης</td>
               <td><input type="password" value="" id="password"></td>
            </tr>
            <tr>
               <td>Ρόλος</td>
               <td><select id="role" onchange="checkrolesemester()"></select></td>
            </tr>
            <tr id="fieldsemester">
               <td>Εξάμηνο</td>
               <td><select id="semester" data-custom="true">
                  <option selected value="1">Πρώτο</option>
                  <option value="2">Δεύτερο</option>
                  <option value="3">Τρίτο</option>
                  </select>
               </td>
            </tr>
            <tr>
               <td>Τηλέφωνο</td>
               <td><input type="text" value="" id="mobilephone"></td>
            </tr>
            <tr>
               <td>Address</td>
               <td><textarea id="address" cols="15" rows="5"></textarea></td>
            </tr>
            <tr>
               <td>Ημερομηνία/Γέννισης</td>
               <td><input type="text" value="" id="birthdate"></td>
            </tr>
            <tr>
               <td>Ημερομηνία Εγγραφής</td>
               <td><input type="text" value="" id="regdate"></td>
            </tr>
            <tr>
               <td>Α/Μ</td>
               <td><input type="text" value="" id="am"></td>
            </tr>
            <tr>
               <td></td>
               <td><input type="button" value="save" onclick="tablehandler.save()"><button onclick = "formValidator.validate()">validate</button></td>
            </tr>
         </table>
      </div>
   </div>
</div>

<!-- Trigger/Open The Modal -->
<button id="btnNewUser">Νέος Χρήστης</button>


<script>
   //Το load της σελίδας για να φορτώσει το ajax
   var actionType;
   var gid;         
   var tablehandler;

   document.addEventListener('readystatechange', function(evt) {      
        if(evt.target.readyState == "complete"){                
            document.getElementById("fieldsemester").style.display="none";           
            var tableid = "table";
            var getAllUrl = "/myframework/users/getusers?format=raw";
            var rows = ["id", "name", "email", "rolename", "regdate", "am"];
            var getItemUrl= "/myframework/users/getusers?format=raw";
            var deleteUrl = "/myframework/users/delete?format=raw";
            var updateUrl = "/myframework/users/update?format=raw";
            var insertUrl = "/myframework/users/insert?format=raw";
            var deleteconfirmmsg = "Είστε σίγουρος ότι θέλετε να διαγράψετε τον μαθητή?";
            var popupwindow = "myModal";
            var newbutton = "btnNewUser";
            var closepopupbutton = "closepopup";
            var clicksaveForPopup = function(){               

               var fields = [{id: "name", type: "textbox", required : true}
                              ,{id: "lastname", type: "textbox", required : true}
                              ,{id: "email", type: "email", required : true}
                              ,{id: "password", type: "password", required : true}
                              ,{id: "role", type: "select", required : true}
                              ,{id: "mobilephone", type: "phone", required : false}
                              ,{id: "address", type: "textbox", required : false}
                              ,{id: "birthdate", type: "date", required : false}
                              ,{id: "regdate", type: "date", required : false}
                              ,{id: "am", type: "textbox", required : false}
                              ];
                     formValidator =  new FrormValidator(fields);
                     return formValidator.validate();
            };
            var clickRow = function(id, obj){
               
               if(obj.editrows.role==3){                                    
                  document.getElementById("fieldsemester").style.display="";
               }else{                  
                  document.getElementById("fieldsemester").style.display = "none";
               }
               getCurrentSemester(id);
            };

            tablehandler = new TableHandler(tableid, getAllUrl, rows, getItemUrl, deleteUrl, updateUrl, insertUrl, deleteconfirmmsg, popupwindow, newbutton, closepopupbutton, clicksaveForPopup, clickRow);
            tablehandler.loadtable();

        }
    }, false);


    function getCurrentSemester(id){       
        var xhttp = new XMLHttpRequest(); // Δημιουργεί ένα object XMLHttpRequest         
        xhttp.onreadystatechange = function() { //Ένα promise ώστε όταν επιστρέχει το αποτέλεσμα από τον server να εκτελεστεί η παρακάτω ανώμυμη συνάρτηση. 

         if (this.readyState == 4 && this.status == 200) {                                
                var response = eval('(' + this.responseText + ')'); //Στάνταρ λειτουργία για να μετασχηματίσουμε το αποτέλεσμα που παίρνουμε από τον server σε JSON                  
                  document.getElementById("semester").value = response.data[0].semester;
            }
        };
        
        xhttp.open("POST", "/myframework/users/getcurrentsemester?format=raw", true); //Προετιμασία της αποστολής δηλώνοντας την μέθοδο το url και τρίτη παράμετρος ότι είναι ασύγχρονη αποστολή.                
        
        xhttp.send(JSON.stringify({'id':id})); //Τελική αποστολή των δεδομένων στο endpoint. μετά από εδώ ο κώδικας θα μεταφερθεί στην γραμμή 51 που είναι το promise. Δηλαδή μόλις ολοκληρωθεί

    }

    function checkrolesemester(){
         if(document.getElementById("role").value == 3){
            document.getElementById("fieldsemester").style.display="";
         }else{
            document.getElementById("fieldsemester").style.display="none";
         }
    }
     
</script>