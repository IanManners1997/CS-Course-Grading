<?php
    function addSections($filename){
        require("db_connect.php");
        $handle = fopen("test.txt", "r");
        while(($line = fgets($handle)) !== false){
            if(strpos($line, "{")){
                for($i = 0; $i < 5; $i++){
                    switch($i % 5){
                        case 0:
                            preg_match_all('!\d+!', $line, $section);
                        break;
                        case 1:
                            $line = fgets($handle);
                            $teacher = explode(", ", $line);
                            $tName = ltrim(rtrim($teacher[0]));
                            $tID = ltrim(rtrim($teacher[1]));
                        break;
                        case 2:
                            $line = fgets($handle);
                            $grader = explode(", ", $line);
                            $gName = ltrim(rtrim($grader[0]));
                            $gID = ltrim(rtrim($grader[1]));
                        break;
                        case 3:
                            $line = fgets($handle);
                            $students = explode(", ", $line);
                        break;

                        case 4:
                            //echo section to the screen for testing
                            echo "Adding Section: " . $section[0][0] . "<br>" . " Teacher: " . $tName . " " . $tID . "<br>" . "Grader: " . $gName . " " . $gID . "<br>";
                            foreach ($students as $student){
                                echo ltrim(rtrim($student));
                                echo "<br>";
                            }
                            //add to database
                            //First check if section exists
                            echo "Checking if session exists" . "<br>";
                            $s = $section[0][0];
                            $sql = "SELECT section_id FROM Sections WHERE section_id=$s";
                            $result = mysqli_query($conn, $sql);     
                            $x = $result->fetch_assoc()['section_id'];
                            mysqli_free_result($result);
                            if($x){
                                echo "Section exists.";
                            }else{
                                echo "section does not exist.";
                                //add the section
                                $sql = "INSERT INTO Sections (section_id)
                                VALUES ('$s')";

                                if ($conn->query($sql) === TRUE) {
                                    echo "New record created successfully <br>";
                                } else {
                                    echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                                }
                            }
                            echo "<br>";

                            //Next check if instructor exists
                            $sql = "SELECT DISTINCT login FROM Instructors WHERE login LIKE '%$tID%'";
                            $result = mysqli_query($conn, $sql);
                            if($result->fetch_assoc()['login']){
                                echo 'teacher exists';
                                echo '<br>';
                            }else{
                                echo 'Teacher does not exist <br>';
                                //add the teacher
                                $sql = "INSERT INTO Instructors (name, login)
                                VALUES ('$tName', '$tID')";

                                if ($conn->query($sql) === TRUE) {
                                    echo "New record created successfully <br>";
                                } else {
                                    echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                                }
                            }
                            $sql = "SELECT id FROM Instructors WHERE login LIKE '%$tID%'";
                            $result = mysqli_query($conn, $sql);
                            $tSQLID = $result->fetch_assoc()['id'];
                            //Next check if grader exists
                            $sql = "SELECT DISTINCT login FROM Graders WHERE login LIKE '%$gID%'";
                            $result = mysqli_query($conn, $sql);
                            if($result->fetch_assoc()['login']){
                                echo 'grader exists';
                                echo '<br>';
                            }else{
                                echo 'Grader does not exist <br>';
                                //add the grader
                                $sql = "INSERT INTO Graders (name, login)
                                VALUES ('$gName', '$gID')";

                                if ($conn->query($sql) === TRUE) {
                                    echo "New record created successfully <br>";
                                } else {
                                    echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                                }
                            }
                            $sql = "SELECT DISTINCT id FROM Graders WHERE login LIKE '%$gID%'";
                            $result = mysqli_query($conn, $sql);
                            $gSQLID = $result->fetch_assoc()['id'];
                            //create the to section relationship
                            $sql = "INSERT INTO to_section (section_id, instructor_id, grader_id)
                                VALUES ('$s', '$tSQLID', '$gSQLID')";

                            if ($conn->query($sql) === TRUE) {
                                echo "New record created successfully <br>";
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                            }
                            //check if the students exit
                            foreach ($students as $student){
                                //make sure the student id has no white space
                                $student = ltrim(rtrim($student)); 
                                $sql = "SELECT DISTINCT login FROM Students WHERE login LIKE '%$student%'";
                                $result = mysqli_query($conn, $sql);
                                $st = $result->fetch_assoc()['login'];
                                if($st){
                                    echo 'student exists ' . $st;
                                    echo '<br>';
                                }else{
                                    echo $student . ' student does not exist <br>';
                                    //add the student
                                    $sql = "INSERT INTO Students (login)
                                    VALUES ('$student')";

                                    if ($conn->query($sql) === TRUE) {
                                        echo "New record created successfully <br>";
                                    } else {
                                        echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                                    }
                                }
                                //add the student to the section
                                $sql = "SELECT id FROM Students WHERE login LIKE '%$student%'";
                                $result = mysqli_query($conn, $sql);
                                $stID = $result->fetch_assoc()['id'];
                                //check if the student is already within the section
                                $sql = "SELECT prim_id FROM to_student WHERE student_id=$stID AND section_id=$s";
                                $result = mysqli_query($conn, $sql);
                                if(!$result->fetch_assoc()){
                                    $sql = "INSERT INTO to_student (student_id, section_id)
                                    VALUES ('$stID', '$s')";

                                    if ($conn->query($sql) === TRUE) {
                                        echo "New record created successfully <br>";
                                    } else {
                                        echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                                    }
                                }else{
                                    echo "This student already exists in the section. Student:" . $student . " Section: " . $s . "<br>";
                                }
                            }
                            
                        break;
                    }
                }
            }
        }
        fclose($handle);
    }
    function getSection($args){

    }
    function studentsToFile($section){

    }
    function addStudent($id, $section){
        
    }
?>