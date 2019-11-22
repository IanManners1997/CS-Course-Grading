<!DOCTYPE html>
<html>
    <?php
    //store the password hashed even though we're using basic auth just in case
    $valid_passwords = array ("hoffmantDB" => hash('md4', "p00b1es77"));
    $valid_users = array_keys($valid_passwords);

    $user = $_SERVER['PHP_AUTH_USER'];
    $pass = $_SERVER['PHP_AUTH_PW'];
    //compared the hashed version of the password
    $validated = (in_array($user, $valid_users)) && (hash('md4',$pass) == $valid_passwords[$user]);

    if (!$validated) {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        die ("Not authorized");
    }
    ?>
    <head>
            <link rel="stylesheet" href="admintool.css">
    </head>
<center>Select a table to modify : </center><br>
<div class="header">
    <div class="row">
        <div class="column">
            <input type="Button" id="Teacher" value="Teacher">
        </div>
        <div class="column">
            <input type="Button" id="Grader"  value="Grader">
        </div>
        <div class="column">
            <input type="Button" id="Student" value="Student">
        </div>
        <div class="column">
            <input type="Button" id="Section" value="Section">
        </div>
        <div class="column">
            <input type="Button" id="Misc" value="Misc Functions">
        </div>
    </div>
</div>
<script src="adminTool.js"></script>
<form action="adminToolV2.php" method="post">
    <div id="displayDiv" class="displayDivClass">

    </div>
