<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	if($_POST['action']=='add'){		
		$role = ($_POST['role']);	
		$status = ($_POST['status']);	
		
		$insert = $conn->query("INSERT INTO r_user_role (role,status) VALUES ('" . $role . "','" . $status . "')") or die(mysqli_error());		
		if ($insert) {
			$message = "<strong>Success!</strong> User role Added Successfully.";
			header('location:'.SITE_ADMIN_URL.'userrole.php?response=success&message='.$message);
		}
		else {
			$message = "<strong>Warning!</strong> User Not Added.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'userrole.php?response=Warning&message='.$message);
		}
	}
	else if($_REQUEST['action']=='edit'){
		$id=(int)$_POST['id']  ;   
		$role = ($_POST['role']);	
		$status = ($_POST['status']);	
		$page=$_POST['page'];
		$row = "update r_user_role SET role='".$role."',status=".$status." where id_user_role='$id' ";
		$result = $conn->query($row);
		
		if ($result) {
			$message = "<strong>Success!</strong> User Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'userrole.php?response=success&message='.$message);
			} else {
			$message = "<strong>Warning!</strong> User Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'userrole.php?response=danger&message='.$message);
		} 
	}
	else if($_REQUEST['action']=='delete'){
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$row="DELETE FROM r_user_role WHERE id_user_role=".$messageid[$i];
			$result= $conn->query($row);
		}
		if ($result) {
			$message = "<strong>Success!</strong> user role Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'userrole.php?response=success&message='.$message);
			}else{
			$message = "<strong>Warning!</strong> User role Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'userrole.php?response=danger&message='.$message);
		}
	}
?>
