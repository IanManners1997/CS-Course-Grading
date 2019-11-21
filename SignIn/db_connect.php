<?php
    $conn = mysqli_connect("mysql.cs.pitt.edu", "hoffmant_grading","CfxQY6ycbSNCR5jU","hoffmant_grading");
    //check connection
    if(!$conn){
        echo 'Connection Failed: ' . mysqli_connect_error();
    }
?>