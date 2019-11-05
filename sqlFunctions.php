<?php
    function dump(){
        require("db_connect.php");
        $sql = "SELECT * FROM Instructors";
        $result = mysqli_query($conn, $sql);
        echo "Teachers: <br>";
        $graders;
        $teachers;
        while($res = $result->fetch_assoc()){
            echo "Name: " . $res["name"] . " id: " . $res["login"] . " Our DB id: " . $res["id"] . "<br>"; 
            $teachers[$res["id"]] = $res["name"];
        }
        $sql = "SELECT * FROM Graders";
        $result = mysqli_query($conn, $sql);
        echo "Graders: <br>";
        while($res = $result->fetch_assoc()){
            echo "Name: " . $res["name"] . " id: " . $res["login"] . " Our DB id: " . $res["id"] . "<br>"; 
            $graders[$res["id"]] = $res["name"];
        }
        $sql = "SELECT * FROM Students";
        $result = mysqli_query($conn, $sql);
        echo "Students: <br>";
        $students;
        while($res = $result->fetch_assoc()){
            echo "ID: " . $res["login"] . "<br>"; 
            $students[$res["id"]] = $res["login"];
        }
        $sql = "SELECT * FROM to_section";
        $result = mysqli_query($conn, $sql);
        echo "Sections: <br>";
        while($res = $result->fetch_assoc()){
            $sid = $res["section_id"];
            $sql = "SELECT * FROM to_student WHERE section_id=$sid";
            $res2 = mysqli_query($conn, $sql);
            echo "#: " . $res["section_id"] . " Teacher: " . $teachers[$res["instructor_id"]] . " Grader: " . $graders[$res["grader_id"]] . "<br>"; 
            echo "Students: <br>";
            while($student = $res2->fetch_assoc()){
                echo $students[$student["student_id"]] . " ";
            }
            echo "<br><br>";
            mysqli_free_result($res2);
        }
        mysqli_free_result($result);
    }
    function addSections($filename){
        require("db_connect.php");
        $handle = fopen($filename, "r");
        if(!$handle){
            echo "<br>file does not exist.<br>";
            return false;
        }
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
                            //create the to section relationship & update incase it already existed with a  previous grader/teacher
                            $s = $section[0][0];
                            echo "s = " . $s . "<br>";
                            $sql = "INSERT INTO to_section (section_id, instructor_id, grader_id)
                                VALUES ('$s', '$tSQLID', '$gSQLID')";
                            if ($conn->query($sql) === TRUE) {
                                echo " Section New record created successfully <br>";
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                            }
                            $sql = "UPDATE to_section SET grader_id=$gSQLID WHERE section_id=$s";
                            mysqli_free_result($result);
                            if ($conn->query($sql) === TRUE) {
                                echo " Section New record created successfully <br>";
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
                            }
                            $sql = "UPDATE to_section SET instructor_id=$tSQLID WHERE section_id=$s";
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
                                //add the student to the student db
                                $sql = "INSERT INTO Students (login)
                                VALUES ('$student')";
                                if ($conn->query($sql) === TRUE) 
                                    echo "New record created successfully <br>";
                                else 
                                    echo "Student is already in the database.<br>";

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
        }
        fclose($handle); //close the file
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
        if(!($tID == -1)){
            //get the teacher ID from the database
            $sql = "SELECT id FROM Instructors WHERE login LIKE '%$tID%'";
            $result = mysqli_query($conn, $sql);
            $tNum = $result->fetch_assoc()['id'];
            if(!$tNum){
                echo 'Teacher does not exist <br>';
                return false;
            }
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
            if(!($tID == -1)){
                $sql = "SELECT section_id FROM to_section WHERE grader_id=$gNum AND instructor_id=$tNum";
            }else
                $sql = "SELECT section_id FROM to_section WHERE grader_id=$gNum";
            $result = mysqli_query($conn, $sql);
            $arr;
            $i = 0;
            //for each result add it to an array
            while($x = $result->fetch_assoc()){
                $arr[$i] = $x['section_id'];
                $i++;
            }
            return $arr;
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
        if($result){
            while(($student = $result->fetch_assoc())){
                $sid = $student['student_id'];
                $sql = "SELECT login FROM Students where id=$sid";
                $res2 = mysqli_query($conn, $sql);
                $arr[$i] = $res2->fetch_assoc()['login'];
                $i++;
            }
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
        echo "section is : " . $section . "<br>";
        $students = getStudents($section);
        if($students){
            $handle = fopen($toWrite, 'w') or die('Cannot open file:  '.$my_file);
            echo "Section found, creating text file with name " . $section . "<br>";
            $i = 0;
            foreach($students as $student){
                fwrite($handle, $student);
                $i++;
                    if(!($i == count($students))){
                        fwrite($handle, ', ');
                    }
            }
            fclose($handle);
        }else{
            echo "section not found";
        }
        
    }
    function removeSection($section){
        require("db_connect.php");
        //get grader from the section
        $sql = "SELECT grader_id FROM to_section WHERE section_id=$section";
        $result = mysqli_query($conn, $sql);
        if(($gid = $result->fetch_assoc()['grader_id'])){
            $sql = "SELECT login FROM Graders WHERE id=$gid";
            $result = mysqli_query($conn, $sql);
            removeGrader($result->fetch_assoc()["login"], $section);
        }
        //delete the section
        $sql = "DELETE FROM to_section WHERE section_id=$section";
        $result = mysqli_query($conn, $sql);
        //create the query to delete it from the student table
        $sql = "DELETE FROM to_student WHERE section_id=$section";
        //first get all the students from the section about to be deleted
        $sql2 = "SELECT student_id FROM to_student WHERE section_id=$section";
        $result2 = mysqli_query($conn, $sql2);
        $a = array();
        $i = 0;
        while(($a[$i] = $result2->fetch_assoc()['student_id'])){
            echo $a[$i] . "<br>";
            $i++;
        }
        //free the result2
        mysqli_free_result($result2);

        print_r($a);
        //delete the section
        $result = mysqli_query($conn, $sql);
        
        //now do some cleanup with the students -- if they have no more sections, remove from the database
        for($i = 0; $i < sizeof($a); $i++){
            $currID = $a[$i];
            echo "Curr id: " . $currID . "<br>";
            $sql = "SELECT * FROM to_student WHERE student_id=$currID";
            $result = mysqli_query($conn, $sql);
            echo "checking student <br>";
            if($result){
                if(!$result->fetch_assoc()){
                    echo "no result removing student from student database<br>";
                    $sql = "DELETE FROM Students WHERE id=$currID";
                    $result = mysqli_query($conn, $sql);
                }else{
                    echo "student has other classes<br>";
                    mysqli_free_result($result);
                }
            }else{
                echo "no student found || section does not exist.<br>";
            }
        }
    }
    function addGrader($name, $id, $section){
        require("db_connect.php");
        $sql = "SELECT * FROM to_section WHERE section_id=$section";
        $result = mysqli_query($conn, $sql);
        $pres;
        if(!($pres = $result->fetch_assoc())){
            echo "Section does not exist, graders cannot initialize sections<br>";
            return false;
        }
        $sql = "SELECT id FROM Graders WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql);
        if(!$result->fetch_assoc()){
            $sql = "INSERT INTO Graders (name, login)
            VALUES ('$name', '$id')";
            $result2 = mysqli_query($conn, $sql);
            $sql = "SELECT id FROM Graders WHERE login LIKE '%$id%'";
            $result = mysqli_query($conn, $sql);
        }
        $gID = $result->fetch_assoc()['id'];
        $sql = "UPDATE to_section SET grader_id=$gID WHERE section_id=$section";
        $result = mysqli_query($conn, $sql);
        echo "Grader updated... checking if previous graders<br>";
        if(($prev = $pres['grader_id'])){
            echo "There was a previous grader <br>";
            $sql = "SELECT section_id FROM to_section WHERE grader_id=$prev";
            $result = mysqli_query($conn, $sql);
            if($a = $result->fetch_assoc()){
                echo "Grader has more sections <br>";
            }else{
                echo "Grader has no other sections. Deleting grader <br>";
                $sql = "DELETE FROM Graders WHERE id=$prev";
                $result = mysqli_query($conn, $sql);
            }
        }
    }
    function addTeacher($name, $id, $section){
        require("db_connect.php");
        $sql = "SELECT * FROM to_section WHERE section_id=$section";
        $result = mysqli_query($conn, $sql);
        $sec = false;
        if(!$result->fetch_assoc()){
            echo "Section does not exist <br>";
            echo "Sections needs created...<br>";
            $sec = true;
        }
        $sql = "SELECT * FROM Instructors WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql);
        if(!$result->fetch_assoc()){
            echo "Instructor does not exist <br>";
            echo "Creating instructor...<br>";
            $sql = "INSERT INTO Instructors (name, login)
            VALUES ('$name','$id')";
            $result = mysqli_query($conn, $sql);
            echo "Instructor created...<br>";
        }else{
            echo "Instructor exists<br>";
        }
        $sql = "SELECT id FROM Instructors WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql);
        $tID = $result->fetch_assoc()['id'];
        if($sec){
            echo "Creating section...<br>";
            $sql = "INSERT INTO to_section (section_id, instructor_id)
            VALUES ('$section', '$tID')";
            $result = mysqli_query($conn, $sql);
            echo "Section created without grader!<br>";
        }
    }
    function removeGrader($id, $section){
        require("db_connect.php");
        $sql = "SELECT * FROM Graders WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            echo "Grader does not exist! <br>";
            return false;
        }
        $gID = $result->fetch_assoc()["id"];
        $sql = "SELECT * FROM to_section WHERE section_id=$section AND grader_id=$gID";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            echo "Grader is not part of this section or does not exist.<br>";
            return false;
        }
        if($a = $result->fetch_assoc()){
            echo $a['section_id'] . "<br>";
            $sql = "UPDATE to_section SET grader_id=null WHERE section_id=$section";
            $result = mysqli_query($conn, $sql);
            echo "Removed grader.<br>";
        }
        $sql = "SELECT * FROM to_section WHERE grader_id=$gID";
        $result = mysqli_query($conn, $sql);
        echo "Checking if grader has any other courses<br>";
        if(!($a = $result->fetch_assoc())){
            echo "Grader has no more courses, removing grader<br>";
            $sql = "DELETE FROM Graders WHERE id=$gID";
            $result = mysqli_query($conn, $sql);
        }else{
            echo "Grader has courses:<br>";
            echo $a['section_id'];
            while($a = $result->fetch_assoc()){
                echo " " . $a['section_id'];
            }
        }

    }
    function removeTeacher($id){
        require("db_connect.php");
        $sql = "SELECT * FROM Instructors WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            echo "Teacher does not exist<br>";
            return false;
        }
        $tID = mysqli_query($conn, $sql)->fetch_assoc()["id"];
        $sql = "SELECT * FROM to_section WHERE instructor_id=$tID";
        $result = mysqli_query($conn, $sql);
        if(!$result){
            echo "Teacher does not exist<br>";
            return false;
        }
        if($a = $result->fetch_assoc()){
            echo "Teacher has sections, remove them before deleting the teacher.<br>";
            echo "Sections numbers are : <br>";
            echo $a['section_id'] . "<br>";
            while($a = $result->fetch_assoc()){
                echo $a['section_id'] . "<br>";
            }
        }else{
            echo "Removing " . $id . " from our database <br>";
            $sql = "DELETE FROM Instructors WHERE id=$tID";
            $result = mysqli_query($conn, $sql);
        }
    }
    function addStudent($id, $section){
        require("db_connect.php");
        $id = ltrim(rtrim($id));
        $sql = "SELECT * FROM to_section WHERE section_id=$section";
        $result = mysqli_query($conn, $sql) or die($conn->error);
        if(!$result->fetch_assoc()){
            echo "Section does not exist <br>";
            return false;
        }
        $sql = "INSERT INTO Students (login)
        VALUES ('$id')";
        $result = mysqli_query($conn, $sql);
        $sql = "SELECT * FROM Students WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql) or die($conn->error);
        $num = $result->fetch_assoc()['id'];
        mysqli_free_result($result);
        echo $num . "<br>";
        $sql = "INSERT INTO to_student (student_id, section_id)
        VALUES ('$num', '$section')";
        $result = mysqli_query($conn, $sql) or die($conn->error);   
    }
    function removeStudent($id, $section){
        require("db_connect.php");
        echo $id . "<br>";
        echo $section . "<br>";
        $sql = "SELECT id FROM Students WHERE login LIKE '%$id%'";
        $result = mysqli_query($conn, $sql) or die($conn->error);
        $currST = $result->fetch_assoc()['id'];
            
       
        //delete the student from the section
        $sql = "DELETE FROM to_student WHERE section_id=$section AND student_id=$currST";
        echo "Current student " . $currST . "<br>";
        $result = mysqli_query($conn, $sql);
        //now do some cleanup with the students -- if they have no more sections, remove from the database
        $sql = "SELECT * FROM to_student WHERE student_id=$currST";
        $result = mysqli_query($conn, $sql);
        if($result->fetch_assoc()){
            echo "Student has other classes <br>";
        }else{
            echo "Student does not have other classes";
            //remove student from Students
            $sql = "DELETE FROM Students WHERE id=$currST";
            $result = mysqli_query($conn, $sql);
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
        addStudent("adx33", 44232);
    */
?>