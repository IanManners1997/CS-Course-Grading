<?php
	include 'db_connect.php';
	include 'sqlFunctions.php';
	session_start();
	$arr2 = getTeachersByGraderUsername($_SESSION['log2']);
	$counter = 0;
	?>


<html>
<head>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link type="text/css" href="css/style1.css" rel="stylesheet" />
	
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

	<script src="js/tools.js" type="text/javascript"></script>
	<script src='js/jquery.form.js' type='text/javascript'></script>
	
	
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
		<li>Instructors
		<ul>
			<a value = "<?php echo $str1; ?>" href = "studentLogin.php"><li>hoffmant</li></a>
			<a href = "graderLogin.php"> <li>tst493</li></a>
		</ul>
		</li>
	</ul>
	</div>
	<div id="footer">
			Send all bug reports to <a href="mailto:hoffmant@pitt.edu">hoffmant@cs.pitt.edu</a>
	</div>

</body>
</html>