</form>
<div class="scrollable">
    <center> Log </center>
    <!-- All PHP functions from here on -->
    <?php
        require("sqlFunctions.php");
        echo "Post array : <br>";
        print_r($_POST);

        //TEACHER PAGE
        //ADD TEACHER
        if(isset($_POST["addTeacher"])){
            $name[0] = $_POST["aTFName"];
            $name[1] = $_POST["aTLName"];
            $username = $_POST["aTUser"];
            echo "Adding Teacher: First name " . $name[0] . " " . $name[1] . "<br>";
            echo "Username : " . $username . "<br>";
            //create the teacher
            createTeacher([$name[0] . " " . $name[1], $username]);
        }
        //UPDATE TEACHER
        if(isset($_POST["updateTeacher"])){
            $currentTeacherUsername = $_POST["uTUser"];
            $newFirstName = $_POST["nuTFName"];
            $newLastName = $_POST["nuTLName"];
            $newUsername = $_POST["nuTUser"];
            echo "Updating Teacher " . $currentTeacherUsername . " : First name " . $newFirstName . " " . $newLastName . "<br>";
            echo "Username : " . $newUsername . "<br>";
            updateTeacher($currentTeacherUsername, [$newFirstName . " " . $newLastName, $newUsername]);
        }
        //REMOVE TEACHER
        if(isset($_POST["removeTeacher"])){
            $teacherToRemoveUsername = $_POST["rTUser"];
            echo "Removing Teacher : " . $teacherToRemoveUsername . "<br>";
            removeTeacher($teacherToRemoveUsername);
        }
        //DUMP TEACHER TABLE
        if(isset($_POST["dumpTeacher"])){
            dumpTeachers();
        }

        //GRADER PAGE
        //Add Grader
        if(isset($_POST["addGrader"])){
            $name[0] = $_POST["aGFName"];
            $name[1] = $_POST["aGLName"];
            $username = $_POST["aGUser"];
            echo "Adding Grader: First name " . $name[0] . " Last name " . $name[1] . "<br>";
            echo "Username : " . $username . "<br>";
            createGrader([$name[0] . " " . $name[1], $username]);
        }
        //Update Grader
        if(isset($_POST["updateGrader"])){
            $currentGraderUsername = $_POST["uGUser"];
            $newFirstName = $_POST["nuGFName"];
            $newLastName = $_POST["nuGLName"];
            $newUsername = $_POST["nuGUser"];
            echo "Updating Grader " . $currentGraderUsername . " : First name " . $newFirstName . " " . $newLastName . "<br>";
            echo "Username : " . $newUsername . "<br>";
            updateGrader($currentGraderUsername, [$newFirstName . " " . $newLastName, $newUsername]);
        }
        //Remove Grader
        if(isset($_POST["removeGrader"])){
            $graderToRemoveUsername = $_POST["rGUser"];
            echo "Removing Grader : " . $graderToRemoveUsername . "<br>";
            removeGraderC($graderToRemoveUsername);
        }
        //Dump Grader Table
        if(isset($_POST["dumpGrader"])){
            dumpGraders();
        }

        //Student PAGE
        //Add Student
        if(isset($_POST["addStudent"])){
            $username = $_POST["aSUser"];
            echo "Creating Student : " . $username . "<br>";
            createStudent($username);
        }
        //Remove Student
        if(isset($_POST["removeStudent"])){
            $toRemoveUsername = $_POST["rDUser"];
            removeStudentC($toRemoveUsername);
        }
        //Dump Student Table
        if(isset($_POST["dumpStudent"])){
            dumpStudents();
        }
        //Section Page
        //Add Section
        if(isset($_POST["addSection"])){
            $teacher = $_POST["teacherOfSection"];
            $grader = $_POST["graderOfSection"];
            $section = $_POST["section"];
            echo "Creating section : " . $section . " : Teacher : " . $teacher . " Grader : " . $grader . "<br>";
            createSection($teacher, $grader, NULL, $section);
        }
        //Update Section
        if(isset($_POST["updateSectionTeacher"])){
            $section = $_POST["usection"];
            $teacher = $_POST["uteacher"];
            echo "Updating section : " . $section . " with Teacher : " . $teacher . "<br>";
            updateTeacherSection(getTeacherByUsername($teacher), $section);
        }
        if(isset($_POST["updateSectionGrader"])){
            $section = $_POST["usection"];
            $grader = $_POST["ugrader"];
            echo "Updating section : " . $section . " with Grader : " . $grader . "<br>";
            updateGraderSection(getGraderByUsername($grader), $section);
        }
        if(isset($_POST["updateSectionStudent"])){
            $section = $_POST["usection"];
            $student = $_POST["ustudent"];
            echo "Adding student : " . $student . " to Section : " . $section . "<br>";
            addStudent($student, $section);
        }
        //Remove Section
        if(isset($_POST["removeSection2"])){
            $section = $_POST["rsection"];
            echo "Removing Section : " . $section . "<br>";
            removeSection($section);
        }
        //Remove Grader from Section
        if(isset($_POST["removeSection"]) && isset($_POST['gUser'])){
            $section = $_POST["sectionToRemoveFrom"];
            $grader = $_POST['gUser'];
            echo "Removing Grader From Section : " . $section . "<br>";
            removeGraderS($grader, $section);
        }
        //Remove Student from Section
        if(isset($_POST["removeSection"]) && isset($_POST['sUser'])){
            $section = $_POST["sectionToRemoveFrom"];
            $student = $_POST['sUser'];
            echo "Removing Student From Section : " . $section . "<br>";
            removeStudentS($student, $section);
        }
        //Dump Section Table
        if(isset($_POST["dumpSection"])){
            dumpSections();
        }

        //Misc page
        //Drop all tables
        if(isset($_POST["dropAll"])){
            dropAll();
        }
        //Truncate all tables
        if(isset($_POST["truncateAll"])){
            truncateAll();
        }
        //Create all tables
        if(isset($_POST["createAll"])){
            createTables();
        }
        //Dump all tables
        if(isset($_POST["dumpAll"])){
            dump();
        }
        //Import from text file
        if(isset($_POST["submitImportText"])){
            $fileName = $_POST["importText"];
            echo "Importing file : " . $fileName . "<br>";
            separateFile($fileName);
        }
        //export section by teacher username
        if(isset($_POST["submitExportTSection"])){
            $teacher = $_POST["exportTSection"];
            echo "Exporting Section by Teacher : " . $teacher . "<br>";
            print_r($_POST);
            if($teacher){
                echo "Adding all students to a text file<br>";
                $d = getSections($teacher);
                if(!$d)
                    return null;
                for($i = 0; $i < sizeof($d); $i++)
                    studentsToFile(strval($d[$i]) . "section.txt", $d[$i]);
            }else{
                echo "Please input a teacher";
            }
        }
        //Export section by grader username
        if(isset($_POST["submitExportGSection"])){
            $grader = $_POST["exportGSection"];
            echo "Exporting Section by Grader : " . $grader . "<br>";
            print_r($_POST);
            if($grader){
                echo "Adding all students to a text file<br>";
                $d = getSections(-1, $grader);
                if(!$d)
                    return null;
                for($i = 0; $i < sizeof($d); $i++)
                    studentsToFile(strval($d[$i]) . "section.txt", $d[$i]);
            }else{
                echo "Input a grader id.";
            }
        }
    ?>
</div>
</html>