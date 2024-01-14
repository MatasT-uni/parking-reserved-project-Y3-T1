<?php
    $mysqli = mysqli_connect("localhost","root","root","loginsystem");
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $p_id = $_GET['id'];
    $mysqli = mysqli_connect("localhost","root","root","loginsystem");
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $q = "DELETE FROM admins where id=$p_id";
    if(!$mysqli->query($q)){
        echo "DELETE failed. Error: ".$mysqli->error;
    }
    $mysqli->close();


header("location: admin.php");

?>