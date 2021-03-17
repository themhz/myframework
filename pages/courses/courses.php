<h1>Μαθήματα</h1>
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
               <td>Όνομα μαθήματος</td>
               <td><input type="text" value="" id="title"></td>
            </tr>
            <tr>
               <td>Περιγραφή</td>
               <td><textarea id="description" cols="15" rows="5"></textarea></td>
            </tr>
            <tr>
               <td>Είδος</td>
               <td><select id="courses_type"></select></td>
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
               <td><select id="users"></select></td>
            </tr>
            <tr>
               <td>Ects</td>
               <td><input type="text" value="" id="ects"></td>
            </tr>
            <tr>
               <td></td>
               <td><input type="button" value="save" onclick="tablehandler.save()"></td>
            </tr>
         </table>
      </div>
   </div>

</div>

<!-- Trigger/Open The Modal -->
<button id="btnNewCourse">Νέος Χρήστης</button>


<script>
   //Το load της σελίδας για να φορτώσει το ajax
   var actionType;
   var gid;         
   var tablehandler;

   document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete"){                           
            var tableid = "table";
            var getAllUrl = "/myframework/courses/getcourses?format=raw"; //OK
            var rows = ["id", "title","type", "semester", "ects", "professorname"]; //OK
            var getItemUrl= "/myframework/courses/getcourses?format=raw"; //OK
            var deleteUrl = "/myframework/courses/delete?format=raw"; //OK
            var updateUrl = "/myframework/courses/update?format=raw"; //OK
            var insertUrl = "/myframework/courses/insert?format=raw"; //OK
            var deleteconfirmmsg = "Είστε σίγουρος ότι θέλετε να διαγράψετε το μάθημα?"; //OK
            var popupwindow = "myModal"; //OK
            var newbutton = "btnNewCourse"; //OK
            var closepopupbutton = "closepopup";//ΟΚ

            var clickrowForPopup = function(){
               var fields = [{id: "title", type: "textbox", required : true}
                              ,{id: "description", type: "textbox", required : true}
                              ,{id: "courses_type", type: "select", required : true}
                              ,{id: "semester", type: "select", required : true}
                              ,{id: "ects", type: "textbox", required : true}
                              ,{id: "users", type: "select", required : false}
                              ];
                              formValidator =  new FrormValidator(fields);

                     return formValidator.validate();
            };              
            tablehandler = new TableHandler(tableid, getAllUrl, rows, getItemUrl, deleteUrl, updateUrl, insertUrl, deleteconfirmmsg, popupwindow, newbutton, closepopupbutton, clickrowForPopup);
            tablehandler.loadtable();            

        }
    }, false);
     
</script>