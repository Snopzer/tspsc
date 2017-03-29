<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	
	if ($_POST['action'] == 'add') {
		$title 				= $conn->real_escape_string($_POST['title']);		
		$description 	= $conn->real_escape_string($_POST['description']);		
		$commencement_date 			= $conn->real_escape_string($_POST['commencement_date']);		
		$status 			= $conn->real_escape_string($_POST['status']);		
		$last_date 			= $conn->real_escape_string($_POST['last_date']);		
		$exam_date 			= $conn->real_escape_string($_POST['exam_date']);		
		
		$user=$_SESSION['id'];
		$date_added = date('Y-m-d h:i:s'); 
		/*echo "INSERT INTO r_notification (name,notification,commencement_date,last_date,exam_date,status) VALUES ('" . $title . "','" . $description . "','" . $commencement_date . "','" . $last_date . "','" . $exam_date . "','" . $status . "')";
                exit;*/
                
		$addPost = "INSERT INTO r_notification (name,notification,commencement_date,last_date,exam_date,status) VALUES ('" . $title . "','" . $description . "','" . $commencement_date . "','" . $last_date . "','" . $exam_date . "','" . $status . "')";
		
		if ($conn->query($addPost) === TRUE) {
			$postid=$conn->insert_id;
			
			
			if($postid && $_FILES['photo']['name']!=''){
				$temp = explode(".", $_FILES["photo"]["name"]);
			    $pic =$title. '' .$postid. '.' . end($temp);
				$rows = $conn->query("update r_notification SET pdf='$pic' where id_notification=$postid")or die(mysql_error());
				move_uploaded_file($_FILES["photo"]["tmp_name"], "../images/notification/" . $pic);
			}
			$message = "<strong>Success!</strong> Notification Added Successfully.";
			header('location:'.SITE_ADMIN_URL.'notification.php?response=success&message='.$message);
			} else {
                            $message = "<strong>Warning!</strong> Notification Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'notification.php?response=warning');
		}
		
		} else if ($_POST['action'] == 'edit') {
		
		$id = (int)$_POST['id'];
		$title 				= $conn->real_escape_string($_POST['title']);
		if (isset($_FILES['photo']['name']) && $_FILES['photo']['name']!='') {
			$removeimage = $_POST['preview_image']; 
			$Path2 =  "../images/notification/".$removeimage;
			unlink($Path2);
			
			$temp = explode(".", $_FILES["photo"]["name"]);
			$pic =$title. '' .$id. '.' .end($temp);
			move_uploaded_file($_FILES["photo"]["tmp_name"], "../images/notification/" . $pic);
			} else {
			$pic = $_POST['preview_image']; 
		}
		

		$description 	= $conn->real_escape_string($_POST['description']);		
		$commencement_date 			= $conn->real_escape_string($_POST['commencement_date']);		
		$status 			= $conn->real_escape_string($_POST['status']);		
		$last_date 			= $conn->real_escape_string($_POST['last_date']);		
		$exam_date 			= $conn->real_escape_string($_POST['exam_date']);		
		
		$editPost = $conn->query( "update r_notification SET name='".$title."',notification='".$description."',commencement_date='".$commencement_date."',last_date='".$last_date."',exam_date='".$exam_date."',pdf= '".$pic."',status= '".$status."'	where id_notification='".$id."' ");
		$page = $_POST['page'];
		if ($editPost) {
		
			$message = "<strong>Success!</strong> Notification Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'notification.php?response=success&message='.$message.'&page=$page');
			} else {
			$message = "<strong>Warning!</strong> Notification Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'notification.php?response=danger&message='.$message.'&page=$page');
		}
	}
	else if ($_REQUEST['action'] == 'delete') {
		
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$result2= $conn->query("select pdf from r_notification where id_notification=".$messageid[$i])or die(mysqli_error());
			$image = $result2->fetch_assoc();
			$Path =  "../images/notification/".$image['image'];
			unlink($Path);
			
			
			$result= $conn->query("DELETE FROM r_notification WHERE id_notification=".$messageid[$i])or die(mysqli_error());
			
			
		} 
		
		if ($result) {
			$message = "<strong>Success!</strong> Notification Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'notification.php?response=success&message='.$message.'&page=$page');
			}else{
			$message = "<strong>Warning!</strong> Notification Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'notification.php?response=danger&message='.$message.'&page=$page');
		}
	}
?>

