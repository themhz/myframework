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
      <span class="close">X</span>
      <div class="modal-frame">
         «Μαθήματα που έχουν δηλωθεί», <br>
         «Βασικά Μαθήματα με προβιβάσιμο βαθμό», <br>
         «Μαθήματα Επιλογής με προβιβάσιμο βαθμό», <br>
         <table id="courses" class="minitable"><tbody></tbody></table>
         «Διδακτικές Μονάδες», <lable id="ects"></lable><br>
         «Βασικά Μαθήματα για πτυχίο», <br>
         <table id="remainingenrolementsbasic" class="minitable"><tbody></tbody></table>
         «Μαθήματα Επιλογής για πτυχίο», <br>
         <table id="remainingenrolementschoice" class="minitable"><tbody></tbody></table>
         «Διδακτικές Μονάδες για πτυχίο» <lable id="maxects"></lable><br>
         
      </div>
   </div>

</div>

<script>
   //Το load της σελίδας για να φορτώσει το ajax
   var actionType;
   var gid;
   let th = new TableHandler();
   document.addEventListener('readystatechange', function(evt) {
      if (evt.target.readyState == "complete") {
         filltable();

         //Εδώ έχουμε τον κώδικα του popup 

         // Get the modal
         var modal = document.getElementById("myModal");


         // Get the <span> element that closes the modal
         var span = document.getElementsByClassName("close")[0];


         // When the user clicks on <span> (x), close the modal
         span.onclick = function() {

            modal.style.display = "none";
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

            }
         };

      }
   }, false);


   //Η συνάρτηση ajax call για να γίνει η κλήση στο endpoint
   function filltable() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            th.addtablerows(response.data, ["id", "name", "email", "regdate", "am","lessons","semester"], "table", "myModal", "selectitem(this.cells[0].innerHTML);");
         }
      };
      xhttp.open("get", "/myframework/studentprogress/select?format=raw", true);
      xhttp.send();
   }

   //Για το drop down των καθηγητών
   //Η συνάρτηση ajax call για να γίνει η κλήση στο endpoint
   function selectitem(id) {

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            //response = response.data[0];
            th.addtablerows(response.data, ["id", "title", "description", "grade", "type", "ects"], "courses", null, null);

            var table = document.getElementById("courses");
            //alert(table.rows.length-1);
            var sum=0; //Μεταβλιτή για να μετρήσουμε τις διδακτικές μονάδες
            for(var i=1;i<table.rows.length;i++){ //λουπα για να μετρήσουμε τις διδακτικές μονάδες
               //console.log(table.rows[i].cells[5].innerHTML)
               sum += parseInt(table.rows[i].cells[5].innerHTML);
            }
            document.getElementById("ects").innerHTML=sum;

            getRemainingenrolementsbasic(id);
         }
      };
      xhttp.open("POST", "/myframework/studentprogress/getentrolements?format=raw", true);
      xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      xhttp.send(JSON.stringify({
         "id": id
      }));
   }

   var totalectssum=0;
   function getRemainingenrolementsbasic(id){
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            //response = response.data[0];
            th.addtablerows(response.data, ["id", "title", "description", "type", "ects"], "remainingenrolementsbasic", null, null);

            let table = document.getElementById("remainingenrolementsbasic");            
                        
            for(var i=1;i<table.rows.length;i++){ //λουπα για να μετρήσουμε τις διδακτικές μονάδες     
               if(table.rows[i].cells[4]!= undefined && table.rows[i].cells[4]!=""){
                  totalectssum += parseInt(table.rows[i].cells[4].innerHTML);
                  //console.log(totalectssum);
               }                                             
            }            
            getRemainingenrolementschoice(id);
         }
      };
      xhttp.open("POST", "/myframework/studentprogress/remainingenrolements?format=raw", true);
      xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      xhttp.send(JSON.stringify({
         "id": id,
         "type": 1
      }));
   }

   function getRemainingenrolementschoice(id){
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            //response = response.data[0];
            th.addtablerows(response.data, ["id", "title", "description", "type", "ects"], "remainingenrolementschoice", null, null);

            var table = document.getElementById("remainingenrolementschoice");            
            for(var i=1;i<table.rows.length;i++){ //λουπα για να μετρήσουμε τις διδακτικές μονάδες
               if(table.rows[i].cells[4]!= undefined && table.rows[i].cells[4]!=""){
                  totalectssum += parseInt(table.rows[i].cells[4].innerHTML);
               }
            }
            document.getElementById("maxects").innerHTML=totalectssum;
            
         }
      };
      xhttp.open("POST", "/myframework/studentprogress/remainingenrolements?format=raw", true);
      xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      xhttp.send(JSON.stringify({
         "id": id,
         "type": 2
      }));
   }

   
</script>