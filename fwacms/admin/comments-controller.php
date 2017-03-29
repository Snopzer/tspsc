<?php
	ob_start(); 
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	if($_POST['action']=='add'){
		$name = $conn->real_escape_string($_POST['name']);
		$email = $conn->real_escape_string($_POST['email']);
		$subject = $conn->real_escape_string($_POST['subject']);
		$website = $conn->real_escape_string($_POST['website']);
		$message = $conn->real_escape_string($_POST['message']);
		
		$addCommentQuery =  $conn->query("INSERT INTO r_comment (name,email,subject,message,website) VALUES ('".$name."','".$email."','".$subject."','".$message."','".$website."')") or die(mysqli_error());
		
        if($addCommentQuery){
			$message = "<strong>Success!</strong> Comment Added Successfully.";
			header('location:'.SITE_ADMIN_URL.'comments.php?response=success&message='.$message);
			} else {
				$message = "<strong>Success!</strong> Comment Not Added .Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'comments.php?response=warning');
		}
		
           
	}	else if($_POST['action']=='edit'){
		$id = (int)$_POST['id'];
		$name = $conn->real_escape_string($_POST['name']);
		$email = $conn->real_escape_string($_POST['email']);
		$website = $conn->real_escape_string($_POST['website']);
		$subject = $conn->real_escape_string($_POST['subject']);
		$message = $conn->real_escape_string($_POST['message']);
		
		$editComment=  $conn->query("update r_comment SET name='".$name."',email='".$email."',website='".$website."',subject='".$subject."',message='".$message."' where id_comment=".$id ) or die(mysqli_error());
		if($editComment)
		{
			$message = "<strong>Success!</strong> Comment Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'comments.php?response=success&message='.$message);
			
			} else {
				$message = "<strong>Warning!</strong> Comment Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'comments.php?response=danger&message='.$message);
			
		}
		}
		else if($_REQUEST['action']=='delete'){
			
			$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$row="DELETE FROM r_comment WHERE id_comment=".$messageid[$i];
			$result= $conn->query($row);
		}
		
		if($result)
		{
			$message = "<strong>Success!</strong> Comment Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'comments.php?response=success&message='.$message);
			
			} else {
				$message = "<strong>Warning!</strong> Comment Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'comments.php?response=danger&message='.$message);
			
		}
		
		}
?>
