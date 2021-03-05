<h1>Μαθήματα</h1>
<!--Εδώ έχουμε το table  `-->

<table id="table">
   <tbody>
      <tr>
         <th>Κωδικός</th>
         <th>Μάθημα</th>
         <th>Περιγραφή</th>
         <th>Είδος</th>
         <th>Εξάμηνο</th>
         <th>Μονάδες</th>
         <th>Καθηγητής</th>
         <th>Ενέργημα</th>
      </tr>
   </tbody>
</table>


<!-- Εδώ έχουμε το κώδικα του popup -->
<!-- The Modal -->
<div id="myModal" class="modal">
   <!-- Modal content -->
   <div class="modal-content">
      <span class="close">X</span>
      <div class="modal-frame">
         <table>
            <tr>
               <td>Όνομα μαθήματος</td>
               <td><input type="text" value="" id="title"></td>
            </tr>
            <tr>
               <td>Περιγραφή</td>
               <td><textarea id="description" cols="15" rows="5"></textarea></td>
            </tr>
            <tr>
               <td>Είδος</td>
               <td><select id="type">
                     <option value="1">Υποχρεωτικό</option>
                     <option value="2">Επιλογής</option>
                  </select></td>
            </tr>
            <tr>
               <td>Εξάμηνο</td>
               <td><select id="semester">
                     <option value="1">Πρώτο</option>
                     <option value="2">Δεύτερο</option>
                     <option value="3">Τρίτο</option>
                  </select></td>
            </tr>
            <tr>
               <td>Καθηγητής</td>
               <td><select id="proffessor"></select></td>
            </tr>
            <tr>
               <td>Ects</td>
               <td><input type="ects" value="" id="ects"></td>
            </tr>
            <tr>
               <td></td>
               <td><input type="button" value="save" onclick="save()"></td>
            </tr>
         </table>
      </div>
   </div>

</div>

<script>
   //Το load της σελίδας για να φορτώσει το ajax
   var actionType;
   var gid;
   document.onreadystatechange = function() {
      if (document.readyState === 'complete') {
         getCourses();
         getTeachers();

         //Εδώ έχουμε τον κώδικα του popup 

         // Get the modal
         var modal = document.getElementById("myModal");

         // Get the button that opens the modal
         var btn = document.getElementById("btnNewLesson");


         // Get the <span> element that closes the modal
         var span = document.getElementsByClassName("close")[0];

         // When the user clicks the button, open the modal 
         btn.onclick = function() {

            modal.style.display = "block";
            actionType = "insert";
         }

         // When the user clicks on <span> (x), close the modal
         span.onclick = function() {
            clearForm();
            modal.style.display = "none";
         }

      }
   }

   //για να πιάνει το escape event
   document.onkeydown = function(evt) {
      evt = evt || window.event;
      var isEscape = false;
      if ("key" in evt) {
         isEscape = (evt.key === "Escape" || evt.key === "Esc");
      } else {
         isEscape = (evt.keyCode === 27);
      }
      if (isEscape) {
         //alert("Escape");
         var modal = document.getElementById("myModal");
         modal.style.display = "none";
         clearForm();
      }
   };

   //Η συνάρτηση ajax call για να γίνει η κλήση στο endpoint
   function getCourses() {
      cleanTable();
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            addrows(response.data, ["id", "title", "description", "type", "semester", "ects", "professorname"]);
         }
      };
      xhttp.open("get", "/myframework/courses/getcourses?format=raw", true);
      xhttp.send();
   }

   //Μια απλή συναρτησούλα για να κάνει καταχώρηση των δεδομένων στον πίνακα. Τυπικά παίρνει παράμετρο ένα jason και το βάζει στον πίνακα
   function addrows(data, cols) {
      var tabLines = document.getElementById("table");
      cel = 0;

      for (j = 0; j < data.length; j++) {
         var tabLinesRow = tabLines.insertRow(j + 1);
         for (i = 0; i < cols.length; i++) {
            for (k = 0; k < Object.keys(data[j]).length; k++) {            

               if (Object.keys(data[j])[k] == cols[i]) {
                  var col1 = tabLinesRow.insertCell(cel);
                  cel++;
                  col1.innerHTML = Object.values(data[j])[k];
               }
            }
         }

      var col1 = tabLinesRow.insertCell(cel);
      var button = document.createElement('button');
            button.innerHTML = 'Διαγραφή';
            button.onclick = function(event){
               event.stopPropagation();
               //Για να πάρω τον κωδικό της τρέχουσας γραμμής
               var id = this.parentElement.parentElement.cells[0].innerHTML;
               remove(id);
            };

            col1.appendChild(button);

         tabLinesRow.addEventListener("click", function(event) {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
            getCourse(this.cells[0].innerHTML);
            actionType = "update";
         });
         cel = 0;
      }

   }


   //Για το drop down των καθηγητών
   //Η συνάρτηση ajax call για να γίνει η κλήση στο endpoint
   function getTeachers() {

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            response = response.data;
            lblproffessor = document.getElementById("proffessor");

            for (var i = 0; i < response.length; i++) {

               lblproffessor.options[lblproffessor.options.length] = new Option(response[i].name + ' ' + response[i].lastname, response[i].id);
            }
         }
      };
      xhttp.open("POST", "/myframework/users/getteachers?format=raw", true);
      xhttp.send(JSON.stringify());
   }



   //Για το drop down των καθηγητών
   //Η συνάρτηση ajax call για να γίνει η κλήση στο endpoint
   function getCourse(id) {

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            response = response.data[0];

            gid=id;
            document.getElementById("title").value = response.title;
            document.getElementById("type").value = response.typeid;
            document.getElementById("description").value = response.description;
            document.getElementById("semester").value = response.semester;
            document.getElementById("proffessor").value = response.professorid;
            document.getElementById("ects").value = response.ects;
         }
      };
      xhttp.open("POST", "/myframework/courses/getcourses?format=raw", true);
      xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      xhttp.send(JSON.stringify({
         "id": id
      }));
   }

   function save() {      
      if (actionType == "update") {         
         update();
         document.getElementById("myModal").style.display = "none";        
         getCourses(); 

      } else if (actionType == "insert") {
         insert();
         document.getElementById("myModal").style.display = "none";        
         getCourses();
      } else {

      }

   }

   function clearForm() {
      document.getElementById("title").value = "";
      document.getElementById("type").value = "";
      document.getElementById("description").value = "";
      document.getElementById("semester").value = "";
      document.getElementById("proffessor").value = "";
      document.getElementById("ects").value = "";
      gid="";
   }

   function update() {

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            console.log(response);
         }
      };
      xhttp.open("POST", "/myframework/courses/update?format=raw", true);
      xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      xhttp.send(JSON.stringify({
         "id": gid,
         "title": document.getElementById("title").value,
         "type": document.getElementById("type").value,
         "description": document.getElementById("description").value,
         "semester": document.getElementById("semester").value,
         "user_id": document.getElementById("proffessor").value,
         "ects": document.getElementById("ects").value
      }));
   }


   function insert() {

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            console.log(response);
         }
      };
      xhttp.open("POST", "/myframework/courses/insert?format=raw", true);
      xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      xhttp.send(JSON.stringify({         
         "title": document.getElementById("title").value,
         "type": document.getElementById("type").value,
         "description": document.getElementById("description").value,
         "semester": document.getElementById("semester").value,
         "user_id": document.getElementById("proffessor").value,
         "ects": document.getElementById("ects").value
      }));
   }

   function remove(id) {
      
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            //response = response.data[0];            
            getCourses();
         }
      };
      xhttp.open("POST", "/myframework/courses/delete?format=raw", true);
      xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      xhttp.send(JSON.stringify({
         "id": id
      }));
   }


   function cleanTable(){
      var table = document.getElementById("table");      
      for(var i = table.rows.length - 1; i > 0; i--)
      {
         table.deleteRow(i);
      }
   }
</script>

<!-- Trigger/Open The Modal -->
<button id="btnNewLesson">Νέο Μάθημα</button>