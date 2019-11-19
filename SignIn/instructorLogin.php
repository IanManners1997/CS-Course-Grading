<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

	<script src="js/tools.js" type="text/javascript"></script>
	<script src='js/jquery.form.js' type='text/javascript'></script>
	
	
	<title>Professor Hoffman's CS 445</title>
</head>
<body>
	<div id="header">
		<a id='title' href='index.php'>CS Course Grading</a>
			</div>
					<div id="login">
					<p>
						Authentication Required<br />
						<span class="error"></span>
					</p>
					<form action="processInstructor.php" method="POST">
						<table>
							<tr>
								<td>ID: (jfk63 lower case)<br></td>
								<td><input name="uid" type="text" /></td>
							</tr>
			
							<tr>
								<td>pplSoft#: (9182734)<br></td>
								<td><input name="unum" type="password" /></td>
							</tr>
						</table>
						<input type="submit" id = "btm" value="Login"/>
					</form>
				</div>
						<div id="footer">
			Send all bug reports to <a href="mailto:hoffmant@pitt.edu">hoffmant@cs.pitt.edu</a>
		</div>
</body>
</html>
