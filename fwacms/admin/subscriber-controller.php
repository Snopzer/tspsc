<?php
	ob_start(); 
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	
	if($_POST['action']=='add'){
		$email = $conn->real_escape_string($_POST['email']);	
		$insert=  $conn->query("INSERT INTO r_subscriber (email) VALUES ('".$email."')") or die(mysql_error());
		
		if($insert){
			$message = "<strong>Success!</strong> Subscriber Added Successfully.";
			header('location:subscriber.php?response=success&message='.$message);
			} else {
			$message = "<strong>Success!</strong> Subscriber Not Added .Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'subscriber.php?response=warning');
		}
	}
	else if($_POST['action']=='edit')
	{
		$id = (int)$_POST['id'];
		
		$email = $conn->real_escape_string($_POST['email']);
		$id=$_POST['id'];
		$page=$_POST['page'];
		$row="update r_subscriber SET email='$email' where id_subscriber='$id' ";
		$result=  $conn->query($row);
		if($result)
		{
			$message = "<strong>Success!</strong> Subscriber Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'subscriber.php?response=success&message='.$message);
			
			} else {
			$message = "<strong>Warning!</strong> Subscriber Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'subscriber.php?response=danger&message='.$message);
			
		}
		
	}
	else if($_REQUEST['action']=='delete'){
		
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$row="DELETE FROM r_subscriber WHERE id_subscriber=".$messageid[$i];
			$result= $conn->query($row);
		}
		if($result)
		{
			
			$message = "<strong>Success!</strong> Subscriber Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'subscriber.php?response=success&message='.$message);
			
			} else {
			$message = "<strong>Warning!</strong> Subscriber Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'subscriber.php?response=danger&message='.$message);
			
		}
	}
?>
