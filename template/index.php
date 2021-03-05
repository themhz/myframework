<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="template/css/style.css">
    <!--Δήλωση του javascript για την λειτουργία του Menu-->
    <script src="template/js/main.js"></script>
    <meta charset="utf-8">
    <title>WebSite</title>
</head>

<body>
    <!-- <input type="button" value="ajax test" onclick="ajaxtest()"/>
    <textarea id="demo" style="width:700px;height:120px"></textarea> -->
    <!--Επειδή χρησιμοποιώ το css-grid ως τεχνική, δηλώνω ένα container για να τα βάλω όλα μέσα  -->
    <div class="grid-container">
        <!--Εδώ είναι η κεφαλίδα της σελίδας όπου θα τοποθετηθεί η εικονα ο τίτλος η είσοδος-->
        <div class="head">
            <div class="header-container">
                <div class="headl">
                    <!--Εκόνα με Link στν αρική-->
                    <a href="home"><img src="template/images/eap.png" alt="eap" /></a>
                </div>
                <!--Οι κεφαλιδες στην κεντρική-->
                <div class="headm">
                    <h1>ΗΛΕΚΤΡΟΝΙΚΗ ΓΡΑΜΜΑΤΕΙΑ</h1>
                    <h2>ΜΕΤΑΠΤΥΧΙΑΚΟΥ ΠΡΟΓΡΑΜΜΑΤΟΣ ΣΠΟΥΔΩΝ ΠΛΗΡΟΦΟΡΙΚΗΣ</h2>
                    <h2>ΤΜΗΜΑ ΕΦΑΡΜΟΓΩΝ ΤΗΛΕΜΑΤΙΚΗΣ</h2>
                    <h2>ΠΑΝΕΠΙΣΤΗΜΙΟ ΠΑΤΡΩΝ</h2>
                </div>
                <!--το Λινκ της εισόδου-->
                <div class="headr">
                    <a id="login" href="login">Είσοδος</a>
                </div>
            </div>
        </div>
        <!--Εδώ έχουμε τον placeholder για το menu-->
        <div class="nav">
            &nbsp;
        </div>
        <nav id="nav2" class="nav2">
            <ul>
                <li id="listusers"><a href="users">Διαχείριση Χρηστών</a></li>
                <li id="listcourses"><a href="courses">Διαχείριση Μαθημάτων</a></li>
                <li id="liststudentprogress"><a href="studentprogress">Πρόοδος Φοιτητών</a></li>
            </ul>
        </nav>
        <!--Εδώ έχουμε το κυρίως σώμα της σελίδας με τις ανακοινώσεις αριστερά και δεξιά
            επίσης κάθε ανακοίνωση ανα μια αλλάζει η κλάση της ώστε να πετύχουμε την διαφοροποίηση αριστερά δεξιά-->
        <div class="body">

            <?php
            include $pageloader->load();
            ?>

        </div>
        <!--Εδώ έχουμε το footer. To χώρισα σε 2 κομμάτια, το αριστερό και το δεξιό.-->
        <!-- εδώ έχουμε την λίστα με το επικοινωνήστε το τηλέφωνο και το email-->
        <div class="footerl">
            <ul>
                <li class="lititle">Επικοινωνήστε μαζί μας</li>
                <li class="phone">Τηλέφωνο:2107798659</li>
                <li class="mail">E-mail:themhz@gmail.com</li>
            </ul>
        </div>
        <!--και εδώ οι όροι χρήσης -->
        <div class="footerr">
            <ul>
                <li>
                    <a href="https://www.eap.gr/wp-content/uploads/2020/10/oroi-xr.pdf" target="_blank">Όροι χρήσης |</a>
                </li>
                <li>
                    <a href="https://www.eap.gr/data-protection-team/" target="_blank">Πολιτική απορρήτου</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('readystatechange', function(evt) {
            if (evt.target.readyState == "complete") {
                <?php if(isset($_GET["page"]) && $_GET["page"]!="login") {?>
                    document.getElementById("list<?php echo $_GET["page"] ?>").className = "selected";
                <?php }?>
            }
        }, false);
    </script>
</body>

</html>