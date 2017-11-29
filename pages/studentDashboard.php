<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Student Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="lib/css/normalize.css">
	<link rel="stylesheet" href="lib/css/skeleton.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/classList.css" />
	<link rel="stylesheet" type="text/css" href="css/base.css" />
	<link rel="stylesheet" type="text/css" href="css/myDialog.css" />
	<script type="text/javascript" src="lib/js/jquery.timepicker.min.js"></script>
	<script type="text/javascript" src="js/base.js"></script>
	<script type="text/javascript" src="js/myDialog.js"></script>
	<script type="text/javascript" src="js/logout.js"></script>
	<script type="text/javascript" src="js/studentDashboard.js"></script>
</head>
<body>

<div id="main" class="container">
	<div class="title row">
		<div class="twelve columns">
			<h4>Students Dashboard</h4><!--page title-->
			<p>Manage Classes</p>
			<div class="user"></div><!--user info-->
			<div class="panel"></div>
		</div>
	</div>

	<div class="funcs row" >
		<div class="four columns">
			<h5 class="subTitle">Please select class</h5>
		</div>
	</div>

	<div class="data row">
		<div class="twelve columns">
			<button class="openAddClassFormDialog" ></button>
			<span class="classList"></span>
		</div>
	</div>
</div>

<div id="dialog">	<!--split dialog out -->
	<div class="container largeBox addClassForm">
		<span class="close"></span>
		<div class="title row">
			<div class="twelve columns">
				<h5>Manage Class</h5>
			</div>
		</div>
		<div class="funcs row">
			<div class="twelve columns">
				<input disabled class="searchInput" type="text" placeholder="Search"/>
			</div>
		</div>
		<form name="addClass" class="post_class">
			<div class="row checkbox">
				<div class="twelve columns">
				</div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<input class="submitBtn button-primary" type="button" value="Add"/>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="mask"></div>
<div id="toast"></div>
</body>
</html>

<script>

</script>