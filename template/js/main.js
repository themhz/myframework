function showmenu(){
    document.getElementById("submenu").style.visibility="visible";
}

function hidemenu(){
    document.getElementById("submenu").style.visibility="hidden";
}


//Προαπετούμενα της κλάσης tablehandler
//1.Το id θα πρέπει να είναι τέρμα αριστερά στον πίνακα και θα πρέπει να έχει την ονομασία id
//2.Tα ονόματα των μοντέλων να είναι ίδια με τα ονόματα των πινάκων εκτός απο το πρώτο γράμμα της δήλωσης της κλάσης που πρέπει να είναι κεφαλαίο. 
//3.
class TableHandler{
    //Ο Constructor της κάσης tablehandler για να αρχικοποιήσει την βασικές μεταβλητές
   
    constructor(tableid, getallUrl, rows, getItemUrl=null, deleteUrl=null, updateUrl=null, insertUrl=null, deleteconfirmmsg=null, popupwindow=null, newbutton=null, closepopupbutton=null, clicksaveForPopup=null, onOpenPopup=null){
        
        this.tableid = tableid; //Το id του πίνακα που θα τοποθετηθούν τα δεδομένα
        this.getallUrl = getallUrl; //Το url που θα χρησιμοποιηθεί για να φορτοθούν τα δεδομένα από το endpoint της php
        this.rows = rows; //Τα δεδομένα
        this.getItemUrl = getItemUrl; //Όταν γίνει κλικ σε ένα δεδομένο πάνω στον πίνακα τότε καλείτε αλλη μια φορά ένα url για να φορτώσει τα δεδομένα για το συγκεκριμένο 
        //item. Στο μέλλον μπορεί να αντικατασταθεί αυτό με ένα μονομιάς φόρτομα. Δηλαδή να προφορτόνονται όλα τα πιθανά δεδομένα σε μια μνήμη cache. 
        this.deleteUrl = deleteUrl; //To url της διαγραφής
        this.updateUrl = updateUrl; //Το url της ενημέρωσης
        this.insertUrl = insertUrl; //To url της εισαγωγής
        this.deleteconfirmmsg = deleteconfirmmsg; //Το μήνυμα της προϊδοποίησης της διαγραφής
        this.editrows = {}; //Μια μνήμη που χρησιμοποιήτε για να παίρνει τα δεδομένα προς επεξεργασία στην φόρμα
        this.popupwindow = popupwindow;//το id του popup
        this.newbutton = newbutton;//το id του new κουμπιού        
        this.closepopupbutton = closepopupbutton;
        this.clicksaveForPopup = clicksaveForPopup;
        this.onOpenPopup = onOpenPopup;
        this.selectedrows = null;
        // Get the modal
        var modal = document.getElementById(this.popupwindow);

        if(this.newbutton != null){
            // Get the button that opens the modal
            this.btn = document.getElementById(this.newbutton);

            // When the user clicks the button, open the modal 
            this.btn.onclick = function() {
                tablehandler.clearForm();
                modal.style.display = "block";
                actionType = "insert";                
            }
        }
        

        if(this.closepopupbutton != null){
            // Get the <span> element that closes the modal        
            this.span = document.getElementById(this.closepopupbutton);
                    
            // When the user clicks on <span> (x), close the modal
            this.span.onclick = function() {
            tablehandler.clearForm();
                modal.style.display = "none";
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
                modal.style.display = "none";
                tablehandler.clearForm();
            }
        };

    }

