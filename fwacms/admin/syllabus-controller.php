<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	
	if ($_POST['action'] == 'add') {
		// echo "<pre/>";
		// print_r($_POST);
		// print_r($_FILES);
		// exit;
		$name 				= $conn->real_escape_string($_POST['name']);		
		$description 		= $conn->real_escape_string($_POST['description']);			
		$status 			= $conn->real_escape_string($_POST['status']);	
		
		$addSyllabus = "INSERT INTO r_syllabus (name,description,status) VALUES ('" . $name . "','" . $description . "','" . $status . "')";
		
		if ($conn->query($addSyllabus) === TRUE) {
			$postid=$conn->insert_id;
			if($postid && $_FILES['pdf']['name']!=''){
				$temp = explode(".", $_FILES["pdf"]["name"]);
			    $pic = $postid. '.' . end($temp);
		
				$rows = $conn->query("update r_syllabus SET pdf = '".$pic."' where id_syllabus = ".$postid." ")or die(mysql_error());
				move_uploaded_file($_FILES["pdf"]["tmp_name"], "../syllabus/" . $pic);
				
				$message = "<strong>Success!</strong> syllabus Added Successfully.";
				header('location:'.SITE_ADMIN_URL.'syllabus.php?response=success&message='.$message);
				} else {
				header('location:'.SITE_ADMIN_URL.'syllabus.php?response=warning');
			}
		}
		
		} else if ($_POST['action'] == 'edit') {
		
		$id = (int)$_POST['id'];
		
		if (isset($_FILES['pdf']['name']) && $_FILES['pdf']['name']!='') {
			$removeimage = $_POST['preview_image']; 
			$Path2 =  "../syllabus/".$removeimage;
			unlink($Path2);
			
			$temp = explode(".", $_FILES["pdf"]["name"]);
			$pic = $id. '.' .end($temp);
			move_uploaded_file($_FILES["pdf"]["tmp_name"], "../syllabus/" . $pic);
			} else {
			$pic = $_POST['preview_image']; 
		}
		
		$name 				= $conn->real_escape_string($_POST['name']);		
		$description 		= $conn->real_escape_string($_POST['description']);			
		$status 			= $conn->real_escape_string($_POST['status']);	
		
		$editPost = $conn->query( "update r_syllabus SET name='".$name."',pdf='".$pic."',description='".$description."',status = '".$status."'	where id_syllabus='".$id."' ");
		$page = $_POST['page'];
		if ($editPost) {
			
			$message = "<strong>Success!</strong> Syllabus Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'syllabus.php?response=success&message='.$message.'&page=$page');
			} else {
			$message = "<strong>Warning!</strong> Syllabus Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'syllabus.php?response=danger&message='.$message.'&page=$page');
		}
	}
	else if ($_REQUEST['action'] == 'delete') {
		
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$result2= $conn->query("select pdf from r_syllabus where id_syllabus=".$messageid[$i])or die(mysqli_error());
			$image = $result2->fetch_assoc();
			$Path =  "../syllabus/".$image['pdf'];
			unlink($Path);
			/*Delete SEO URL Details*/
			$result= $conn->query("DELETE FROM r_syllabus WHERE id_syllabus=".$messageid[$i])or die(mysqli_error());
			
			
		} 
		
		if ($result) {
			$message = "<strong>Success!</strong> Syllabus Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'syllabus.php?response=success&message='.$message.'&page=$page');
			}else{
			$message = "<strong>Warning!</strong> Syllabus Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'syllabus.php?response=danger&message='.$message.'&page=$page');
		}
	}
?>

