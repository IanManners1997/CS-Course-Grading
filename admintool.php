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

    // If arrives here, is a valid user.
    echo "<p>Welcome $user.</p>";
    echo "<p>You are validated.</p>";

    ?>
    <head>
            <link rel="stylesheet" href="admintool.css">
    </head>
<div class="row">
    <div class="column">
        <form action="admintool.php" method="post">
            <pre>           Add Grader</pre>
            <pre>Name:    <input type="text" name="name"><br></pre>
            <pre>Id:      <input type="text" name="id"><br></pre>
            <pre>Section: <input type="text" name="section"><br></pre>
            <center><input type="submit" name="sGrader" value="Submit"></center>
        </form> 
            <br><br><br>
        <form action="admintool.php" method="post">
            <pre>           Remove Grader</pre>
            <pre>Id:      <input type="text" name="id"><br></pre>
            <pre>Section: <input type="text" name="section"><br></pre>
            <input type="submit" name="rGraderComplete" value="Remove grader">
            <input type="submit" name="rGraderSection" value="Remove from section">
            <br><br><br><br>
        </form> 
        <form action="admintool.php" method="post">
            <pre>             Import File</pre>
            <pre>*note* name should end with .txt<br></pre>
            <pre>File name: <input type="text" name="filename"><br></pre>  
            <center><input type="submit" name="sFile" value="Submit"></center>
        </form> 
    </div>
    <div class="column">
        <form action="admintool.php" method="post">
            <pre>           Add Teacher</pre>
            <pre>Name:    <input type="text" name="name"><br></pre>
            <pre>Id:      <input type="text" name="id"><br></pre>
            <pre>Section: <input type="text" name="section"><br></pre>   
            <center><input type="submit" name="sTeacher" value="Submit"> </center>
            <br><br><br>
        </form>
        <form action="admintool.php" method="post">    
            <pre>           Remove Teacher</pre>
            <pre>Id:      <input type="text" name="id"><br></pre>
            <center><input type="submit" name="rTeacher" value="Submit"></center>
            <br><br><br><br><br><br>
            <pre>       Dump all tables     <input type="submit" name="dump"value="Dump"></center></pre>
            <pre>       Dump Teachers       <input type="submit" name="dumpT"value="Dump"></center></pre>
            <pre>       Dump Graders        <input type="submit" name="dumpG"value="Dump"></center></pre>
        </form> 
    </div>
    <div class="column">
        <form action="admintool.php" method="post">
            <pre>           Add Student</pre>
            <pre>Id:      <input type="text" name="id"><br></pre>
            <pre>Section: <input type="text" name="section"><br></pre>  
            <center><input type="submit" name="sStudent" value="Submit"></center><br><br><br><br><br>
        </form> 
        <form action="admintool.php" method="post">
            <pre>           Remove Student from Section</pre>
            <pre>Id:      <input type="text" name="id"><br></pre>
            <pre>Section: <input type="text" name="section"><br></pre>  
            <pre><center><input type="submit" name="rStudent" value="Submit"></center></pre>
            <br><br><br>
            <pre>       Dump Students       <input type="submit" name="dumpST"value="Dump"></center></pre>
            <pre>       Dump Sections       <input type="submit" name="dumpSE"value="Dump"></center></pre>
            <pre>       Truncate all tables <input type="submit" name="truncate"value="Truncate"></pre></center>
        </form>
    </div>
    <div class="column">
        <form action="admintool.php" method="post">
            <pre>         Students to textfile<br></pre>
            <pre>Section: <input type="text" name="section"></pre>
            <center><input type="submit" name="toTXTS"value="submit"></center>
            <br>
        </form> 
        <form action="admintool.php" method="post">
            <pre>         Students by teacher<br></pre>
            <pre>Teacher id: <input type="text" name="id"></pre>
            <center><input type="submit" name="toTXTT"value="submit"></center>
            <br>
        </form> 
        <form action="admintool.php" method="post">
            <pre>         Students by grader<br></pre>
            <pre>Grader id: <input type="text" name="id"></pre>
            <center><input type="submit" name="toTXTG"value="submit"></center>
            <br>
        </form> 
        <form action="admintool.php" method="post">
            <pre>         Students by grader and teacher<br></pre>
            <pre>Teacher id: <input type="text" name="tID"></pre>
            <pre>Grader id: <input type="text" name="gID"></pre>
            <center><input type="submit" name="toTXTTG"value="submit"></center>
            <br>
        </form> 
        <form action="admintool.php" method="post">
            <pre>           Remove Section</pre>
            <pre>Section: <input type="text" name="section"><br></pre>  
            <center><input type="submit" name="rSection" value="Submit"></center>
        </form> 
    </div>