    //Η συνάρτηση ajax call για να γίνει η κλήση στο endpoint και να φορτωθούν τα δεδομένα στον πίνακα
    loadtable(callbackfunc) {

        this.cleanTable(); //Καθαρίζει τον πίνακα
        var xhttp = new XMLHttpRequest(); // Δημιουργεί ένα object XMLHttpRequest 
        var self = this;    // Δηλώνει μια μεταβλητή για να κρατήσει το εξωτερικό this της κλάσης TableHandler ώστε να μην μπερδευτεί με το εσωτερικό της xhttp γραμμή 39,40
        xhttp.onreadystatechange = function() { //Ένα promise ώστε όταν επιστρέχει το αποτέλεσμα από τον server να εκτελεστεί η παρακάτω ανώμυμη συνάρτηση. 

            //Μονο αν το readystate είναι 4 και το status 200 παρακάτω παραθέτω τους κωδικούς
            // readyState	Holds the status of the XMLHttpRequest.
            // 0: request not initialized
            // 1: server connection established
            // 2: request received
            // 3: processing request
            // 4: request finished and response is ready
            // 200: "OK"
            // 403: "Forbidden"
            // 404: "Page not found"            
            //https://www.w3schools.com/xml/ajax_xmlhttprequest_response.asp

            if (this.readyState == 4 && this.status == 200) {                                
                var response = eval('(' + this.responseText + ')'); //Στάνταρ λειτουργία για να μετασχηματίσουμε το αποτέλεσμα που παίρνουμε από τον server σε JSON

                if(response.relations!= null){
                    self.pupulatedropdowns(response.relations); // Λειτουργία για να ενημερώσουμε τα dropdown του popup μια φορά
                }                
                self.addrows(response.data, self.rows);     // Εισαγωγή των γραμμών - αποτελεσμάτων στον πίνακα           
                if(callbackfunc!=null){
                    eval(callbackfunc).call();
                }
            }
        };
        
        xhttp.open("get", this.getallUrl, true); //Προετιμασία της αποστολής δηλώνοντας την μέθοδο το url και τρίτη παράμετρος ότι είναι ασύγχρονη αποστολή.
        xhttp.send(); //Τελική αποστολή των δεδομένων στο endpoint. μετά από εδώ ο κώδικας θα μεταφερθεί στην γραμμή 51 που είναι το promise. Δηλαδή μόλις ολοκληρωθεί

        return xhttp
    }
   
    //Μια απλή συναρτησούλα :P για να κάνει καταχώρηση των δεδομένων στον πίνακα. Τυπικά παίρνει παράμετρο ένα jason και το βάζει στον πίνακα
    //data και cols είναι json και τα 2
    addrows(data, cols) {
        
        var self = this;        
        var tabLines = document.getElementById(this.tableid); //Παίρνουμε τον πίνακα
        var cel = 0; //Μηδενίζουμε τον μετριτή κολόνας
        
        var tr = document.createElement('tr');  //Δημιουργούμε μια γραμμή πάνω στον πίνακα
        for(var c=0;c<cols.length;c++){ //για κάθε κολόνα που έχουμε δηλώσει να εμφανίσουμε φτιάχνουμε ένα header στον πίνακα
            var th = document.createElement('th'); //Δημιουργούμε το header
            th.innerHTML = cols[c]; //το βάζουμε στο header
            tr.appendChild(th); //και το κάνουμε append στο tr την γραμμή
        }

        if(this.deleteUrl!=null){
            var th = document.createElement('th');
                th.innerHTML = "Ενέργημα";
                tr.appendChild(th);
        }

        //document.getElementById("tbody").appendChild(tr);
        tabLines.tBodies[0].appendChild(tr);

        for (var j = 0; j < data.length; j++) { //Για κάθε εγγραφή μέσα στα δεδομένα που φέραμε σε μορφή json
            var tabLinesRow = tabLines.insertRow(j + 1); //τοποθετούμε μια γραμμή μέσα στον πίνακα
            for (var i = 0; i < cols.length; i++) { //Για κάθε μια από τις κολόνες μέσα στην λίστα μας
                for (var k = 0; k < Object.keys(data[j]).length; k++) { //Για κάθε κολόνα μέσα στην γραμμή            

                    if (Object.keys(data[j])[k] == cols[i]) { //Ελέγχουμε αν είναι ίδια η κολόνα της λίστας που θέλουμε με την κολόνα που φέραμε από την βάση αν ναι την τοποθετούμε
                        var col1 = tabLinesRow.insertCell(cel); //Ε η φάση της τοοποθέτησης
                        cel++; //Αυξάνουμε τον μετριτή κολόνας
                        col1.innerHTML = Object.values(data[j])[k]; //Τοποθετούμε το περιεχόμενο της κολόνας μέσα της
                    }
                }
            }

            if(this.deleteUrl!=null){
                var col1 = tabLinesRow.insertCell(cel); //Φτιάχνω ένα κελί για να βάλω το κουμπί
                var button = document.createElement('button'); //Βάζω το κουμπί
                button.innerHTML = 'Διαγραφή'; //Φτιάχνω και το όνομα του
                
                button.onclick = function(event){ //Δηλώνω το event του πατήματος
                    event.stopPropagation(); //Επειδή έχει και το tr even να ανοίγει το popup όταν πατηθεί και το κουμπί θα εκτελεστεί και το event του τr το onclick. Οπότε σταματάω την μετάδωση του click στο tr με την εντολή αυτή               
                    var id = this.parentElement.parentElement.cells[0].innerHTML; //παίρνω το κωδικό του user
                    //if (window.confirm("Είστε σίγουρος ότι θέλετε να διαγράψετε τον μαθητή?")) { // Τον προϊδοποιώ για την διαγραφή
                    if (window.confirm(self.deleteconfirmmsg)) { // Τον προϊδοποιώ για την διαγραφή                
                        self.remove(id); //Και διαγράφω τον user στέλνοντας request στον Server.
                    }
                };

                col1.appendChild(button);
            }            


            tabLinesRow.addEventListener("click", function(event) { //Για κάθε γραμμή στον πίνακα βάζω ένα event onclick ώστε όταν γίνεται click να ανοίγει ένα popup παράθυρο 
                var modal = document.getElementById(self.popupwindow); // Παίρνω το όνομα του popup
                modal.style.display = "block"; //Του αλλάζω στιλ για να το εμφανίσω
                                
                if(self.getItemUrl!=null){
                    self.getItem(this.cells[0].innerHTML); //Παίρνω τον κωδικό της γραμμής ή του στοιχείου που βρίσκεται στην πρώτη στήλη.:ΠΡΟΣΟΧΗ αν δεν είναισ την πρώτη θέση δεν θα παίξει
                    actionType = "update"; //Δηλώνω το acton type. Επειδή χρησιμοποιώ την ίδια φόρμα για το update και το insert για να ξέρω πότε θα γίνει το ένα και πότε το άλλο.
                }

                self.selectedrows = this.cells;
                if(self.onOpenPopup!=null){
                    self.onOpenPopup(self.selectedrows[0].innerHTML, self);
                }  
            });
                cel = 0; //Και μηδενίζουμε την κολόνα για την επόμενη γραμμή
        }

    }
   
