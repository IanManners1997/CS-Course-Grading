<?php
    $conn = mysqli_connect('localhost', 'test', 'test1234', 'testdb');

    //check connection
    if(!$conn){
        echo 'Connection Failed: ' . mysqli_connect_error();
    }
    //3 steps to a query
    //construct query

    //write query for all graders
    $sql = 'SELECT name FROM instructors'; //can also add "ORDER BY" 
    //make query
    $result = mysqli_query($conn, $sql);
    //fetch result
    $instructorNames = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //echo to screen
    echo 'Names are: ';
    print_r($instructorNames);
    //free result
    mysqli_free_result($result);

    //close connection
    mysqli_close($conn);
?>