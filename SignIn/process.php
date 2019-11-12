<?php
	require("db_connect.php");
	$uid = $POST['uid'];
	$unum = $POST['unum'];

	$uid = stripcslashes($uid);
	$unum = stripcslashes($unum);
	$uid = mysql_real_escape_string($uid);
	$unum = mysql_real_escape_string($unum);
	mysql_select_db(student);
	$result = mysql_query("select " from student where student_id = '$uid' and student_pw= 'unum')

?>