    //Η μέθοδος που τρέχει όταν επιλέξουμε την γραμμή πάνω στον πίνακα. Προφανός παίρνει σαν παράμετρο το id που βρίσκεται τέρμα αριστερά στον πίνακα.
    getItem(id) {
        
        this.clearForm();
        var xhttp = new XMLHttpRequest();
        var self = this;
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = eval('(' + this.responseText + ')');                
                response = response.data[0]; //παίρνω τα δεδομένα του πεδίου
                //self.selectedItemResponse =
                self.editrows = response; //παίρνω τις σχέσεις που υπάρχουν. Δηλαδή τα τυχών constrains για να κάνω populate τον dropdown box της φόρμας σε όσα υπάρχει. 
                //Φυσικά η φόρμα πρέπει να τα περιλαμβάνει αυτά τα πεδία
                gid=id; //Σετάρω το id ως γενικό global id της φόρμας
                                
                //Ο παρακάτω αλγόριθμος είναι αυτό που γεμίζει τα dropdown. Η θεώρηση είναι όταν αν έχω dropdown, μάλλον θα έχω forein key σε κάποιο πίνακα. 
                //Για αυτό από πριν φρόντίζω από στο μηχανισμό του μοντέλου να φέρω τις αναφορές σε ένα ξεχωριστό αντικείμενο.                 
                for(var i=0; Object.keys(response).length>i;i++){

                   if(document.getElementById(Object.keys(response)[i])!=null){
                        document.getElementById(Object.keys(response)[i]).value = Object.values(response)[i];
                   }
                                      
                }                

                if(self.onOpenPopup!=null){
                    self.onOpenPopup(self.selectedrows[0].innerHTML, self);
                }  
                
            }
        };
        xhttp.open("POST", this.getItemUrl, true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhttp.send(JSON.stringify({// Τα κλασικά ποιο πάνω απλά εδω περνάω και το id ως παράμετρο
        "id": id
        }));
    }
    
    //Απλά καθαρίζει τον πίνακα πριν τον φορτώσει
    cleanTable() {

        var table = document.getElementById(this.tableid); //Παίρνει τoν πίνακα με βάση το id
        for (var i = table.rows.length - 1; i >= 0; i--) { //για κάθε μια γραμμή
            table.deleteRow(i);// την διαγράφει
        }
    } 

    //Διαγράφει το δεδομένο από τον πίνακα στην βάση και επαναφορτώνει τον πίνακα. 
    remove(id) { 
        var self = this;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
              var response = eval('(' + this.responseText + ')');
              if(response.delete == false){
                 alert("Σφάλμα κατά την διαγραφή. ΣΦΑΛΜΑ:"+response.error);
              }
                tablehandler.loadtable(); 
           }
        };
  
        xhttp.open("POST", self.deleteUrl, true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhttp.send(JSON.stringify({
           "id": id
        }));
    }

    //Εκτελέι το update των δεδομένων καλόντας φυσικά το αντίστοιχο method στην php
    update() {

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = eval('(' + this.responseText + ')');            
            }
        };
        xhttp.open("POST", this.updateUrl, true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");


        //Ο παρακάτω κώδικας απλά συλλέγει τα δεδομένα από την φόρμα. Είναι μια αυτοματοποιημένη διαδικασία συλλογής δεδομένων από την φόρμα. 
        //Ο λόγος που επιλέχτηκε να είναι αυτοματοποιημένη είναι επειδή είχα την έμπνευση να χρησιμοποιήσω τον κώδικα αυτόν σε παραγωγικό περιβάλον.
        var data = {};
        data['id'] = gid;
        var formitems = document.getElementById(this.popupwindow).getElementsByTagName("input");        
        
        //Παίρνει όλα τα elements που είναι image, text ή password. Προφανός για την εργασία μας το image δεν χρειάζεται αλλά το φτιάχνω γενικά
        for(var i=0; i<formitems.length;i++){
            if(formitems[i].tagName == "image" || formitems[i].getAttribute("type") == "text" || formitems[i].getAttribute("type") == "password"){ 
                eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");                
            }
        }

        //Παίρνει όλα τα option select
        formitems = document.getElementById(this.popupwindow).getElementsByTagName("select");
        for(var i=0; i<formitems.length;i++){
                eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");                    
        }

        //Τέλος παίρνει τα textareas αν υπάρχουν
        formitems = document.getElementById(this.popupwindow).getElementsByTagName("textarea");        
        for(var i=0; i<formitems.length;i++){
                eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");                    
        }        
        
        //Τα παραπάνω δεδομένα τα μαζεύω σε ένα json αρχείο και τα στέλνω στον controller για να τα κάνει update.
        xhttp.send(JSON.stringify(data));
    }

    //Ίδια λειτουργία με το update απλά δεν στένω το id στο endpoint γιατί είναι απλά ένα insert
    insert() {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = eval('(' + this.responseText + ')');
            console.log(response);
        }
    };
    xhttp.open("POST", this.insertUrl, true);
    xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

    var data = {};
    var formitems = document.getElementById(this.popupwindow).getElementsByTagName("input");      
    //get all inputs
    for(var i=0; i<formitems.length;i++){
        if(formitems[i].tagName == "image" || formitems[i].getAttribute("type") == "text" || formitems[i].getAttribute("type") == "password"){
            eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");
        }
    }

    //get all selects
    formitems = document.getElementById(this.popupwindow).getElementsByTagName("select");        
    for(var i=0; i<formitems.length;i++){            
            eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");                            
    }

    //get all textareas
    formitems = document.getElementById(this.popupwindow).getElementsByTagName("textarea");        
    for(var i=0; i<formitems.length;i++){            
            eval("data['"+formitems[i].id + "'] = '" + formitems[i].value + "'");                            
    }        

    xhttp.send(JSON.stringify(data));
    }

    //Αυτόματη λειτουργία για καθάρισμα των πεδίων της φόρμας
    clearForm() {
    gid="";                
    var formitems = document.getElementById(this.popupwindow).getElementsByTagName("input");      
    //get all inputs
    for(var i=0; i<formitems.length;i++){
        if(formitems[i].getAttribute("data-custom")!="true"){
            if(formitems[i].type!="button"){
                formitems[i].value = "";
            }
        }
    }

    //get all selects
    formitems = document.getElementById(this.popupwindow).getElementsByTagName("select");            
    
    for(var i=0; i<formitems.length;i++){
        if(formitems[i].getAttribute("data-custom")!="true"){
            formitems[i].value = "";        
        }
    }

    //get all textareas
    formitems = document.getElementById(this.popupwindow).getElementsByTagName("textarea");        
    for(var i=0; i<formitems.length;i++){            
        if(formitems[i].getAttribute("data-custom")!="true"){
            formitems[i].value = "";
        }
    }        
    }

    //Ένας μηχανίσμός για να ελέγχουμε πότε κάνουμε update και πότε insert κάτω από το ίδιο κουμπί
    save() {
        //{canSubmit : canSubmit, fields : errorFields};        
        if(this.clicksaveForPopup.call().canSubmit){
            if (actionType == "update") {         
                tablehandler.update();
                tablehandler.loadtable(); 
                document.getElementById(this.popupwindow).style.display = "none";         
       
             } else if (actionType == "insert") {
                tablehandler.insert();
                tablehandler.loadtable(); 
                document.getElementById(this.popupwindow).style.display = "none";         
             } else {
       
             }
        }                  
    }

    //Όταν εκτελείται για πρώτη φορά η loadtable θα φορτώσει τον πίνακα. Επειδή όμως χρησιμοποιώ και μια φόρμα για κάθε ένα click που γίνεται χρησιμοποιώ έναν μηχανισμό για να ξέρω
    //ποιο/ποια είναι το/τα forein key στον πίνακα ώστε να χειριστώ τα dropdown lists. Πχ οι users θα έχουν το dropdown του ρόλου. 
    //Γίνεται η χρήση της Object.keys και Object.values για να επιλέξω τα key και values του json που επιστρέφω. 
    pupulatedropdowns(relations){         
        for(var i=0;i<Object.keys(relations).length;i++){
            var lblitems = document.getElementById(Object.keys(relations)[i]);

            //Καθάρισε τα dropdown list
            while(lblitems.options.length) {
                lblitems.remove(0);
            }

            for(var j=0;j<Object.values(relations)[i][0].length;j++){                 
                lblitems.options[lblitems.options.length] = new Option(Object.values(relations)[i][0][j].name, Object.values(relations)[i][0][j].id);
            }
        }        
    }
}


