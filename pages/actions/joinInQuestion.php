<?php
	require('../db_config/conn2.php');
	
	function complete($mysqli, $isError, $msg, $data){
		$return = array('ERROR'=> $isError, 'MESSAGE'=>$msg, 'DATA'=>$data);
		$mysqli->close();
		exit(json_encode($return));
	}
	
	//Get required data
	$data = json_decode(file_get_contents("php://input"), true);
	$questionId = $data['id'];
	$osuId = $_SESSION['onidid'];
	$firstName = $_SESSION['firstname'];
	$lastName = $_SESSION['lastname'];
	$createdTime = date('Y-m-d H:i:s', time());
	
	//Check is current user a Students or is the user exist
	$sql = 'SELECT role, id FROM t_user WHERE osu_id = "'.$osuId.'"';
	$result = $mysqli->query($sql);
	if($result) {
		if($row = $result->fetch_assoc()){
			$role = $row['role'];
			$userId = $row['id'];
			if($role!='0'){
				complete($mysqli, 1, 'No permission!', NULL);
			}
		}else complete($mysqli, 1, 'Please sign up first!', NULL);
	}
	
	//Check question: is owner
	$sql='SELECT id FROM t_question where id = '.$questionId.' AND stdnt_user_id <> '.$userId.' LIMIT 1';
	$result=$mysqli->query($sql);
	if(!$result) complete($mysqli, 1, 'No such question or the question cannot be joined in by owner!', NULL);
	
	//Get all questions id joined by current user
	$sql='SELECT question_id FROM t_question_concern WHERE user_id = '.$userId;
	$result=$mysqli->query($sql);
	if($result) {
		$isJoinedIds=array();
		while($row = $result->fetch_assoc()) 
			if($questionId==$row['question_id']) complete($mysqli, 1, 'Question cannot be joined twice!', NULL);
	}else complete($mysqli, 1, 'Cannot get concern information!', NULL);

	//Join in the class 
	$sql='INSERT INTO t_question_concern (question_id, user_id, first_name, last_name, created_time) VALUE ('.$questionId.', '.$userId.', "'.$firstName.'", "'.$lastName.'", "'.$createdTime.'")';
	$result = $mysqli->query($sql);
	if(!$result) complete($mysqli, 1, 'Join failed!', NULL);
	
	//update the question table
	$concern = $firstName.' '.$lastName.','.$createdTime.'.';
	$sql='UPDATE t_question SET num_liked = num_liked + 1, concern = concat(IFNULL(concern,""),"'.$concern.'") where id='.$questionId.' LIMIT 1';
	$result = $mysqli->query($sql);
	if(!$result) complete($mysqli, 1, 'Update question info failed!', NULL);
	
	//Get new question info
	$sql='SELECT num_liked FROM t_question where id = '.$questionId.' LIMIT 1';
	$result = $mysqli->query($sql);
	if($result) {
		if($row = $result->fetch_assoc()) complete($mysqli, 0, 'Join success!', NULL);
		else complete($mysqli, 1, 'Question was deleted', NULL);
	}else complete($mysqli, 1, 'Get question info failed!', NULL);
?>