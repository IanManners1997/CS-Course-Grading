<?php
	$uid = $_POST['uid'];
	$unum = $_POST['unum'];

	$uid = stripcslashes($uid);
	$unum = stripcslashes($unum);
	$link = mysqli_connect("mysql.cs.pitt.edu", "hoffmant_grading","CfxQY6ycbSNCR5jU","hoffmant_grading");
	$uid = mysqli_real_escape_string($link,$uid);
	$unum = mysqli_real_escape_string($link,$unum);
	mysqli_select_db($link,"Students");
	$result = mysqli_query($link,"select * from Students where login = '$uid'") or die("Failed to query database ".mysqli_error($link));
	$row = mysqli_fetch_array($result);
	if($row['login'] == $uid && ("password" ==$unum )){
		echo "Login Successful!!! Welcome " .$row['login'];
	}else{
		echo "Failed to Login";
	}

?>