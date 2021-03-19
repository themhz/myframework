<h1>Μαθήματα</h1>

<table id="lessons"><tbody></tbody></table>
<table id="stats" class="table">
    <tr><td>Εξάμηνο:<?php echo $_SESSION["user"][0]->semester; ?></td><td>Μαθήματα που έχουν δηλωθεί:<span id="enroled"></span></td><td></td></tr>
    <tr><td>Βασικά μαθήματα με προβιβάσιμο βαθμό:<span id="BasicCoursesWithPassGrade"></span></td><td>Μαθήματα επιλογής με προβιβάσιμο βαθμό:<span id="OptionCoursesWithPassGrade"></span></td><td>Διδακτικές μονάδες:<span id="studentects"></span></td></tr>
    <tr><td>Βασικά μαθήματα για πτυχίο:<span id="BasicCoursesforDegree"></span></td><td>Μαθήματα Επιλογής για πτυχίο:<span id="OptionCoursesforDegree"></span></td><td>Διδακτικές Μονάδες για πτυχίο:<span id="ectsforgraduation"></span></td></tr>
</table>
<?php //print_r($_SESSION["user"]); ?>
<script>
document.addEventListener('readystatechange', function(evt) {
    if (evt.target.readyState == "complete") {
        var usersemester = <?php echo $_SESSION["user"][0]->semester; ?>;
        var enroled = 0;
        var studentects = 0;
        var BasicCoursesWithPassGrade = 0;
        var OptionCoursesWithPassGrade = 0;
        var BasicCoursesforDegree = 0;
        var OptionCoursesforDegree = 0;
        var ectsforgraduation = 0;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = eval('(' + this.responseText + ')');

                console.log(response.data);
                var data = response.data;
                var results =[];
                for(i=0; i<data.length;i++){
                    var objItem = {'semester':data[i].semester, 'title': data[i].title + " " + data[i].description, 'professorname':data[i].professorname, 'ects': data[i].ects, 'type':data[i].type, 'grade':data[i].grade, 'action':data[i].status, 'course_id':data[i].id};                   

                    if(data[i].status==1){
                        enroled++;
                    }
                    //if((parseInt(data[i].status)==1 || parseInt(data[i].status)==3)){
                    if(parseInt(data[i].grade) >=5){
                        studentects += parseInt(data[i].ects);
                    }
                    if((parseInt(data[i].grade) >=5 && parseInt(data[i].courses_type) == 1)){
                        BasicCoursesWithPassGrade++;
                    }

                    if((parseInt(data[i].grade) >=5 && parseInt(data[i].courses_type) ==2)){
                        OptionCoursesWithPassGrade++;
                    }

                    if((parseInt(data[i].grade) <5 || data[i].grade == null || data[i].grade == "") && parseInt(data[i].courses_type) == 1){
                        BasicCoursesforDegree++;
                    }

                    if((parseInt(data[i].grade) <5 || data[i].grade == null || data[i].grade == "") && parseInt(data[i].courses_type) == 2){
                        OptionCoursesforDegree++;
                    }                                                                
                    

                    results.push(objItem);
                }

                ectsforgraduation = 40 - studentects;
                document.getElementById("enroled").innerHTML = enroled;
                document.getElementById("studentects").innerHTML = studentects;
                document.getElementById("BasicCoursesWithPassGrade").innerHTML = BasicCoursesWithPassGrade;
                document.getElementById("OptionCoursesWithPassGrade").innerHTML = OptionCoursesWithPassGrade;
                document.getElementById("BasicCoursesforDegree").innerHTML = BasicCoursesforDegree;
                document.getElementById("OptionCoursesforDegree").innerHTML = OptionCoursesforDegree;
                document.getElementById("ectsforgraduation").innerHTML = ectsforgraduation;
                
                createTable("lessons", results);
            }
        };
        xhttp.open("POST", "/myframework/studentprofilelessons/getlessons?format=raw", true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        //xhttp.send();   
        xhttp.send(JSON.stringify({"id": <?php echo $_SESSION["user"][0]->id; ?> }));   
    }
});


