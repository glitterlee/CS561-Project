<?php

	if (isset($_SESSION["role"]) && $_SESSION["role"] == "ta") {
		echo header("Location: index.php");
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>TA Class Detail</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="lib/css/normalize.css">
	<link rel="stylesheet" href="lib/css/skeleton.css">
	<link type="text/css" rel="stylesheet" href="lib/css/jquery.pagewalkthrough.css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/react/15.4.2/react.min.js"></script>
	<script src="https://cdn.bootcss.com/react/15.4.2/react-dom.min.js"></script>
	<script src="https://cdn.bootcss.com/babel-standalone/6.22.1/babel.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/myDialog.css" />
	<link rel="stylesheet" type="text/css" href="css/base.css" />
	<link rel="stylesheet" type="text/css" href="css/questionsTable.css" />
	<link rel="stylesheet" type="text/css" href="css/questionDetail.css" />
	<script type="text/javascript" src="js/base.js"></script>
	<script type="text/javascript" src="js/logout.js"></script>
	<script type="text/javascript" src="js/ta_question.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109657627-3"></script>
	<script type="text/javascript" src="lib/js/jquery.pagewalkthrough.min.js"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-109657627-3');
	</script>
	<!-- <script type="text/javascript" src="js/myDialog.js"></script> -->
	<!-- <script type="text/javascript" src="js/timePicker.js"></script> -->
	<!-- <script type="text/javascript" src="js/studentsQuestions.js"></script> -->

</head>
<body>
<div id="main" class="container">
	<div class="title row">
		<div id="titleTag" class="twelve columns">
			<h4>TA Question Detail Page</h4>	<!--page title-->
			<div class="user">			<!--user info-->
				<img onclick="logout()" src="images/svg/logout.svg" /> <!--action:logout-->
				<span id="logout_name" style="float:right">XXX</span>
			</div>
            <div class="panel"><a href="studentDashboard.php">Switch to Student Dashboard</a></div>
		</div>
	</div>
	<div class="data row">
		<div class="six columns" id="question_title"></div>
		<div class="six columns" id="question_time"></div>
	</div>
	<div id="question_desc"></div>
	<div id="student_list"></div>
	<div class="data row">
		<div class="twelve columns studentList"></div>
	</div>
</div>
<div id="mask"></div>
<div id="toast"></div>
</body>
</html>
