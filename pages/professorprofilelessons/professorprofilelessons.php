<h1>Μαθήματα καθηγητή</h1>

<table id="professorlessons"><tbody></tbody></table>
<table id="enroledusers"><tbody></tbody></table>

<?php //print_r($_SESSION["user"]); ?>
<script>
document.addEventListener('readystatechange', function(evt) {
    if (evt.target.readyState == "complete") {
        getlessons();
    }
});

function getlessons(){
    var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = eval('(' + this.responseText + ')');
                
                var data = response.data;
                var results =[];
                for(i=0; i<data.length;i++){
                    var objItem = {'id':data[i].id, 'title': data[i].title + " " + data[i].description, 'ects': data[i].ects, 'semester':data[i].semester, 'action': "<input type='button' value='Προβολή μαθητών' onclick='showStudents("+data[i].id+")' />"};
                    results.push(objItem);
                }
                //var data = response.data;
                
                createTable("professorlessons", results);
            }
        };
        xhttp.open("POST", "/myframework/professorprofilelessons/getlessons?format=raw", true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");        
        xhttp.send(JSON.stringify({"id": <?php echo $_SESSION["user"][0]->id; ?> }));
}

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
     }


function showStudents(id){
    var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = eval('(' + this.responseText + ')');                
                var data = response.data;
                 var results =[];
                 for(i=0; i<data.length;i++){
                     var objItem = {'name': data[i].name, 'lastname':data[i].lastname, 'grade':data[i].grade+' <input type="text" value="" id="grade'+data[i].id+'" style="display:none" /><input id="updatebutton'+data[i].id+'" type="button" value="Ενημέρωση" onclick="update('+data[i].id+', '+data[i].users+')" style="display:none"/> <input id="editbutton'+data[i].id+'" type="button" value="επεξεργασία" onclick="editegrade('+data[i].id+')"/>'};
                     results.push(objItem);
                 }
                
                
                 createTable("enroledusers", results);
            }
        };
        xhttp.open("POST", "/myframework/professorprofilelessons/getenroledstudents?format=raw", true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");        
        xhttp.send(JSON.stringify({"id": id }));
}


function editegrade(enroled_id){    
    document.getElementById("grade"+enroled_id).style.display="";
    document.getElementById("updatebutton"+enroled_id).style.display="";
    document.getElementById("editbutton"+enroled_id).style.display="none";
}


function update(enroled_id, user_id){
    var grade = document.getElementById("grade"+enroled_id).value;
    
    if(parseInt(grade)>=0 && parseInt(grade) <=10){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = eval('(' + this.responseText + ')');                
                getlessons();
                showStudents(user_id);
            }
        };
        xhttp.open("POST", "/myframework/professorprofilelessons/updategrade?format=raw", false);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");        
        xhttp.send(JSON.stringify({"enroled_id": enroled_id, "grade": grade }));
    }
    
}
</script>

