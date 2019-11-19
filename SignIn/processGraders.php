<?php
	$uid = $_POST['uid'];
	$unum = $_POST['unum'];

	$uid = stripcslashes($uid);
	$unum = stripcslashes($unum);
	$link = mysqli_connect("mysql.cs.pitt.edu", "hoffmant_grading","CfxQY6ycbSNCR5jU","hoffmant_grading");
	$uid = mysqli_real_escape_string($link,$uid);
	$unum = mysqli_real_escape_string($link,$unum);
	mysqli_select_db($link,"Graders");
	$result = mysqli_query($link,"select * from Graders where login = '$uid'") or die("Failed to query database ".mysqli_error($link));
	$row = mysqli_fetch_array($result);
	if($row['login'] == $uid && ("password" ==$unum )){
		echo "Login Successful!!! Welcome " .$row['login'];
		getSections(-1,$uid);
	}else{
		echo "Failed to Login";
	}
        
    function getSections(){
    	$link = mysqli_connect("mysql.cs.pitt.edu", "hoffmant_grading","CfxQY6ycbSNCR5jU","hoffmant_grading");
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
            $teacher = getTeacherByUsername($tID,$link);
            $tNum = $teacher['id'];
        }
        //if a grader id was passed in make sure it exists
        if($gID){
            $grader = getGraderByUsername($gID,$link);
            $gNum = $grader['id'];
            //have teacher and grader ID from database
            //get the section number
            if(!($tID == -1)){
                $sql = "SELECT section_id FROM to_section WHERE grader_id=$gNum AND instructor_id=$tNum";
            }else
                $sql = "SELECT section_id FROM to_section WHERE grader_id=$gNum";
            $result = mysqli_query($link, $sql);
            $arr;
            $i = 0;
            //for each result add it to an array
            while($x = $result->fetch_assoc()){
                $arr[$i] = $x['section_id'];
                $i++;
            }
            printf("\nHere are your courses:");
            	printf("\n %s \n", $arr[0]);
            return $arr;
            echo "<br>";
        }else{
            //only teacher id was passed in
            $sql = "SELECT section_id FROM to_section WHERE instructor_id=$tNum";
            $result = mysqli_query($link, $sql);
            $arr;
            $i = 0;
            //for each result add it to an array
            while($x = $result->fetch_assoc()){
                $arr[$i] = $x['section_id'];
                $i++;
            }
            //if any results are found return them
            if($arr){
           		printf("\nHere are your courses:");
            	printf("\n %s \n", $arr[0]);
            }else{
                echo 'No section found.';
                return false;
            }
            echo "<br>";
        }
        mysqli_free_result($result);
        $ans = mysqli_fetch_array($result);
        printf("%s", $ans[0]);
        
    }
     function getTeacherByUsername($tID,$link){
        $sql = "SELECT id FROM Instructors WHERE login LIKE '%$tID%'";
        $result = mysqli_query($link, $sql);
        $teacher = $result->fetch_assoc() or die;
        mysqli_free_result($result);
        return $teacher;
    }
      function getGraderByUsername($gID,$link){
        $sql = "SELECT DISTINCT id FROM Graders WHERE login LIKE '%$gID%'";
        $result = mysqli_query($link, $sql);
        $grader = $result->fetch_assoc();
        mysqli_free_result($result);
        return $grader;
    }
?>