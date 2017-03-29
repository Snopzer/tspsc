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
		$name = $conn->real_escape_string($_POST['name']);	
		$status = $conn->real_escape_string($_POST['status']);	
		
			$insert = $conn->query("INSERT INTO r_subject (name,status) VALUES ('" . $name . "','" . $status . "')") or die(mysqli_error());	
			$userid=$conn->insert_id;
			if ($insert) {
				$message = "<strong>Success!</strong> Subject Added Successfully.";
				header('location:'.SITE_ADMIN_URL.'subject.php?response=success&message='.$message);
			}
			else {
				$message = "<strong>Warning!</strong> Subject Not Added.Please check Carefully..";
				header('location:'.SITE_ADMIN_URL.'Subject.php?response=warning');
			}
		
	}
	else if($_REQUEST['action']=='edit'){
		$id=(int)$_POST['id']  ;   
		$name = $conn->real_escape_string($_POST['name']);	
		$status = $conn->real_escape_string($_POST['status']);		
		
		
		$result2 = $conn->query( " update r_subject SET name='".$name."', status ='" . $status . "'	where id_subject='".$id."' ") or die(mysqli_error());
		
		
		
		if ($result2) {
			$message = "<strong>Success!</strong> subject Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'subject.php?response=success&message='.$message.'&page=$page');
			} else {
			$message = "<strong>Warning!</strong> subject Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'subject.php?response=danger&message='.$message.'&page=$page');
		}
	}
	else if($_REQUEST['action']=='delete'){
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$conn->query("DELETE FROM r_subject WHERE id_subject=".$messageid[$i]);
			$result= $conn->query($row);
		}
		
		$message = "<strong>Success!</strong> subject Deleted Successfully.";
		header('location:'.SITE_ADMIN_URL.'subject.php?response=success&message='.$message.'&page=$page');
		
		/*if ($result) {
			$message = "<strong>Success!</strong> subject Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'subject.php?response=success&message='.$message.'&page=$page');
			}else{
			$message = "<strong>Warning!</strong> subject Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'subject.php?response=danger&message='.$message.'&page=$page');
		}*/
	}
?>