//Κλάση για έλεγχο πεδίων
class FrormValidator{
    //φορτώνω τα πεδία σε ένα πίνακα από json αντικείμενα ώστε να τα ελέγξω μετά
    //το format είναι {id:value, type:value, required:value}
    //το id to χρησιμοποιώ για να πάρω το πεδίο. Ανάλογα με το type γίνεται και ο αντίστοιχος έλεγχος και επίσης με το required. Δηλαδή αν χρειάζεται η όχι.
    constructor(fields){ 
        this.fields = fields;
        //console.log(this.fields);
    }

    validate(){
        
        var errorFields = {};
        for(var i=0;i<this.fields.length;i++){

            switch(this.fields[i].type) {
                case "textbox":
                    if(this.fields[i].required && (document.getElementById(this.fields[i].id).value == "" || this.checkLength(this.fields[i]))){
                        document.getElementById(this.fields[i].id).style = "background-color:red";
                        errorFields[this.fields[i].id] = this.fields[i].id + " is required";
                    }else{
                        document.getElementById(this.fields[i].id).style = "background-color:none;";
                    }
                    break;
                case "password":                    
                    if(this.fields[i].required && (document.getElementById(this.fields[i].id).value == "" || this.checkLength(this.fields[i]))){
                        document.getElementById(this.fields[i].id).style = "background-color:red";
                        errorFields[this.fields[i].id] = this.fields[i].id + " is required";
                    }else{
                        document.getElementById(this.fields[i].id).style = "background-color:none;";
                        
                    }
                    break;
                case "textarea":
                    if(this.fields[i].required && (document.getElementById(this.fields[i].id).value == "" || this.checkLength(this.fields[i]))){
                        document.getElementById(this.fields[i].id).style = "background-color:red";
                        errorFields[this.fields[i].id] = this.fields[i].id + " is required";
                    }else{
                        document.getElementById(this.fields[i].id).style = "background-color:none;";
                    }
                    break;
                case "date":
                    if(this.fields[i].required && (document.getElementById(this.fields[i].id).value == "" || this.checkLength(this.fields[i]))){
                        document.getElementById(this.fields[i].id).style = "background-color:red";
                        errorFields[this.fields[i].id] = this.fields[i].id + " is required";
                    }else{
                        document.getElementById(this.fields[i].id).style = "background-color:none;";
                    }
                    break;
                case "datetime":
                    // code block
                    break;
                case "time":
                    // code block
                    break;
                case "image":
                    // code block
                    break;
                case "label":
                    // code block
                    break;
                case "checkbox":
                    // code block
                    break;
                case "multicheckbox":
                    // code block
                    break;
                case "select":
                    if(this.fields[i].required && (document.getElementById(this.fields[i].id).value == "" || this.checkLength(this.fields[i]))){
                        document.getElementById(this.fields[i].id).style = "background-color:red";
                        errorFields[this.fields[i].id] = this.fields[i].id + " is required";
                    }else{                        
                        document.getElementById(this.fields[i].id).style = "background-color:none;";
                    }
                    break;
                case "multiselect":                    
                    break;
                case "email":
                    if(this.fields[i].required && (!this.isemail(document.getElementById(this.fields[i].id).value) || this.checkLength(this.fields[i]))){
                        document.getElementById(this.fields[i].id).style = "background-color:red";
                        errorFields[this.fields[i].id] = this.fields[i].id + " is required";
                    }else{                        
                        document.getElementById(this.fields[i].id).style = "background-color:none;";
                    }

                    break;
                case "phone":
                    if (this.fields[i].required && !this.isphonenumber(document.getElementById(this.fields[i].id).value)) {
                        errorFields[this.fields[i].id] = { result: "error" };
                        document.getElementById(this.fields[i].id).style = "background-color:red";
                    } else if (!this.fields[i].required && !this.isempty(document.getElementById(this.fields[i].id).value) && !this.isphonenumber(document.getElementById(this.fields[i].id).value)) {
                        errorFields[this.fields[i].id] = { result: "error" };
                        document.getElementById(this.fields[i].id).style = "background-color:red";
                    }else {
                        document.getElementById(this.fields[i].id).style = "background-color:white";                        
                    }
                    break;         
                default:
                  // code block
              }
        }

        var canSubmit = true;
        //console.log(Object.keys(errorFields).length);
        if(Object.keys(errorFields).length>0){
            canSubmit = false;
        }
        return {canSubmit : canSubmit, fields : errorFields};
    }

