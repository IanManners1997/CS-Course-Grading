<?php
	require("db_connect.php");
	$uid = $POST['uid'];
	$unum = $POST['unum'];

	$uid = stripcslashes($uid);
	$unum = stripcslashes($unum);
	$uid = mysql_real_escape_string($uid);
	$unum = mysql_real_escape_string($unum);
	mysql_select_db(student);
	$result = mysql_query("select " from student where student_id = '$uid' and student_pw= 'unum') or die("Failed to query database ".mysql_error());
	$row = mysql_fetch_array($result);
	if($row['student_id'] == $uid && $row['student_pw']==$unum){
		echo "Login Successful!!! Welcome " $row['student_id'];
	}else{
		echo "Failed to Login";
	}

?>