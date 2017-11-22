<?php
	$dir = dirname(__FILE__);
	require_once ($dir."/"."../db_config/conn2.php");
	require_once ($dir."/"."../models/User.class.php");
	require_once ($dir."/"."../models/CompleteMsg.class.php");

	class UserFunctions{

		public function filterUserbyOnids($tas){
			global $mysqli;
			$taName = '';

			foreach ($tas as &$value) {
				if($value !=''){
					if($taName != ''){
						$taName = $taName.',';
					}
					$taName = $taName.'"'.trim($value).'"';
				}
			}
			
			$sql = 'SELECT osu_id FROM t_user WHERE osu_id in ('.$taName .')';
			$result = $mysqli->query($sql);
			
			if($result) {
				$tmp = array();
				while($row = $result->fetch_assoc()){
					$i = -1;
					$onid = $row['osu_id'];
					foreach ($tas as &$value) {
						$i++;
						if(strcmp($value, $onid)){
							array_push($tmp, $value);
						}
					}
					$tas = $tmp;
					$tmp = array();
				}
			}else {
				return new CompleteMsg(1, 'Query user error!', NULL);
			}

			$sql = 'UPDATE t_user SET role = 1 WHERE osu_id in ('.$taName .')';
			$result = $mysqli->query($sql);
			if(!$result) {
				return new CompleteMsg(1, 'Update tas infomation failed!', NULL);
			}

			return new CompleteMsg(0, "Filter tas success!", $tas);
		}

		//add the new user to the DB
		public function insertTabyOnids($tas){
			global $mysqli;
			$i=0;
			if(isset($tas) && $tas !='' && !empty($tas)){
				//$sql = 'INSERT INTO r_user_class (user_id, class_id, role) VALUES ';
				$sql = "INSERT INTO t_user(first_name, last_name, osu_id, role) VALUES ";

				foreach ($tas as &$value) {
					if(trim($value) !=''){
						if($i > 0) 
							$sql = $sql .',';							
						$sql = $sql .'("TBA", "TBA", "'.$value.'", 1)';
						$i++;
					}

				}

				$result = $mysqli->query($sql);
					
				if(!$result) {
					return new CompleteMsg(1, 'Add tas failed!', NULL);
				}
			}
 
			return new CompleteMsg(0, "Add tas success!", NULL);
		}

		//parpare sql
		function checkUserByClassSql(){
			$sql="SELECT
				u.id uid,
				u.osu_id osu_id,
				u.last_name last_name,
				u.first_name first_name,
				u.role u_role
			FROM
				r_user_class r, t_user u
			WHERE
				u.id = r.user_id
				AND u.osu_id = ?
				AND r.class_id = ?
				AND r.role = ?

			LIMIT 1";

			return $sql;
		}

		// parpare Json object
		function checkUserByClassJson($result){
			while($row = $result->fetch_assoc()){
				//user_info
				$user_info['user_info'] = new User($row['uid'], $row['osu_id'], $row['last_name'], $row['first_name'], $row['u_role']);
			}

			return $user_info;
		}

		function checkUserByClass($osu_id, $class_id, $r_role){
			global $mysqli;
			if(!isset($osu_id)){
				return new CompleteMsg(1, 'Failed to get onid!', NULL);
			}

			if(!isset($class_id)){
				return new CompleteMsg(1, 'Failed to get class_id!', NULL);
			}

			$sql = $this->checkUserByClassSql();

			// Preparing sql is failed, exit!
			if(!isset($sql)){
				return new CompleteMsg(1, 'Failed to Prepare sql!', NULL);
			}

			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param("sii", $osu_id, $class_id, $r_role);
			$stmt->execute();
			$result = $stmt->get_result();
			//build Json object
			if(isset($result)) {
				$user_info = $this->checkUserByClassJson($result);
			} else {
				return new CompleteMsg(1, 'You are not Admin in this class!', NULL);
			}

			return new CompleteMsg(0, 'Check user success!', $user_info);
		}

		function findTasinClass($class_id, $r_role){
			global $mysqli;
			if(!isset($r_role)){
				return new CompleteMsg(1, 'Failed to get r_role!', NULL);
			}

			if(!isset($class_id)){
				return new CompleteMsg(1, 'Failed to get class_id!', NULL);
			}

			$sql = "SELECT u.osu_id osu_id, c.id c_id, c.name c_name FROM r_user_class r, t_user u, t_class c WHERE u.id = r.user_id AND r.class_id = c. id AND r.class_id = ? AND r.role = ? ";

			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param("ii", $class_id, $r_role);
			$stmt->execute();
			$result = $stmt->get_result();
			//build Json object
			if(isset($result)) {
				$ta = "";
				while($row = $result->fetch_assoc()){
					$ta_info['class_name'] = $row['c_name'];
					$ta_info['c_id'] = $row['c_id'];
					if($ta == ""){
						$ta = $row['osu_id'];
					}else{
						$ta = $ta.' '.$row['osu_id'];
					}
				}
				$ta_info['ta_names'] = $ta;

			} else {
				return new CompleteMsg(1, 'Find ta in this class error!', NULL);
			}

			return new CompleteMsg(0, 'Find tas success!', $ta_info);
		}

	}
?>