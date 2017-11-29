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
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/myDialog.css" />
	<link rel="stylesheet" type="text/css" href="css/base.css" />
	<link rel="stylesheet" type="text/css" href="css/questionsTable.css" />
	<script type="text/javascript" src="js/base.js"></script>
	<script type="text/javascript" src="js/ta_class.js"></script>
	<script type="text/javascript" src="js/logout.js"></script>
	<!-- <script type="text/javascript" src="js/myDialog.js"></script> -->
	<!-- <script type="text/javascript" src="js/timePicker.js"></script> -->
	<!-- <script type="text/javascript" src="js/studentsQuestions.js"></script> -->

</head>
<body>
<div id="main" class="container">
	<div class="title row">
		<div class="twelve columns">
			<h4>TA Class Detail Page</h4>	<!--page title-->
			<p>Questions List.</p>
			<div class="user">					<!--user info-->
				<img onclick="logout()" src="images/svg/logout.svg" /> <!--action:logout-->
				<span id="logout_name">XXX</span>
			</div>
			<div class="panel"><a href="studentDashboard.php">Switch to Student Dashboard</a></div>
		</div>
	</div>
	<div class="data row">
		<div class="twelve columns">
			<table class="u-full-width">
				<thead>
					<tr>
						<th style="width:28%">Title</th>
						<th style="width:22%">Poster</th>
						<th style="width:20%">Post Time</th>
						<th style="width:15%">Status</th>
						<th style="width:10%">Members</th>
						<th style="width:5%"></th>
						<!--<th class="tableBlock"></th> -->
					</tr>
				</thead>
				<tbody>
					<!--Questions list -->													<!--action:getQuestionList-->
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="dialog">	<!--split dialog out -->
	<div class="container largeBox questionForm">
		<span class="close"></span>
		<div class="title row">
			<div class="twelve columns">
				<h5>Post A New Question</h5>
			</div>
		</div>
		<form name="QUESTIONS" class="post_ques">
			<div class="row">
				<div class="six columns">
					<label for="titleInput">Question Title</label>
					<input name="TITLE" class="u-full-width" type="text" id="titleInput" required/><span></span>
				</div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<label for="questextsInput">Description</label>
					<textarea name="DESCRIPTION" class="quesArea" placeholder="" id="questextsInput" required></textarea><span></span>
				</div>
			</div>
			<div class="row timeSelect">
				<div class="twelve columns " >
					<label for="timeSelect">Planned Arrival Time:</label>
					<label>
						<input class="nowRadioBtn" name="AVAILABLE_TIME" type="radio" value="now" checked="checked" /> Now<br/>
					</label>
					<div class="row" >
						<div class="eight columns">
							<label>
								<input class="laterRadioBtn" name="AVAILABLE_TIME" type="radio" value=""/>
								<span> Later:</span>
								<input id="timeDetailInput" class="timeDetailInput" type="text" disabled="disabled" required/><span></span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<input class="submitBtn button-primary" type="button" value="Post"/>		<!--action:createNewQuestion & questionList-->
				</div>
			</div>
		</form>
	</div>
</div>
<div id="mask"></div>
<div id="toast"></div>
</body>
</html>