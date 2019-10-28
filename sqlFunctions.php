<?php
    function addSections($filename){
        require("db_connect.php");
        $handle = fopen("test.txt", "r");
        while(($line = fgets($handle)) !== false){
            if(strpos($line, "{")){
                for($i = 0; $i < 5; $i++){
                    switch($i){
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
                        //START CASE 4 ADDING EVERYTHING TO DATABASE
                            echo "Adding Section: " . $section[0][0] . "<br>" . " Teacher: " . $tName . " " . $tID . "<br>" . "Grader: " . $gName . " " . $gID . "<br>";
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
                                    echo "Teacher record created successfully <br>";
                                } else {
                                    echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                                }
                            }
                            mysqli_free_result($result);
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
                                //echo 'Grader does not exist <br>';
                                //add the grader
                                $sql = "INSERT INTO Graders (name, login)
                                VALUES ('$gName', '$gID')";

                                if ($conn->query($sql) === TRUE) {
                                    echo "Grader record created successfully <br>";
                                } else {
                                    echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                                }
                            }
                            mysqli_free_result($result);
                            $sql = "SELECT DISTINCT id FROM Graders WHERE login LIKE '%$gID%'";
                            $result = mysqli_query($conn, $sql);
                            $gSQLID = $result->fetch_assoc()['id'];
                            //create the to section relationship
                            $s = $section[0][0];
                            echo "s = " . $s . "<br>";
                            $sql = "INSERT INTO to_section (section_id, instructor_id, grader_id)
                                VALUES ('$s', '$tSQLID', '$gSQLID')";
                            mysqli_free_result($result);
                            if ($conn->query($sql) === TRUE) {
                                echo " Section New record created successfully <br>";
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                            }
                            //check if the students exit
                            foreach ($students as $student){
                                //make sure the student id has no white space
                                $student = ltrim(rtrim($student)); 
                                    echo $student;
                                    echo "<br>";

                                $sql = "SELECT DISTINCT login FROM Students WHERE login LIKE '%$student%'";
                                $result = mysqli_query($conn, $sql);
                                $st = $result->fetch_assoc()['login'];
                                mysqli_free_result($result);
                                if($st){
                                    //echo 'student exists ' . $st;
                                    //echo '<br>';
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
                                mysqli_free_result($result);
                                //check if the student is already within the section
                                $sql = "SELECT prim_id FROM to_student WHERE student_id=$stID AND section_id=$s";
                                $result = mysqli_query($conn, $sql);
                                if(!$result->fetch_assoc()){
                                    $sql = "INSERT INTO to_student (student_id, section_id)
                                    VALUES ('$stID', '$s')";

                                    if ($conn->query($sql) === TRUE) {
                                        //echo "New record created successfully <br>";
                                    } else {
                                        echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                                    }
                                }else{
                                    echo "This student already exists in the section. Student:" . $student . " Section: " . $s . "<br>";
                                }
                                mysqli_free_result($result);
                            } 
                        //END CASE 4     
                        break;
                    }
                }
            }
            fclose($handle);
        }
    }  
    function getSections(){
        require('db_connect.php');
        $tID = NULL;
        $gID = NULL;
        $tNum = NULL;
        $gNum = NULL;
        $i = 0;
        foreach(func_get_args() as $arg){
            //only allow two arguments first being teacher id second be grader id
            switch($i){
                case 0: 
                    $tID = $arg;
                    break;
                case 1:
                    $gID = $arg;
                    break;
                default:
                    $b = true;
                    break;
            }
            $i++;
            if($i > 2)
                break;
        }
        //return if no arguments were passed in
        if($i == 0){
            echo "No arguments were passed in. Please pass in either teacher ID or teacher and grader ID <br>";
            return false;
        }
        //get the teacher ID from the database
        $sql = "SELECT id FROM Instructors WHERE login LIKE '%$tID%'";
        $result = mysqli_query($conn, $sql);
        $tNum = $result->fetch_assoc()['id'];
        if(!$tNum){
            echo 'Teacher does not exist <br>';
            return false;
        }
        //if a grader id was passed in make sure it exists
        if($gID){
            $sql = "SELECT id FROM Graders WHERE login LIKE '%$gID%'";
            $result = mysqli_query($conn, $sql);
            $gNum = $result->fetch_assoc()['id'];
            if(!$gNum){
                echo 'Grader does not exist <br>';
                return false;
            }
            //have teacher and grader ID from database
            //get the section number
            $sql = "SELECT section_id FROM to_section WHERE grader_id=$gNum AND instructor_id=$tNum";
            $result = mysqli_query($conn, $sql);
            if($result){
                return array($result->fetch_assoc()['section_id']);
            }else{
                echo 'No section found.';
                return false;
            }
            echo "<br>";
        }else{
            //only teacher id was passed in
            $sql = "SELECT section_id FROM to_section WHERE instructor_id=$tNum";
            $result = mysqli_query($conn, $sql);
            $arr;
            $i = 0;
            //for each result add it to an array
            while($x = $result->fetch_assoc()){
                $arr[$i] = $x['section_id'];
                $i++;
            }
            //if any results are found return them
            if($arr){
                return $arr;
            }else{
                echo 'No section found.';
                return false;
            }
            echo "<br>";
        }
        mysqli_free_result($result);
    }
    function getStudents($section){
        require('db_connect.php');
        $sql = "SELECT student_id FROM to_student WHERE section_id=$section";
        $result = mysqli_query($conn, $sql);
        $arr;
        $i = 0;
        while($student = $result->fetch_assoc()){
            $sid = $student['student_id'];
            $sql = "SELECT login FROM Students where id=$sid";
            $res2 = mysqli_query($conn, $sql);
            $arr[$i] = $res2->fetch_assoc()['login'];
            $i++;
        }
        return $arr;
    }
    function truncateAll(){
        require("db_connect.php");
        $sql = "SET FOREIGN_KEY_CHECKS=0";
        mysqli_query($conn, $sql);
        
        $sql = "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema='hoffmant_grading'";
        $result = mysqli_query($conn, $sql);

        while($tbl = $result->fetch_assoc()){
            $tbl = $tbl['TABLE_NAME'];
            //print_r($tbl);
            $sql = "TRUNCATE TABLE $tbl";
            mysqli_query($conn, $sql);
        }
        $sql = "SET FOREIGN_KEY_CHECKS=1";
        mysqli_query($conn, $sql);
    }
    function studentsToFile($section){
        $i = 0;
        $toWrite = $section.'.txt';
        $handle = fopen($toWrite, 'w') or die('Cannot open file:  '.$my_file);
        echo "section is : " . $section . "<br>";
        $students = getStudents($section);
        $i = 0;
        foreach($students as $student){
            fwrite($handle, $student);
            $i++;
                if(!($i == count($students))){
                    fwrite($handle, ', ');
                }
        }
        fclose($handle);
    }
    function addStudent($id, $section){
        require("db_connect.php");
        //trim the id of whitespace
        $id = ltrim(rtrim($id)); 
        //check and see if the student exists
        $sql = "SELECT DISTINCT login FROM Students WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql);
        $st = $result->fetch_assoc()['login'];
        mysqli_free_result($result);
        if($st){
            //echo 'student exists ' . $st;
            //echo '<br>';
        }else{
            //add the student
            $sql = "INSERT INTO Students (login)
            VALUES ('$id')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully <br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
            }
        }
        //add the student to the section
        $sql = "SELECT id FROM Students WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql);
        $stID = $result->fetch_assoc()['id'];
        mysqli_free_result($result);
        //check if the student is already within the section
        $sql = "SELECT prim_id FROM to_student WHERE student_id=$stID AND section_id=$section";
        $result = mysqli_query($conn, $sql);
        if(!$result->fetch_assoc()){
            $sql = "INSERT INTO to_student (student_id, section_id)
            VALUES ('$stID', '$section')";

            if ($conn->query($sql) === TRUE) {
                //echo "New record created successfully <br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
            }
        }else{
            echo "This student already exists in the section. Student:" . $id . " Section: " . $section . "<br>";
        }
        mysqli_free_result($result);
    }
    //EXAMPLE USES OF EACH FUNCTION
    /*
        addSections("test.txt");
        $s = getSections("hoffmant");
        foreach($s as $z)
            studentsToFile($z);
        truncateAll();
    */
        function addGrader($id, $section){
            //Connect Database
            require("db_connect.php");
            //Trim White Space 
            $id = ltrim(rtrim($id));
            //See if Grader ID exists in Database
            $sql = "SELECT DISTINCT login FROM Graders WHERE login LIKE '%$id%'";
            $result = mysqli_query($conn,$sql);
            $gr = $result ->fetch_assoc()['login'];
            mysqli_free_result($result);
            if($gr){
                echo 'grader exists ' . $gr;
                echo '<br>';
            }else{
                //Add the Grader to the database 
                $sql = "INSERT INTO Graders (login) VALUES ('id')";

            if($conn -> query($sql)==TRUE){
                echo "New record created successfully <br>";
            }else{
                echo "Error: " . $sql . "<br>" . $conn->error . "<br>" ;
            }
           } 
           //Add the grader to the correct section
           $sql = "SELECT id FROM Graders WHERE login LIKE '%$id%'";
           $result = mysqli_query($conn, $sql);
           $grID = $result->fetch_assoc()['id']);
            mysqli_free_result($result);
            //Check if grader is assigned to the current section or not 
            $sql = "SELECT gr_id FROM to_grader WHERE grader_id=$grID AND section_id = $Section";
            $result = mysqli_query($conn, $sql);
            if(!$result->fetch_assoc()){
            $sql = "INSERT INTO to_grader (grader_id, section_id) VALUES ('$grID', '$section')";
                if($conn->query($sql) === TRUE){
                     echo "New record created successfully <br>";
                }else{
                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                }
            }else{
                echo "This grader already exists in the section. Grader:" . $id . " Section: " . $section . "<br>";
            }

        }

        function getGrader($section){
            require('db_connect.php');
            $sql = "SELECT grader_id FROM to_grader WHERE section_id=$section";
            $result = mysqli_query($conn, $sql);
            if($grader = $result->fetch_assoc()){
                $gid = $grader['grader_id'];
                $sql = "SELECT login FROM Graders where id = $gid";
                $result2 = mysqli_query($conn, $sql);
                $ret = result2->fetch_assoc()['login'];
            }
            return $ret; 

        }

        function addInstructor($id, $section){
            //Connect Database
            require("db_connect.php");
            //Trim White Space 
            $id = ltrim(rtrim($id));
        }

        function getInstructor($section){
            require('db_connect.php');
            $sql = "SELECT instructor_id FROM to_instructor WHERE section_id = $section";
            $result = mysqli_query($conn, $sql);
            if($instructor = $result->fetch_assoc()){
                $Iid = $instructor['instructor_id'];
                $sql = "SELECT login FROM Instructors where id = $Iid";
                $result2 = mysqli_query($sconn, $sql);
                $ret = result2->fetch_assoc()['login'];
            }
            return $ret
        }


        

?>