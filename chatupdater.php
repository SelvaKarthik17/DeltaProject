<?php
    require 'dbconfig/config.php';
    session_start();
   
   //$msg = $_POST[''];
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];

    $query = "SELECT message,sender,msgtime from chat WHERE (sender = '$sender' AND receiver = '$receiver') OR (sender = '$receiver' AND receiver = '$sender')";
    $result = mysqli_query($con,$query);

    $data = "";

    if(mysqli_num_rows($result)>0)
    {
        while ($row = mysqli_fetch_assoc($result))
        {   
            $data = $data . '<a>';
            $data = $data . $row['sender'];
            $data = $data . " =>  ".$row['message'];
            $data = $data . "  @ " . $row['msgtime'];
            $data = $data . '</a><br>';



        }


    }

    echo $data ;





?>