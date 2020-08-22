<?php
    require 'dbconfig/config.php';
    session_start();
   
   //$msg = $_POST[''];
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];

    $query = "SELECT message,sender,msgtime from chat WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)";

     $stmt = mysqli_prepare($con,$query);
     mysqli_stmt_bind_param($stmt,'ssss',$sender,$receiver,$receiver,$sender);
     mysqli_stmt_execute($stmt);
     $result = mysqli_stmt_get_result($stmt);

   // $result = mysqli_query($con,$query);

    $data = "";

    if(mysqli_num_rows($result)>0)
    {
        while ($row = mysqli_fetch_assoc($result))
        {   
            $data = $data . '<a>';
            $data = $data . $row['sender'];
            $data = $data . " =>  ".$row['message'];
            $data = $data . "  @ " . $row['msgtime'];
            $data = $data . '</a><br><br>';



        }


    }

    echo $data ;





?>