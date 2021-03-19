<h1>Profile</h1>
<?php //print_r($_SESSION["user"][0]); ?>
<div class="page">
    <form id="profile">
        <table>
            <tr>
                <td>Όνομα</td>
                <td><label id="name"><?php echo $_SESSION["user"][0]->name; ?></label></td>
            </tr>
            <tr>
                <td>Επίθετο</td>
                <td><label id="lastname"><?php echo $_SESSION["user"][0]->lastname; ?></label></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><label id="email"><?php echo $_SESSION["user"][0]->email; ?></label></td>
            </tr>
            <tr>
                <td>Κωδικός πρόσβασης</td>
                <td><input type="password" value="<?php echo $_SESSION["user"][0]->password; ?>" id="password"><span id="showpass" onclick="showpass()">showpass</span></td>
            </tr>
            <tr>
                <td>Ρόλος</td>
                <td><label id="rolename"><?php echo $_SESSION["user"][0]->rolename; ?></label></td>
            </tr>
            <tr id="fieldsemester">
                <td>Εξάμηνο</td>
                <td><label id="semester"><?php echo $_SESSION["user"][0]->semester; ?></label></td>
            </tr>
            <tr>
                <td>Τηλέφωνο</td>
                <td><input type="text" value="<?php echo $_SESSION["user"][0]->mobilephone; ?>" id="mobilephone"></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><textarea id="address" cols="15" rows="5"><?php echo $_SESSION["user"][0]->address; ?></textarea></td>
            </tr>
            <tr>
                <td>Ημερομηνία/Γέννισης</td>
                <td><input type="date" value="<?php echo $_SESSION["user"][0]->birthdate; ?>" id="birthdate"></td>
            </tr>
            <tr>
                <td>Ημερομηνία Εγγραφής</td>
                <td><label id="regdate"><?php echo $_SESSION["user"][0]->regdate; ?></label></td>
            </tr>
            <tr>
                <td>Α/Μ</td>
                <td><label id="am"><?php echo $_SESSION["user"][0]->am; ?></label></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="button" value="save" onclick="save()"></td>
            </tr>
        </table>
    </form>    
</div>

<script>    
    document.addEventListener('readystatechange', function(evt) {
        if (evt.target.readyState == "complete") {

            var form = document.getElementById("profile");
            var fields = document.getElementsByTagName("input");

            let formHandler = new FormHandler();
            
            var fields = [{id: "password", type: "password", required : true, size:{len:8, test:"<="}}
                          ,{id: "mobilephone", type: "phone", required : false}
                          ,{id: "address", type: "textbox", required : false}
                          ,{id: "birthdate", type: "date", required : false}                              
                        ];
            formValidator =  new FrormValidator(fields);                     
        }
    });

    function save(){
        if(formValidator.validate().canSubmit){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = eval('(' + this.responseText + ')');
                    if(response.update == true){
                        alert("Η αλλαγή αποθηκεύτηκε")
                    }
                    //console.log(response.update);
                }
            };
            xhttp.open("POST", "/myframework/profile/update?format=raw", true);
            xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");


            //Ο παρακάτω κώδικας απλά συλλέγει τα δεδομένα από την φόρμα. Είναι μια αυτοματοποιημένη διαδικασία συλλογής δεδομένων από την φόρμα. 
            //Ο λόγος που επιλέχτηκε να είναι αυτοματοποιημένη είναι επειδή είχα την έμπνευση να χρησιμοποιήσω τον κώδικα αυτόν σε παραγωγικό περιβάλον.
            var data = {};
            data['id'] = <?php echo $_SESSION["user"][0]->id ; ?>;

            data['email'] = "<?php echo $_SESSION["user"][0]->email ; ?>";
            
            var formitems = document.getElementById("profile").getElementsByTagName("input");        
            
            //Παίρνει όλα τα elements που είναι image, text ή password. Προφανός για την εργασία μας το image δεν χρειάζεται αλλά το φτιάχνω γενικά
            for(var i=0; i<formitems.length;i++){
                if(formitems[i].getAttribute("type") == "text" || formitems[i].getAttribute("type") == "password"|| formitems[i].getAttribute("type") == "date"){ 
                    eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");                
                }
            }

            //Παίρνει όλα τα option select
            formitems = document.getElementById("profile").getElementsByTagName("select");
            for(var i=0; i<formitems.length;i++){
                    eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");                    
            }

            //Τέλος παίρνει τα textareas αν υπάρχουν
            formitems = document.getElementById("profile").getElementsByTagName("textarea");        
            for(var i=0; i<formitems.length;i++){
                    eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");                    
            }
            
            
            //console.log(data);
            //Τα παραπάνω δεδομένα τα μαζεύω σε ένα json αρχείο και τα στέλνω στον controller για να τα κάνει update.
            xhttp.send(JSON.stringify(data));

        }        
    }

    function showpass(){                
        if(document.getElementById("showpass").innerHTML == "showpass"){
            document.getElementById("password").type= "text";
            document.getElementById("showpass").innerHTML = "hidepass";
        }else{
            document.getElementById("password").type= "password";
            document.getElementById("showpass").innerHTML = "showpass";
        }
        
        
    }
</script>