
<?php
if(isset($_POST['stopbutton']))
{
    echo "Stopped";
    exit;
}
if(isset($_POST['startbutton']))
{
    insertdata();
    exit;
}

function insertdata(){

    echo "<script type=\"text/javascript\">alert(\"ruunnn\")</script>";
    //Insert code here
}
?>
<html>
    <head>
        <title>Repeated Insert</title>
    </head>
    <body>
        <input type="button" value="Start Insert" name="startbutton" onclick="start();" />
        <input type="button" value="Stop Insert" name="stopbutton" onclick="stopInsert();" />
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript">
            var intervals;
            $(document).ready(function(){

            });
            function start()
            {
                startInsert();

                intervals = setInterval(startInsert, 1000);
            }

            function startInsert() {
               // alert("runn");

                $.ajax({
                    type: "POST",
                    url: "chatupdater.php",
                    data: {startbutton : true},
                    success: function(){
                    },
                    dataType: "json"
                });
            }

            function stopInsert() {
                clearInterval(intervals);
                $.ajax({
                    type: "POST",
                    url: "chatupdater.php",
                    data: {stopbutton : true},
                    success: function(){
                    },
                    dataType: "json"
                });
            }
        </script>
    </body>
</html>