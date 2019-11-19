<?php
    require("db_connect.php");
    /*
        Create Tables creates every table we use in case the database gets deleted
        *note* need to add relationships to the creation
    */
    function createTables(){
        //in case the tables ever get deleted run this function
        echo "Creating tables <br>";
		$sql = "CREATE TABLE Graders (
			id	INT PRIMARY KEY AUTO_INCREMENT,
			fname VARCHAR(30) NOT NULL,
			lname VARCHAR(30) NOT NULL,
			login VARCHAR(30) NOT NULL,
            CONSTRAINT login_u UNIQUE (login));";
        if (!($GLOBALS['conn']->query($sql) === TRUE)) {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
        $sql = "CREATE TABLE Instructors (
			id	int PRIMARY KEY AUTO_INCREMENT,
			fname varchar(30) NOT NULL,
			lname varchar(30) NOT NULL,
			login varchar(30) NOT NULL,
            CONSTRAINT login_u UNIQUE (login));";
        if (!($GLOBALS['conn']->query($sql) === TRUE)) {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
        $sql = "CREATE TABLE Students (
			id int PRIMARY KEY AUTO_INCREMENT,
			login varchar(30) NOT NULL,
            CONSTRAINT login_u UNIQUE (login));";
        if (!($GLOBALS['conn']->query($sql) === TRUE)) {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
        $sql = "CREATE TABLE to_section (
			prim_id	int PRIMARY KEY AUTO_INCREMENT,
			section_id int NOT NULL,
            instructor_id int NOT NULL,
            grader_id int NULL,
            CONSTRAINT section_u UNIQUE (section_id));
            ";
        if (!($GLOBALS['conn']->query($sql) === TRUE)) {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
        $sql = "CREATE TABLE to_student (
			prim_id	int PRIMARY KEY AUTO_INCREMENT,
			student_id int NOT NULL,
            section_id int NOT NULL);
            ";
        if (!($GLOBALS['conn']->query($sql) === TRUE)) {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
    }
    /*
        Dump the entire teacher table
    */
    function dumpTeachers(){
        $sql = "SELECT * FROM Instructors";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        echo "Teachers: <br>";
        while($res = $result->fetch_assoc()){
            echo "Name: " . $res["fname"]  . " " .  $res["lname"] . " id: " . $res["login"] . " Our DB id: " . $res["id"] . "<br>"; 
        }
    }
    /*
        Dump the entire grader table
    */
    function dumpGraders(){
        $sql = "SELECT * FROM Graders";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        echo "Graders: <br>";
        while($res = $result->fetch_assoc()){
            echo "Name: " . $res["name"]  . " " .  $res["lname"] . " id: " . $res["login"] . " Our DB id: " . $res["id"] . "<br>"; 
        }
    }
    /*
        Dump the entire student table
    */
    function dumpStudents(){
        $sql = "SELECT * FROM Students";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        echo "Students: <br>";
        while($res = $result->fetch_assoc()){
            echo "ID: " . $res["login"] . "<br>"; 
        }
    }
    /*
        Dump the entire section table
        with section info
    */
    function dumpSections(){
        $sql = "SELECT * FROM to_section";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        echo "Sections: <br>";
        while($res = $result->fetch_assoc()){
            $sid = $res["section_id"];
            $sql = "SELECT * FROM to_student WHERE section_id=$sid";
            $res2 = mysqli_query($GLOBALS['conn'], $sql);
            $tid = $res['instructor_id'];
            $gid = $res['grader_id'];
            $sql = "SELECT * FROM Instructors WHERE id=$tid";
            $teacher = mysqli_query($GLOBALS['conn'], $sql);
            $sql = "SELECT * FROM Graders WHERE id=$gid";
            $grader = mysqli_query($GLOBALS['conn'], $sql);
            if($grader)
                $graderN = $grader->fetch_assoc()["login"];
            else
                $graderN = "No Grader";
            echo "#: " . $res["section_id"] . " Teacher: " . $teacher->fetch_assoc()["login"] . " Grader: " . $graderN . "<br>"; 
            echo "Students: <br>";
            while($student = $res2->fetch_assoc()){
                $sid = $student["student_id"];
                $sql = "SELECT * FROM Students WHERE id=$sid";
                $student = mysqli_query($GLOBALS['conn'], $sql);
                echo $student->fetch_assoc()["login"] . " ";
            }
            echo "<br><br>";
            mysqli_free_result($res2);
            mysqli_free_result($teacher);
            if($grader)
                mysqli_free_result($grader);
        }
    }
    /*
        Dump all database info
    */
    function dump(){
        
        dumpTeachers();
        dumpGraders();
        dumpStudents();
        dumpSections();
        
    }
    /*
        Check if a string is json
    */
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    /*
        Parse a file and add the contents to the database
        Accepts both JSON and my format
        To find JSON object format or my format, consult the readme.txt
    */
    function separateFile($filename){
        $handle = fopen($filename, "r");
        if(!$handle){
            echo "<br>file does not exist.<br>";
            return false;
        }
        
        while(($line = fgets($handle)) !== false){
            if(isJson($line)){
                //we have json so parse it
                echo "We have some JSON!<br>";
                $arr = json_decode($line, true);
                for($i = 0; $i < sizeof($arr); $i++){
                    //separate the json
                    $teacherJS = $arr[$i]["teacher"];
                    $graderJS = $arr[$i]["grader"];
                    $sectionJS = $arr[$i]["number"];
                    $studentsJS = $arr[$i]["students"];

                    echo "Teacher <br>";
                    print_r($teacherJS);

                    //Set the teacher info
                    $tfName = $teacherJS["firstname"];
                    $tlName = $teacherJS["lastname"];
                    $tlogin = explode("@", $teacherJS["email"])[0];
                    $teacher[0] = $tfName . " " . $tlName;
                    echo $teacher[0];
                    $teacher[1] = $tlogin;

                    //set the grader info
                    echo "Grader <br>";
                    print_r($graderJS);
                    $gfName = $graderJS["firstname"];
                    $glName = $graderJS["lastname"];
                    $glogin = explode("@", $graderJS["email"])[0];
                    $grader[0] = $gfName . " " . $glName;
                    $grader[1] = $glogin;

                    //set section info
                    echo "Section <br>";
                    print_r($sectionJS);
                    echo "Student <br>";
                    print_r($studentsJS);

                    //create each section
                    createSection($teacher, $grader, $studentsJS, $sectionJS);
                }
            }
            else if(strpos($line, "{")){
                echo "Not json!";
                for($i = 0; $i < 5; $i++){
                    switch($i){
                        case 0:
                            preg_match_all('!\d+!', $line, $section);
                        break;
                        case 1:
                            $line = fgets($handle);
                            $teacher = explode(", ", $line);
                            
                        break;
                        case 2:
                            $line = fgets($handle);
                            $grader = explode(", ", $line);

                        break;
                        case 3:
                            $line = fgets($handle);
                            $students = explode(", ", $line);
                        break;

                        case 4:
                            print_r($teacher);
                            echo "<br>";
                            print_r($grader);
                            echo "<br>";
                            print_r($students);
                            echo "<br>";
                            print_r($section);
                            echo "<br>";
                            createSection($teacher, $grader, $students, $section[0][0]);
                        break;
                    }
                }
            }
        }   
    fclose($handle); //close the file
    }
    /*
        Initializes a section with a teacher, grader <- can be null, students, and a section number
    */  
    function createSection($teacher, $grader, $students, $section){
        $teacher = createTeacher($teacher);
        $grader = createGrader($grader);
        $sIDs = array();
        echo "Printing students : <br>";
        print_r($students);
        echo "<br>";
        foreach ($students as $student){
            if($curstudent = createStudent(ltrim(rtrim($student))))
            array_push($sIDs, $curstudent);
        }
        addSection($teacher, $grader, $section);
        fillSection($section, $sIDs);
    }
    /*
        Returns teacher object by username or dies if teacher does not exist
    */
    function getTeacherByUsername($tID){
        $sql = "SELECT id FROM Instructors WHERE login LIKE '%$tID%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $teacher = $result->fetch_assoc() or die;
        mysqli_free_result($result);
        return $teacher;
    }
    /*
        Returns teacher object by database ID, 
        this can be very useful in conjunction with other functions
        such as getting a section, which carries the teacher id
        then getting the teacher from that section
        or dies if teacher does not exist
    */
    function getTeacherByID($id){
        $sql = "SELECT * FROM Instructors WHERE id=$id";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $teacher = $result->fetch_assoc() or die;
        echo $teacher;
        mysqli_free_result($result);
        return $teacher;
    }
    /*
        Returns the teacher of a section number
    */
    function getTeacherBySection($section){
        $sql = "SELECT * FROM to_section WHERE section_id=$section";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $section = $result->fetch_assoc() or die;
        $tid = $section["instructor_id"];
        return getTeacherById($tid);
    }
    /*
        returns the grader of a section
    */
    function getGraderBySection($section){
        $sql = "SELECT * FROM to_section WHERE section_id=$section";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        echo $section . " is the section <br>";
        $section = $result->fetch_assoc() or die;
        $gid = $section["grader_id"];
        return getGraderById($gid);
    }
    /*
        Returns an array of teachers for whom a Grader grades
    */
    function getTeachersByGraderUsername($id){
        $grader = getGraderByUsername($id);
        $sections = getSections(-1, $id);
        $arr = [];
        for($i = 0; $i < sizeof($sections); $i++){
            array_push($arr, getTeacherBySection($sections[$i]));
        }
        print_r($arr);
        return $arr;
    }
    /*
        Returns an array of Graders whom grade for a Teacher
    */
    function getGradersByTeacherUsername($id){
        $teacher = getTeacherByUsername($id);
        $sections = getSections($id);
        $arr = [];
        print_r($sections);
        for($i = 0; $i < sizeof($sections); $i++){
            array_push($arr, getGraderBySection($sections[$i]));
        }

        print_r($arr);
        return $arr;
    }
    /*
        Returns a grader by DATABASE id
    */
    function getGraderByID($id){
        $sql = "SELECT * FROM Graders WHERE id=$id";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $grader = $result->fetch_assoc() or die;
        echo "getting grader";
        echo $grader;
        mysqli_free_result($result);
        return $grader;
    }
    /*
        returns grader by login/username
    */
    function getGraderByUsername($gID){
        $sql = "SELECT DISTINCT id FROM Graders WHERE login LIKE '%$gID%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $grader = $result->fetch_assoc();
        mysqli_free_result($result);
        return $grader;
    }
    /*
        Create a teacher from a teacher object
        teacher[0] -> firstname lastname
        teacher[1] -> login without @pitt.edu
    */
    function createTeacher($teacher){
        $tName = ltrim(rtrim($teacher[0]));
        $tName = explode(" ", $tName);
        $tID = ltrim(rtrim($teacher[1]));
        //add the teacher
        //let sql deal with duplicates
        $sql = "INSERT INTO Instructors (fname, lname,login)
        VALUES ('$tName[0]', '$tName[1]','$tID')";

        if ($GLOBALS['conn']->query($sql) === TRUE) {
            echo "Teacher record created successfully <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
        $sql = "SELECT id FROM Instructors WHERE login LIKE '%$tID%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $teacher = $result->fetch_assoc();
        mysqli_free_result($result);
        return $teacher;
    }
    /*
        Create a grader from a grader object
        grader[0] -> firstname lastname
        grader[1] -> login without @pitt.edu
    */
    function createGrader($grader){
        //make sure no whitespace
        //$grader = explode(" ", $grader);
        $gName = ltrim(rtrim($grader[0]));
        $gName = explode(" ", $gName);
        //$lName = ltrim(rtrim($grader[1]));
        $gID = ltrim(rtrim($grader[1]));
        //echo 'Grader does not exist <br>';
        //let sql deal with duplicates
        //add the grader
        $sql = "INSERT INTO Graders (fname, lname, login)
        VALUES ('$gName[0]', '$gName[1]', '$gID')";

        if ($GLOBALS['conn']->query($sql) === TRUE) {
            echo "Grader record created successfully <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
        
        $sql = "SELECT DISTINCT id FROM Graders WHERE login LIKE '%$gID%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $grader = $result->fetch_assoc();
        mysqli_free_result($result);
        return $grader;
    }
    /*
        Create a student, student is just an ID
        Student IDs are checked to be between 3 and 6 characters
    */
    function createStudent($student){
        //make sure the student id has no white space
        $student = ltrim(rtrim($student)); 
        if(strlen($student) < 3 || strlen($student) > 6){
            echo "Error adding students, student id should be between 3 and 6 characters" . "<br>";
            return null;
        }
        echo $student;
        echo "<br>";
        //add the student to the student db
        $sql = "INSERT INTO Students (login)
        VALUES ('$student')";
        if ($GLOBALS['conn']->query($sql) === TRUE) 
            echo "New record created successfully <br>";
        else {
            echo "Student is already in the database.<br>";
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
        $sql = "SELECT id FROM Students WHERE login LIKE '%$student%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $student = $result->fetch_assoc()['id'];
        mysqli_free_result($result);
        return $student;
    }
    /*
        Fill a section with an array of student IDs
    */
    function fillSection($section, $sIDs){
        print_r($sIDs);
        foreach ($sIDs as $sID){
            //add the student to the section
            //check if the student is already within the section
            $sql = "SELECT prim_id FROM to_student WHERE student_id=$sID AND section_id=$section";
            $result = mysqli_query($GLOBALS['conn'], $sql);
            echo "SID: ";
            echo $sID;
            echo " section : ";
            echo $section;
            echo "<br>";
            if(!$result->fetch_assoc()){
                $sql = "INSERT INTO to_student (student_id, section_id)
                VALUES ('$sID', '$section')";
                if ($GLOBALS['conn']->query($sql) === TRUE) {
                    //echo "New record created successfully <br>";
                } else {
                    echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
                }
            }else{
                echo "This student already exists in the section. SID:" . $sID . " Section: " . $section . " or section does not exist" . "<br>";
            }
            mysqli_free_result($result);
        }
    }
    /*
        Update the teacher via teacher object for a certain section
    */
    function updateTeacherSection($teacher, $section){
        $tSQLID = $teacher['id'];
        $sql = "UPDATE to_section SET instructor_id=$tSQLID WHERE section_id=$section";
        mysqli_free_result($result);
        if ($GLOBALS['conn']->query($sql) === TRUE) {
            echo " Section New record created successfully <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        } 
    }
    /*
        Update the graderr via grader object for a certain section
    */
    function updateGraderSection($grader, $section){
        $gSQLID = $grader['id'];
        $sql = "UPDATE to_section SET grader_id=$gSQLID WHERE section_id=$section";
        mysqli_free_result($result);
        if ($GLOBALS['conn']->query($sql) === TRUE) {
            echo " Section New record created successfully <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }
    }
    /*
        create a section by grader and teacher
        if you use this function be sure to fill the section with students
    */
    function addSection($teacher, $grader, $section){
        require("db_connect.php");
        //create the to section relationship & update incase it already existed with a  previous grader/teacher
        #$s = $section[0][0];
        print_r($section);
        $tSQLID = $teacher['id'];
        if($grader)
            $grader = $grader['id'];
            #    $sql = "INSERT INTO sections (section_id)
            #VALUES ('$section')";
        #if ($GLOBALS['conn']->query($sql) === TRUE) {
        #    echo " Section New record created successfully <br>";
        #} else {
        #    echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        #}    
        #echo "s = " . $section . "<br>";
        $sql = "INSERT INTO to_section (section_id, instructor_id, grader_id)
            VALUES ('$section', '$tSQLID', '$grader')";
        if ($GLOBALS['conn']->query($sql) === TRUE) {
            echo " Section New record created successfully <br>";
        } else {

            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
            echo "Updating section" . "<br>";
            $sql = "UPDATE to_section SET instructor_id='$tSQLID', grader_id='$grader' WHERE section_id='$section'";
            if ($GLOBALS['conn']->query($sql) === TRUE) {
                echo "Successfully updated <br>";
            }else
                echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "<br>";
        }     
    }  
    /*
        Get sections of a teacher or grader
        for teacher just pass in teacher username
        for teacher and grader pass in teacher username then grader username
        for only grader pass in -1 then grader username
    */
    function getSections(){
        require('db_connect.php');
        $tID = NULL;
        $gID = NULL;
        $tNum = NULL;
        $gNum = NULL;
        $i = 0;
        $b = false;
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
        if($b){
            echo "Too many arguments, Please pass in Teacher id or teacher and grader id <br>";
            return false;
        }
        $teacher;
        $grader;
        $tNum;
        if(!($tID == -1)){
            $teacher = getTeacherByUsername($tID);
            $tNum = $teacher['id'];
        }
        //if a grader id was passed in make sure it exists
        if($gID){
            $grader = getGraderByUsername($gID);
            $gNum = $grader['id'];
            //have teacher and grader ID from database
            //get the section number
            if(!($tID == -1)){
                $sql = "SELECT section_id FROM to_section WHERE grader_id=$gNum AND instructor_id=$tNum";
            }else
                $sql = "SELECT section_id FROM to_section WHERE grader_id=$gNum";
            $result = mysqli_query($GLOBALS['conn'], $sql);
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
            $result = mysqli_query($GLOBALS['conn'], $sql);
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
    /*
        returns an array of students in a given section
    */
    function getStudents($section){
        $sql = "SELECT student_id FROM to_student WHERE section_id=$section";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $arr;
        $i = 0;
        if($result){
            while(($student = $result->fetch_assoc())){
                $sid = $student['student_id'];
                $sql = "SELECT login FROM Students where id=$sid";
                $res2 = mysqli_query($GLOBALS['conn'], $sql);
                $arr[$i] = $res2->fetch_assoc()['login'];
                $i++;
            }
        }
        return $arr;
    }
    /*
        Truncates the entire database
    */
    function truncateAll(){
        $sql = "SET FOREIGN_KEY_CHECKS=0";
        mysqli_query($GLOBALS['conn'], $sql);
        
        $sql = "SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema='hoffmant_grading'";
        $result = mysqli_query($GLOBALS['conn'], $sql);

        while($tbl = $result->fetch_assoc()){
            $tbl = $tbl['TABLE_NAME'];
            //print_r($tbl);
            $sql = "TRUNCATE TABLE $tbl";
            mysqli_query($GLOBALS['conn'], $sql);
        }
        $sql = "SET FOREIGN_KEY_CHECKS=1";
        mysqli_query($GLOBALS['conn'], $sql);
    }
    /*
        Takes in a section # and writes all the students within that section to a file named after the section number
    */
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
    /*
        Removes a section from the database
        cleans up any students that have no classes left
    */
    function removeSection($section){
        //get grader from the section
        $sql = "SELECT grader_id FROM to_section WHERE section_id=$section";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(($gid = $result->fetch_assoc()['grader_id'])){
            $sql = "SELECT login FROM Graders WHERE id=$gid";
            $result = mysqli_query($GLOBALS['conn'], $sql);
            removeGrader($result->fetch_assoc()["login"], $section);
        }
        //delete the section
        $sql = "DELETE FROM to_section WHERE section_id=$section";
        mysqli_query($GLOBALS['conn'], $sql);
        #$sql = "DELETE FROM sections WHERE section_id=$section";
        #mysqli_query($GLOBALS['conn'], $sql);
        //create the query to delete it from the student table
        $sql = "DELETE FROM to_student WHERE section_id=$section";
        //first get all the students from the section about to be deleted
        $sql2 = "SELECT student_id FROM to_student WHERE section_id=$section";
        $result2 = mysqli_query($GLOBALS['conn'], $sql2);
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
        $result = mysqli_query($GLOBALS['conn'], $sql);
        
        //now do some cleanup with the students -- if they have no more sections, remove from the database
        for($i = 0; $i < sizeof($a); $i++){
            $currID = $a[$i];
            echo "Curr id: " . $currID . "<br>";
            $sql = "SELECT * FROM to_student WHERE student_id=$currID";
            $result = mysqli_query($GLOBALS['conn'], $sql);
            echo "checking student for other classes <br>";
            if($result){
                if(!$result->fetch_assoc()){
                    echo "no result removing student from student database<br>";
                    $sql = "DELETE FROM Students WHERE id=$currID";
                    if(mysqli_query($GLOBALS['conn'], $sql) === TRUE)
                        echo "Deleted student.<br>";
                }else{
                    echo "student has other classes<br>";
                }
                mysqli_free_result($result);
            }else{
                echo "no student found || section does not exist.<br>";
            }
        }
    }
    /*
        Adds or updates a grader to the database via name, username, and section number
        Graders cannot initialize sections, teachers must
        aka you cannot have a section without a teacher
        you can have a section without a grader

    */
    function addGrader($name, $id, $section){
        echo "Name is: " . $name;
        $sql = "SELECT * FROM to_section WHERE section_id=$section";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!($prev = $result->fetch_assoc())){
            echo "Section does not exist, graders cannot initialize sections<br>";
            return false;
        }
        $prev = $prev['grader_id'];
        mysqli_free_result($result);
        $grader = createGrader([$name, $id], $GLOBALS['conn']);
        $gID = $grader['id'];
        $sql = "UPDATE to_section SET grader_id=$gID WHERE section_id=$section";
        if(mysqli_query($GLOBALS['conn'], $sql) === TRUE)
            echo "Grader updated... checking if there was a previous grader<br>";
        else
            echo "Error updating grader<br>";
        //doing some cleanup on graders
        if($prev){
            echo "There was a previous grader <br>";
            $sql = "SELECT section_id FROM to_section WHERE grader_id=$prev";
            $result = mysqli_query($GLOBALS['conn'], $sql);
            if($a = $result->fetch_assoc()){
                echo "Grader has more sections <br>";
            }else{
                echo "*Note* Previous grader has no more sections! May want to delete.<br>";
                $sql = "SELECT login FROM Graders WHERE id=$prev";
                $result = mysqli_query($GLOBALS['conn'], $sql);
                echo "Previous grader : " . $result->fetch_assoc()["login"] . "<br>";
               /* echo "Grader has no other sections<br> Deleting grader <br>";
                $sql = "DELETE FROM Graders WHERE id=$prev";
                
                if(mysqli_query($GLOBALS['conn'], $sql) === TRUE){
                    echo "Grader successfully deleted<br>";
                }else
                    echo "Unable to delete grader<br>";*/
            }
            mysqli_free_result($result);
        }else
            echo "There was no previous grader <br>";
    }
    /*
        Adds a teacher to a section, updates the section and creates the section if needed
    */
    function addTeacher($name, $id, $section){
        $sql = "SELECT * FROM to_section WHERE section_id=$section";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        $sec = false;
        if(!$result->fetch_assoc()){
            echo "Section does not exist <br>";
            echo "Sections needs created...<br>";
            $sec = true;
        }
        //create teacher will get the teacher and create it if need be
        $teacher = createTeacher([$name, $id], $GLOBALS['conn']); 
        print_r($teacher);
        $tID = $teacher['id'];
        if($sec){
            echo "Creating section...<br>";
            createSection($teacher, NULL, $section);
        }else{
            echo "Updating section with teacher...<br>";
            $sql = "UPDATE to_section SET instructor_id=$tID WHERE section_id=$section";
            $result = mysqli_query($GLOBALS['conn'], $sql);
            echo "Section updated.<br>";
        }
    }
    /*
        Removes a grader from a section
    */
    function removeGraderS($id, $section){
        $sql = "SELECT * FROM Graders WHERE login LIKE '%$id%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!$result){
            echo "Grader does not exist! <br>";
            return false;
        }
        $gID = $result->fetch_assoc()["id"];
        $sql = "SELECT * FROM to_section WHERE section_id=$section AND grader_id=$gID";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!$result){
            echo "Grader is not part of this section.<br>";
            return false;
        }
        if($a = $result->fetch_assoc()){
            echo $a['section_id'] . "<br>";
            $sql = "UPDATE to_section SET grader_id=null WHERE section_id=$section";
            $result = mysqli_query($GLOBALS['conn'], $sql);
            echo "Removed grader " . $id . " from section " . $section . "<br>";
        }
        $sql = "SELECT * FROM to_section WHERE grader_id=$gID";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        echo "Checking if grader has any other courses<br>";
        if(!($a = $result->fetch_assoc())){
            echo "Grader has no more courses, removing grader from db<br>";
            $sql = "DELETE FROM Graders WHERE id=$gID";
            $result = mysqli_query($GLOBALS['conn'], $sql);
        }else{
            echo "Grader has courses:<br>";
            echo $a['section_id'];
            while($a = $result->fetch_assoc()){
                echo " " . $a['section_id'];
            }
        }
    }
    /*
        Removes a grader entirely, from every section and the database
    */
    function removeGraderC($id){
        $sql = "SELECT * FROM Graders WHERE login LIKE '%$id%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!$result){
            echo "Grader does not exist! <br>";
            return false;
        }
        $gID = $result->fetch_assoc()["id"];
        $sql = "SELECT section_id FROM to_section WHERE grader_id=$gID";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!$result){
            echo "Could not find any sections with grader, removing grader.";
        }
        while($a = $result->fetch_assoc()){
            print_r($a);
            echo $a['section_id'] . "<br>";
            $section = $a['section_id'];
            $sql = "UPDATE to_section SET grader_id=null WHERE section_id=$section";
            mysqli_query($GLOBALS['conn'], $sql);
            echo "Removed grader " . $id . " from section " . $section . "<br>";
        }
        $sql = "SELECT * FROM to_section WHERE grader_id=$gID";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!($a = $result->fetch_assoc())){
            echo "Grader has no more courses, removing grader from db<br>";
            $sql = "DELETE FROM Graders WHERE id=$gID";
            $result = mysqli_query($GLOBALS['conn'], $sql);
        }
    }
    /*
        Removes a teacher from the database, the teacher must have no classes
    */
    function removeTeacher($id){
        $sql = "SELECT * FROM Instructors WHERE login LIKE '%$id%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!$result){
            echo "Teacher does not exist<br>";
            return false;
        }
        $tID = mysqli_query($GLOBALS['conn'], $sql)->fetch_assoc()["id"];
        $sql = "SELECT * FROM to_section WHERE instructor_id=$tID";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!$result){
            echo "Teacher does not have any sections<br> Removing teacher from db.<br>";
            $sql = "DELETE FROM Instructors WHERE id=$tID";
            $result = mysqli_query($GLOBALS['conn'], $sql);
            return false;
        }
        if($a = $result->fetch_assoc()){
            echo "Teacher has sections, remove them before deleting the teacher or replace the teacher with a new teacher.<br>";
            echo "Sections numbers are : <br>";
            echo $a['section_id'] . "<br>";
            while($a = $result->fetch_assoc()){
                echo $a['section_id'] . "<br>";
            }
        }else{
            echo "Removing " . $id . " from our database <br>";
            $sql = "DELETE FROM Instructors WHERE id=$tID";
            $result = mysqli_query($GLOBALS['conn'], $sql);
        }
    }
    /*
        Adds a student to a section, creates the student if needed
    */
    function addStudent($id, $section){
        $id = ltrim(rtrim($id));
        $sql = "SELECT * FROM to_section WHERE section_id=$section";
        $result = mysqli_query($GLOBALS['conn'], $sql) or die($GLOBALS['conn']->error);
        if(!$result->fetch_assoc()){
            echo "Section does not exist <br>";
            return false;
        }
        $sql = "SELECT * FROM Students WHERE login LIKE '%$id%'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if(!($sid = $result->fetch_assoc()['id'])){
            $sql = "INSERT INTO Students (login)
            VALUES ('$id')";
            if(mysqli_query($GLOBALS['conn'], $sql) === TRUE)
                echo "Student created successfully.";
            else
                echo "Student was not able to be created.";
            $sql = "SELECT * FROM Students WHERE login LIKE '%$id%'";
            $sid = mysqli_query($GLOBALS['conn'], $sql)->fetch_assoc()['id'];
            //student was just created -> add to section
            echo "Adding student to section.<br>";
            $sql = "INSERT INTO to_student (student_id, section_id)
            VALUES ('$sid', '$section')";
            mysqli_query($GLOBALS['conn'], $sql) or die($GLOBALS['conn']->error);   

        }else{
            //check if the student is already part of the section
            $sql = "SELECT * FROM to_student WHERE student_id=$sid";
            $innerresult = mysqli_query($GLOBALS['conn'], $sql);
            if(!$innerresult->fetch_assoc()){
                echo "Adding student to section.<br>";
                $sql = "INSERT INTO to_student (student_id, section_id)
                VALUES ('$sid', '$section')";
                mysqli_query($GLOBALS['conn'], $sql) or die($GLOBALS['conn']->error);   
            }    
            mysqli_free_result($innerresult);
        }
        mysqli_free_result($result);
    }
    /*
        Removes a student from a section
    */
    function removeStudent($id, $section){
        echo $id . "<br>";
        echo $section . "<br>";
        $sql = "SELECT id FROM Students WHERE login LIKE '%$id%'";
        $result = mysqli_query($GLOBALS['conn'], $sql) or die($GLOBALS['conn']->error);
        $currST = $result->fetch_assoc()['id'];
            
       
        //delete the student from the section
        $sql = "DELETE FROM to_student WHERE section_id=$section AND student_id=$currST";
        echo "Current student " . $currST . "<br>";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        //now do some cleanup with the students -- if they have no more sections, remove from the database
        $sql = "SELECT * FROM to_student WHERE student_id=$currST";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if($result->fetch_assoc()){
            echo "Student has other classes <br>";
        }else{
            echo "Student does not have other classes";
            //remove student from Students
            $sql = "DELETE FROM Students WHERE id=$currST";
            if(mysqli_query($GLOBALS['conn'], $sql) === TRUE)
                echo "Student removed entirely.";
        }
        mysqli_free_result($result);
    }
?>