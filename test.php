<?php
    require('db_connect.php');
    //3 steps to a query
    //construct query
    //write query for the name of all instructors
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
    
    //inputting into database
    //create sql
    /*$sql = "INSERT INTO instructors(name,data) VALUES('TESTINSERT', 'TESTDATA')";
   // save to db and check
   if(mysqli_query($conn, $sql)){
        echo 'instructor inserted!';
   }else{
       echo ' query error: ' . mysqli_error($conn);
   }
   $sql = "INSERT INTO graders(name,data) VALUES('TESTINSERT', 'TESTDATA')";
   if(mysqli_query($conn, $sql)){
        echo 'grader inserted';
    }else{
        echo ' query error: ' . mysqli_error($conn);
    }
   $sql = "INSERT INTO sections(data) VALUES('TESTDATA')";
   if(mysqli_query($conn, $sql)){
        echo 'section inserted';
    }else{
        echo ' query error: ' . mysqli_error($conn);
    }*/
    //create relationship
    $sql = "INSERT INTO `relations` (`id`, `section_id`, `grader_id`, `instructor_id`) VALUES ('1', '1', '2', '1')";
    if(mysqli_query($conn, $sql)){
        echo 'section inserted';
    }else{
        echo ' query error: ' . mysqli_error($conn);
    }
   //close connection
    mysqli_close($conn);
?>