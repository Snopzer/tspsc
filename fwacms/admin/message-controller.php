<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	if($_POST['action']=='add'){
	
		$name 		= $conn->real_escape_string($_POST['name']);	
		$subject 	= $conn->real_escape_string($_POST['subject']);	
		$email 		= $conn->real_escape_string($_POST['email']);	
		$message 	= $conn->real_escape_string($_POST['message']);	
		$date_created = date('Y-m-d h:i:s'); 
		
		$addMessage = $conn->query("INSERT INTO r_message (name,subject,email,message,date_created) VALUES ('" . $name . "','" . $subject . "','" . $email . "','" . $message . "','" . $date_created . "')") or die(mysqli_error());
		if ($addMessage) {
			$message = "<strong>Success!</strong> Message Added Successfully.";
			header('location:'.SITE_ADMIN_URL.'message.php?response=success&message='.$message);
			} else {
				$message = "<strong>Success!</strong> Message Not Added .Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'message.php?response=warning');
		}
	}else if($_POST['action']=='edit'){
		$id = $_POST['id'];
		$name 		= $conn->real_escape_string($_POST['name']);	
		$subject 	= $conn->real_escape_string($_POST['subject']);	
		$email 		= $conn->real_escape_string($_POST['email']);	
		$message 	= $conn->real_escape_string($_POST['message']);	
		
		$page=$_POST['page'];
		$editMessage = $conn->query("update r_message SET name='".$name."',subject='".$subject."',email='".$email."',message='".$message."' where id_message='".$id."' ");
		if ($editMessage) 
		{
			$message = "<strong>Success!</strong> Message Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'message.php?response=success&message='.$message);
			} else {
				$message = "<strong>Success!</strong> Message Not Modified .Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'message.php?response=warning');
		}
	}
		else if($_REQUEST['action']=='delete'){
			
			$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$deletemessage=$conn->query("DELETE FROM r_message WHERE id_message=".$messageid[$i]);
		}
			if ($deletemessage) 
		{
			$message = "<strong>Success!</strong> Message Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'message.php?response=success&message='.$message);
			} else {
				$message = "<strong>Success!</strong> Message Not Deleted.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'message.php?response=warning');
		}
	}
?>
