<!-- Ο τίτλος-->
<h1>Ανακοίνωση</h1>
<!--Η παράγραφος της ανακοίνωης-->
<p class="anakoinwsh">
    <!--Η Εικόνα με το alt-->
    <img src=http://via.placeholder.com/100x100 alt="anakoinwsh" />
    <!--Ο τίτλος της ανακοίνωσης-->
<h1><a href="anakoinwsh">Τίτλος ανακοίνωσης 1</a></h1>
<!--Το κείμενο-->
<p>
    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker
    <a href="#" onclick="goBack()">Πίσω</a>
</p>
<!--Η Διεύθυνση-->



<script>
    function goBack() {
        window.history.back();
    }


    function test(...args){
        console.log(args);
        ///let x = eval(args[2]);
        //call(x);
        //x();
        args[2]('geia sou');
        console.log(args[2]);
    }


    test("ena", 2, function(a, b){ alert(a)});


</script>