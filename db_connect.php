<?php
    $conn = mysqli_connect('localhost', 'test', 'test1234', 'testdb');

    //check connection
    if(!$conn){
        echo 'Connection Failed: ' . mysqli_connect_error();
    }

?>