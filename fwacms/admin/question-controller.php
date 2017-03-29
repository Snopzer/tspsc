<?php
	/*echo "<pre>";
	print_r($_REQUEST);
	exit;*/
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	if($_POST['action']=='add'){		
		//$question = ($_POST['question']);	
		$test = $conn->real_escape_string($_POST['test']);	
		$question = $conn->real_escape_string($_POST['question']);	
		$option_1 = $conn->real_escape_string($_POST['option_1']);	
		$option_2 = $conn->real_escape_string($_POST['option_2']);	
		$option_3 = $conn->real_escape_string($_POST['option_3']);	
		$option_4 = $conn->real_escape_string($_POST['option_4']);	
		$option_correct = $conn->real_escape_string($_POST['option_correct']);	
		$notes = $conn->real_escape_string($_POST['notes']);	
		$status = $conn->real_escape_string($_POST['status']);	
		
			$insert = $conn->query("INSERT INTO r_question (id_test,question,option_1,option_2,option_3,option_4,option_correct,notes,status) VALUES ('".$test."','" . $question . "','" . $option_1 . "','" . $option_2 . "','" . $option_3 . "','" . $option_4 . "','" . $option_correct . "','" . $notes . "','" . $status . "')") or die(mysqli_error());	
			$userid=$conn->insert_id;
			if ($insert) {
				$message = "<strong>Success!</strong> Question Added Successfully.";
				header('location:'.SITE_ADMIN_URL.'question.php?response=success&message='.$message);
			}
			else {
				$message = "<strong>Warning!</strong> Question Not Added.Please check Carefully..";
				header('location:'.SITE_ADMIN_URL.'question.php?response=warning');
			}
		
	}
	else if($_REQUEST['action']=='edit'){
		$id=(int)$_POST['id']  ;   
		$test = $conn->real_escape_string($_POST['test']);	
		$question = $conn->real_escape_string($_POST['question']);	
		$option_1 = $conn->real_escape_string($_POST['option_1']);	
		$option_2 = $conn->real_escape_string($_POST['option_2']);	
		$option_3 = $conn->real_escape_string($_POST['option_3']);	
		$option_4 = $conn->real_escape_string($_POST['option_4']);	
		$option_correct = $conn->real_escape_string($_POST['option_correct']);	
		$notes = $conn->real_escape_string($_POST['notes']);	
		$status = $conn->real_escape_string($_POST['status']);		
		
		
		$result2 = $conn->query( " update r_question SET id_test='".$test."',question='".$question."',option_1='".$option_1."',option_2='".$option_2."',option_4='".$option_4."',option_correct = '".$option_correct."',	notes = '".$notes."', status ='" . $status . "'	where id_question='".$id."' ") or die(mysqli_error());
		
		
		
		if ($result2) {
			$message = "<strong>Success!</strong> Question Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'question.php?response=success&message='.$message.'&page=$page');
			} else {
			$message = "<strong>Warning!</strong> Question Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'question.php?response=danger&message='.$message.'&page=$page');
		}
	}
	else if($_REQUEST['action']=='delete'){
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$conn->query("DELETE FROM r_question WHERE id_question=".$messageid[$i]);
			$result= $conn->query($row);
		}
		
		$message = "<strong>Success!</strong> Question Deleted Successfully.";
		header('location:'.SITE_ADMIN_URL.'question.php?response=success&message='.$message.'&page=$page');
		
		/*if ($result) {
			$message = "<strong>Success!</strong> Question Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'question.php?response=success&message='.$message.'&page=$page');
			}else{
			$message = "<strong>Warning!</strong> Question Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'question.php?response=danger&message='.$message.'&page=$page');
		}*/
	}
?>
