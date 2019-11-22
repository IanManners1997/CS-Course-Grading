<?php
    $conn = mysqli_connect('localhost', 'testUser', 'test', 'hoffmant_grading');
    //check connection
    if(!$conn){
        echo 'Connection Failed: ' . mysqli_connect_error();
    }
?>