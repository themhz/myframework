  
  <!--Ο τίτλος με τις ανακοινώσεις-->  
  <h1>Αρχική</h1>
Καλώς όρισες <?php echo $_SESSION["user"][0]->name. " " . $_SESSION["user"][0]->lastname; ?> στο σύστημα διαχείρησης του ΜΠΣ

<br><br><br>
<?php 

print_r($_SESSION["user"]);
?>