function createTable(id, values){
         var table =  document.getElementById(id).getElementsByTagName('tbody')[0];        
         for (var i = table.rows.length - 1; i >= 0; i--) { //για κάθε μια γραμμή
            table.deleteRow(i);// την διαγράφει
         }
         
         var hc =0; //metriths gia to header
         for(var i=0; i<values.length ; i++){            

            if(hc == 0){
                var head = table.insertRow();
                for( var j=0; j<Object.keys(values[i]).length; j++){
                    var cell = head.insertCell();
                    cell.innerHTML = Object.keys(values[i])[j];
                    if(j==7){
                        cell.style.display="none";
                    }
                    hc++;
                }
                table.innerHTML = table.innerHTML.replaceAll("td", "th");
            }

            var row = table.insertRow();
            for( var j=0; j<Object.keys(values[i]).length; j++){
               var cell = row.insertCell();               
               cell.innerHTML = Object.values(values[i])[j];                      
               if(j==7){
                 cell.style.display="none";
               }
                              
            }
         }
         calculateSpans(id);
         parseTable(id);
     }

    function calculateSpans(id){
        var table =  document.getElementById(id).getElementsByTagName('tbody')[0];
        var rows = table.getElementsByTagName("tr");
        var rowspans = [];
        var prevrow = null;
        var rowcounter=0;
        var samerowcounter=1;
        for(i=rows.length-1; i>0; i--){
                        
            if( rows[i-1].cells[0].innerHTML != rows[i].cells[0].innerHTML){                
                rows[i].cells[0].setAttribute("class", "spaner");
                rows[i].cells[0].setAttribute("style", "grid-row: span "+samerowcounter);                
                samerowcounter = 1;                
            }else{                
                rows[i].cells[0].style.display = "none";
                samerowcounter++;
            }
                                    
        }
    }


    function parseTable(id){
        var table =  document.getElementById(id).getElementsByTagName('tbody')[0];
        var rows = table.getElementsByTagName("tr");
        var usersemester = <?php echo $_SESSION["user"][0]->semester; ?>;
        for(i=1; i<rows.length; i++){
            if(rows[i].cells[5].innerHTML == ""){
                rows[i].cells[5].innerHTML = "-";
            }

            if(parseInt(rows[i].cells[5].innerHTML)<5 && parseInt(rows[i].cells[6].innerHTML) == 1){
                rows[i].cells[6].innerHTML = "<input type='button' value='Κατάργηση Εγγραφής' onclick='abandonecorse("+rows[i].cells[7].innerHTML+")'>";
            }else if(parseInt(rows[i].cells[5].innerHTML)>5 && parseInt(rows[i].cells[6].innerHTML) == 1){                
                rows[i].cells[1].setAttribute("style", "background-color:green;color:white;" );
                rows[i].cells[2].setAttribute("style", "background-color:green;color:white;" );
                rows[i].cells[3].setAttribute("style", "background-color:green;color:white;" );
                rows[i].cells[4].setAttribute("style", "background-color:green;color:white;" );
                rows[i].cells[5].setAttribute("style", "background-color:green;color:white;" );
                rows[i].cells[6].setAttribute("style", "background-color:green;color:white;" );
                rows[i].cells[6].innerHTML = "";
            }else if(parseInt(rows[i].cells[0].innerHTML) <= usersemester && (parseInt(rows[i].cells[5].innerHTML) <5 || rows[i].cells[5].innerHTML=="-")){
                rows[i].cells[6].innerHTML = "<input type='button' value='Εγγραφή' onclick='enrollcorse("+rows[i].cells[7].innerHTML+")'>";
            }else if(parseInt(rows[i].cells[0].innerHTML) > usersemester){
                rows[i].cells[1].setAttribute("style", "background-color:grey;color:white;" );
                rows[i].cells[2].setAttribute("style", "background-color:grey;color:white;" );
                rows[i].cells[3].setAttribute("style", "background-color:grey;color:white;" );
                rows[i].cells[4].setAttribute("style", "background-color:grey;color:white;" );
                rows[i].cells[5].setAttribute("style", "background-color:grey;color:white;" );
                rows[i].cells[6].setAttribute("style", "background-color:grey;color:white;" );
                rows[i].cells[6].innerHTML = "";
            }  
            
            
        }
    }

    
    function enrollcorse(courses){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = eval('(' + this.responseText + ')');
                alert("Η εγγραφή σας στο μάθημα πραγματοποιήθηκε, Καλή επιτυχία!!");
                location.reload();
            }
        };
        xhttp.open("POST", "/myframework/studentprofilelessons/enrollcorse?format=raw", true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");        
        xhttp.send(JSON.stringify({"users": <?php echo $_SESSION["user"][0]->id; ?> , "courses" : courses}));   
    }

    function abandonecorse(courses){        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = eval('(' + this.responseText + ')');
                alert("Διαγραφτήκατε από το μάθημα. ");
                location.reload();
            }
        };
        xhttp.open("POST", "/myframework/studentprofilelessons/abandonecorse?format=raw", true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");        
        xhttp.send(JSON.stringify({"users": <?php echo $_SESSION["user"][0]->id; ?> , "courses" : courses}));   
    }

</script>

