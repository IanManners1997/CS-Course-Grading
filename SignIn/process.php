<?php
	include 'db_connect.php';
	$uid = $_POST['uid'];
	$unum = $_POST['unum'];

	$uid = stripcslashes($uid);
	$unum = stripcslashes($unum);
	$uid = mysqli_real_escape_string($conn,$uid);
	$unum = mysqli_real_escape_string($conn,$unum);
	$result = mysqli_query($conn,"select * from Instructors where login = '$uid'") or die("Failed to query database ".mysqli_error($conn));
	$result2 = mysqli_query($conn,"select * from Graders where login = '$uid'") or die("Failed to query database ".mysqli_error($conn));
	$row = mysqli_fetch_array($result);
	$row2 = mysqli_fetch_array($result2);
	if(($row['login'] == $uid) && ("password" ==$unum )){
		printf("Login Successful. Welcome %s %s",$row['fname'],$row['lname']);
		session_start();
		$_SESSION['log'] = $uid;
	}else{
		/*echo "Failed to Login"*/
	}

	if(($row2['login'] == $uid) && ("password" ==$unum )){
		printf("Login Successful. Welcome %s %s",$row2['fname'],$row2['lname']);
		session_start();
		$_SESSION['log2'] = $uid;
	}else{
		/*
		echo "Failed to Login";
		*/
	}

?>
<html>
<head>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link type="text/css" href="css/style1.css" rel="stylesheet" />
	
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

	<script src="js/tools.js" type="text/javascript"></script>
	<script src='js/jquery.form.js' type='text/javascript'></script>
	
	<input id="uid" type="hidden" value="<?php echo $uid; ?>"/>
	<title>Professor Hoffman's CS 401</title>

<style>
*{
	margin:0px;
	padding:0px;
}
body{
	background-color:#F7F9F9;
	margin-left:250px;
	margin-top:100px;
}
#container ul{
	list-style:none;
}
#container ul li{
	background-color:#274e74;
	width:150px;
	border:1px solid white;
	height:50px;
	line-height:50px;
	text-align:center;
	float:left;
	color:white;
	font-size:19px;
}
#container ul li:hover{
	background-color:#85C1E9;

}
#container ul ul{
	display: none;
}
#container ul li:hover> ul{
	display:block;
}
</style>
</head>
<body>
	<div id="header">
		<a id='title' href='index.php'>CS Course Grading</a>
			</div>
						
	<div id="container">
	<ul>
		<li>Sign In As
		<ul>
			<a href = "instructorLogin.php"<li>Instructor</li></a>
			<a href = "graderLogin.php">Grader</li>
			<a href = "studentLogin.php"<li>Administrator</li>
		</ul>
		</li>
	</ul>
	</div>
	<div id="footer">
			Send all bug reports to <a href="mailto:hoffmant@pitt.edu">hoffmant@cs.pitt.edu</a>
	</div>

</body>
</html>