</div>
<br><br>
<div class="scrollable">
    <center> Log </center>
    <?php
        require("sqlFunctions.php");
        //REPLACE/ADD TEACHER -- FINISHED
        if(isset($_POST["sTeacher"])){
            print_r($_POST);
            $name = $_POST['name'];
            $id = $_POST['id'];
            $section=$_POST['section'];
            if($name && $id && $section){
                echo "Adding: " . $name . " " . $id . " to section #: " . $section;
                addTeacher($name, $id, $section);
            }else
                echo "A Parameter is missing, please insert a name, id and section.";
        }
        //REMOVE TEACHER -- FINISHED
        if(isset($_POST["rTeacher"])){
            print_r($_POST);
            $id = $_POST['id'];
            if($id){
                removeTeacher($id);
            }else
                echo "A Parameter is missing, please insert a name, id and section.";
        }
        //ADD GRADER -- FINISHED
        if(isset($_POST["sGrader"])){
            print_r($_POST);
            $name = $_POST['name'];
            $id = $_POST['id'];
            $section=$_POST['section'];
            if($name && $id && $section){
                addGrader($name, $id, $section);
            }else
                echo "A Parameter is missing, please insert a name, id and section.";
        }
        //REMOVE GRADER -- FINISHED
        if(isset($_POST["rGraderSection"])){
            print_r($_POST);
            $id = $_POST['id'];
            $section = $_POST['section'];
            if($id && is_numeric($section)){
                echo "all set";
                removeGraderS($id, $section);
            }else
                echo "A Parameter is missing, please insert a name, id and section.";
        }
        //REMOVE GRADER -- FINISHED
        if(isset($_POST["rGraderComplete"])){
            print_r($_POST);
            $id = $_POST['id'];
            removeGraderC($id);
        }
        //ADD STUDENT -- FINISHED
        if(isset($_POST["sStudent"])){
            print_r($_POST);
            $id = $_POST['id'];
            $section=$_POST['section'];
            if($id && $section){
                addStudent($id, $section);
            }else
                echo "A Parameter is missing, please insert a name, id and section.";
        }
        //REMOVE STUDENT -- FINISHED
        if(isset($_POST["rStudent"])){
            echo "Post array : ";
            print_r($_POST);
            $id = $_POST['id'];
            $section=$_POST['section'];
            if($id && is_numeric($section)){
                echo "all set<br>";
                removeStudent($id, $section);
                
            }else
                echo "A Parameter is missing, please insert a name, id and section.";
        }
        //IMPORT FILE -- FINISHED
        function endsWith($string, $endString) 
        { 
            $len = strlen($endString); 
            if ($len == 0) { 
                return true; 
            } 
            return (substr($string, -$len) === $endString); 
        } 
        if(isset($_POST["sFile"])){
            echo "Post array : ";
            print_r($_POST);
            $file = $_POST['filename'];
            if($file && endsWith($file, ".txt")){
                echo "all set -- importing file <br>";
                separateFile($file);
            }else
                echo "A Parameter is missing or malformed. Please enter a file name ending in .txt";
        }
        //DUMP FUNCTIONS -- FINISHED
        if(isset($_POST["dump"])){
            echo "Post array : ";
            print_r($_POST);
            echo "dumping the database <br>";
            dump();
        }
        //DUMP TEACHERS -- FINISHED
        if(isset($_POST["dumpT"])){
            echo "Post array : ";
            print_r($_POST);
            echo "Dumping all teachers <br>";
            dumpTeachers();
        }
        //DUMP GRADERS -- FINISHED
        if(isset($_POST["dumpG"])){
            echo "Post array : ";
            print_r($_POST);
            echo "Dumping all graders <br>";
            dumpGraders();
        }
        //DUMP STUDENTS -- FINISHED
        if(isset($_POST["dumpST"])){
            echo "Post array : ";
            print_r($_POST);
            echo "Dumping all students <br>";
            dumpStudents();
        }
        //DUMP SECTIONS -- FINISHED
        if(isset($_POST["dumpSE"])){
            print_r($_POST);
            echo "dumping all sections <br>";
            dumpSections();
        }
        //TRUNCATE FUNCTIONS -- FINISHED
        if(isset($_POST["truncate"])){
            print_r($_POST);
            echo 'Are you sure you want to truncate the database?';
            echo '<form action="admintool.php" method="post">';
            echo '<input type="submit" name="truncateConfirmed" value="Yes"/>';
            echo '</form>';
            
        }
        if(isset($_POST["truncateConfirmed"])){
            print_r($_POST);
            truncateAll();
            echo "Database truncated.";
        }
        //STUDENTS TO TEXT FILE FUNCTIONS --
        //BY SECTION # -- FINISHED
        if(isset($_POST["toTXTS"])){
            print_r($_POST);
            if(is_numeric($d = $_POST["section"])){
                echo "Adding all students to a text file<br>";
                studentsToFile($d);
            }else{
                echo "section is not a number";
            }
        }
        //BY TEACHER -- FINISHED
        if(isset($_POST["toTXTT"])){
            print_r($_POST);
            if($id = $_POST["id"]){
                echo "Adding all students to a text file<br>";
                $d = getSections($id);
                for($i = 0; $i < sizeof($d); $i++)
                    studentsToFile($d[$i]);
            }else{
                echo "Please input a teacher";
            }
        }
        //BY GRADER -- FINISHED
        if(isset($_POST["toTXTG"])){
            print_r($_POST);
            if($id = $_POST["id"]){
                echo "Adding all students to a text file<br>";
                $d = getSections(-1, $id);
                
                for($i = 0; $i < sizeof($d); $i++)
                    studentsToFile($d[$i]);
            }else{
                echo "Input a grader id.";
            }
        }
        //BY TEACHER & GRADER -- FINISHED
        if(isset($_POST["toTXTTG"])){
            print_r($_POST);
            if($tID = $_POST["tID"] && $gID = $_POST["gID"]){
                echo "Adding all students to a text file<br>";
                $d = getSections($tID, $gID);
                for($i = 0; $i < sizeof($d); $i++)
                    studentsToFile($d[$i]);
            }else{
                echo "Either grader or teacher is not entered";
            }
        }
        //REMOVE SECTION -- FINISHED
        if(isset($_POST["rSection"])){
            print_r($_POST);
            $section=$_POST['section'];
            if(is_numeric($section)){
                echo "Removing section " . $section . "<br>";
                removeSection($section);
            }else
                echo "Section must be a number.";
        }
    ?>
</div>
</html>