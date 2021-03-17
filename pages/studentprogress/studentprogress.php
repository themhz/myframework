<h1>Πρόοδος Φοιτητών</h1>
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
         Μαθήματα που έχουν δηλωθεί <br>
         <table id="EnrolledCourses" class="minitable"><tbody></tbody></table><br>
         
         Βασικά Μαθήματα με προβιβάσιμο βαθμό <br>
         <table id="BasicEnrolledCoursesWithPassGrade" class="minitable"><tbody></tbody></table><br>
         
         Μαθήματα Επιλογής με προβιβάσιμο βαθμό <br>
         <table id="OptionalEnrolledCoursesWithPassGrade" class="minitable"><tbody></tbody></table><br>
         
         Βασικά Μαθήματα για πτυχίο <br>
         <table id="BasicCoursesforDegree" class="minitable"><tbody></tbody></table><br>                  

         Μαθήματα Επιλογής για πτυχίο <br>
         <table id="OptionCoursesforDegree" class="minitable"><tbody></tbody></table><br>

         Διδακτικές Μονάδες για πτυχίο: <lable id="ectsforgraduation"></lable><br>
         Διδακτικές Μονάδες: <lable id="ectsgathered"></lable><br>         
      </div>
   </div>
</div>

<script>
   //Το load της σελίδας για να φορτώσει το ajax
   var actionType;
   var gid;         
   var tablehandler;

   document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete"){                           
            var tableid = "table";
            var getAllUrl = "/myframework/studentprogress/select?format=raw"; 
            var rows = ["id", "name", "email", "am", "lessons", "semester", "regdate"]; 
            var getItemUrl= null; 
            var deleteUrl = null;
            var updateUrl = null;
            var insertUrl = null;
            var deleteconfirmmsg = null;
            var popupwindow = "myModal";
            var newbutton = null;            
            var closepopupbutton = "closepopup";                                 
            var clicksaveForPopup = null;
            var onOpenPopup = function(id){
                              
                  var xhttp = new XMLHttpRequest(); // Δημιουργεί ένα object XMLHttpRequest                
                  xhttp.onreadystatechange = function() { //Ένα promise ώστε όταν επιστρέχει το αποτέλεσμα από τον server να εκτελεστεί η παρακάτω ανώμυμη συνάρτηση.                      

                     if (this.readyState == 4 && this.status == 200) {
                        var response = eval('(' + this.responseText + ')'); //Στάνταρ λειτουργία για να μετασχηματίσουμε το αποτέλεσμα που παίρνουμε από τον server σε JSON
                        for(i=0; i<response.length; i++){                           
                           
                           switch(Object.keys(response[i])[0]) {
                              case "EnrolledCourses":
                                 createTable("EnrolledCourses", Object.values(response[i])[0]);
                                 break;
                              case "BasicEnrolledCoursesWithPassGrade":
                                 createTable("BasicEnrolledCoursesWithPassGrade", Object.values(response[i])[0]);
                                 break;
                              case "OptionalEnrolledCoursesWithPassGrade":
                                 createTable("OptionalEnrolledCoursesWithPassGrade", Object.values(response[i])[0]);
                                 break;                              
                              case "BasicCoursesforDegree":
                                 createTable("BasicCoursesforDegree", Object.values(response[i])[0]);
                                 break;
                              case "OptionCoursesforDegree":
                                 createTable("OptionCoursesforDegree", Object.values(response[i])[0]);
                                 break;
                              case "Ectsforgraduation":                                 
                                 document.getElementById("ectsforgraduation").innerHTML = Object.values(response[i])[0][0].ects;                                 
                                 break;
                              case "EctsGathered":                                 
                                 document.getElementById("ectsgathered").innerHTML = Object.values(response[i])[0][0].ects;
                                 break;
                              default:
                               
                              }                           
                        }                        
                     }
                  };

                  xhttp.open("POST", "/myframework/studentprogress/getstatisticdata?format=raw", true); //Προετιμασία της αποστολής δηλώνοντας την μέθοδο το url και τρίτη παράμετρος ότι είναι ασύγχρονη αποστολή.
                  xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                  xhttp.send(JSON.stringify({"id": id}));
        
                  

            }
            tablehandler = new TableHandler(tableid, getAllUrl, rows, getItemUrl, deleteUrl, updateUrl, insertUrl, deleteconfirmmsg, popupwindow, newbutton, closepopupbutton, clicksaveForPopup, onOpenPopup);
            tablehandler.loadtable();

        }
    }, false);
     

     function createTable(id, values){        
         var table =  document.getElementById(id).getElementsByTagName('tbody')[0];        
         for (var i = table.rows.length - 1; i >= 0; i--) { //για κάθε μια γραμμή
            table.deleteRow(i);// την διαγράφει
         }
         
         var hc =0; //metriths gia to header
         for(var i=0; i<values.length ; i++){
            var head = table.insertRow();
            if(hc == 0){
               for( var j=0; j<Object.keys(values[i]).length ; j++){               
                  var cell = head.insertCell();
                  cell.innerHTML = Object.keys(values[i])[j];
                  hc++;
               }
            }            

            var row = table.insertRow();

            for( var j=0; j<Object.keys(values[i]).length ; j++){
               var cell = row.insertCell();
               cell.innerHTML = Object.values(values[i])[j];               
            }            
         }
     }
</script>