    isemail(x) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (re.test(String(x).toLowerCase())) {
            return true;
        }

        return false;
    }

    checkLength(x){
        
        if(x.hasOwnProperty('size')){
            
            //alert(x.size.test)
            //alert(document.getElementById(x.id).value.length)
            var xsizetest = x.size.test;
            var xsizelen = parseInt(x.size.len);
            var valuelength = document.getElementById(x.id).value.length;

            //alert(xsizelen + xsizetest + valuelength)
            switch(xsizetest){
                case ">":                    
                    if(xsizelen>valuelength){
                        return false;
                    }
                    break;
                case "<":                    
                    if(xsizelen<valuelength){
                        return false;
                    }
                    break;
                case "=":                    
                    if(xsizelen==valuelength){                        
                        return false;
                    }
                    break;
                case ">=":
                if(xsizelen>=valuelength){
                    return false;
                }
                    break;
                case "<=":
                if(xsizelen<=valuelength){
                    return false;
                }
                    break;
                default:                    
                    return true;
            }

            return true;
        }else{
            return false;
        }
    }

    isphonenumber(x) {           
        
        var numbers = /^[0-9]+$/;
        if (x.match(numbers) && x.length=="10") {            
            return true;
        }

        return false;

    }

    isempty(x) {

        if (x.replace(/\s/g, "").length == 0) {
            return true;
        }

        return false;
    }
}


class FormHandler{
    constructor(fields){ 
        this.fields = fields;
    }
}