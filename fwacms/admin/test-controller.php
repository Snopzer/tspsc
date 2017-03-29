<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	
	if ($_POST['action'] == 'add') {
		$name 				= $conn->real_escape_string($_POST['name']);		
		$description 		= $conn->real_escape_string($_POST['description']);		
		$free 				= $conn->real_escape_string($_POST['free']);		
		$fee 				= $conn->real_escape_string($_POST['fee']);		
		$questions 			= $conn->real_escape_string($_POST['questions']);		
		$duration 			= $conn->real_escape_string($_POST['duration']);		
		$status 			= $conn->real_escape_string($_POST['status']);	
		
		$date_added = date('Y-m-d h:i:s'); 
		
		$addPost = "INSERT INTO r_test (name,description,free,fee,questions,duration,status) VALUES ('" . $name . "','" . $description . "','" . $free . "','" . $fee . "','" . $questions . "','" . $duration . "','" . $status . "')";
		
		if ($conn->query($addPost) === TRUE) {
			$message = "<strong>Success!</strong> Test Added Successfully.";
			header('location:'.SITE_ADMIN_URL.'test.php?response=success&message='.$message);
			} else {
			header('location:'.SITE_ADMIN_URL.'test.php?response=warning');
		}
		
		} else if ($_POST['action'] == 'edit') {
		
		$id = (int)$_POST['id'];
		
		
		$name 				= $conn->real_escape_string($_POST['name']);		
		$description 		= $conn->real_escape_string($_POST['description']);		
		$free 				= $conn->real_escape_string($_POST['free']);		
		$fee 				= $conn->real_escape_string($_POST['fee']);		
		$questions 			= $conn->real_escape_string($_POST['questions']);		
		$duration 			= $conn->real_escape_string($_POST['duration']);		
		$status 			= $conn->real_escape_string($_POST['status']);	
		
		$editPost = $conn->query( "update r_test SET name='".$name."',description='".$description."',free='".$free."',fee='".$fee."',questions = '".$questions."',	duration = '".$duration."', status = '".$status."'	where id_test='".$id."' ");
		$page = $_POST['page'];
		if ($editPost) {
		
			$message = "<strong>Success!</strong> Test Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'test.php?response=success&message='.$message.'&page=$page');
			} else {
			$message = "<strong>Warning!</strong> Test Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'test.php?response=danger&message='.$message.'&page=$page');
		}
	}
	else if ($_REQUEST['action'] == 'delete') {
		
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			/*Delete SEO URL Details*/
			$result= $conn->query("DELETE FROM r_test WHERE id_test=".$messageid[$i])or die(mysqli_error());
		} 
		
		if ($result) {
			$message = "<strong>Success!</strong> Test Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'test.php?response=success&message='.$message.'&page=$page');
			}else{
			$message = "<strong>Warning!</strong> Test Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'test.php?response=danger&message='.$message.'&page=$page');
		}
	}
	else if ($_REQUEST['action'] == 'export') {
		
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$testQuery= $conn->query("SELECT * FROM r_test WHERE id_test=".$messageid[$i])or die(mysqli_error());
			$testQuestionQuery = $conn->query("SELECT * FROM r_question WHERE id_test=".$messageid[$i])or die(mysqli_error()); 
			
			$test = $testQuery->fetch_assoc();
			$testQuestion = $testQuestionQuery->fetch_assoc();
			
			
			// This appends a new element to $d, in this case the value is another array
			while ($testQuestionData = $testQuestionQuery->fetch_assoc()) {
				$questions[] = $testQuestionData;
			}
			
			$json = json_encode($questions);
			
			$file = fopen("../test.json","w");
			echo fwrite($file,$json);
			fclose($file);
		} 
			$message = "<strong>Success!</strong> Test Exported Successfully.";
			header('location:'.SITE_ADMIN_URL.'test.php?response=success&message='.$message.'&page=$page');
	}
?>

