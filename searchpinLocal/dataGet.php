<?php
    $con = mysqli_connect("localhost","root","cc1985");
    if (!$con)
    {
        die('Could not connect: ' . mysqli_connect_error());
    }
    mysqli_select_db($con, "searchpin");

    $sql ="select data from pininfo order by id desc limit 1"; 
    $resultGetLastID = mysqli_query($con, $sql);
    $info = mysqli_fetch_array($resultGetLastID, MYSQLI_ASSOC);
    echo json_encode($info);
?>