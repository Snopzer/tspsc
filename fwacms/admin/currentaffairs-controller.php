<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	
	if ($_POST['action'] == 'add') {
		$title 				= $conn->real_escape_string($_POST['title']);		
		$description 		= $conn->real_escape_string($_POST['description']);		
		//$description 		= addslashes($_POST['description']);		
//		$short_description 	= $conn->real_escape_string($_POST['short_description']);		
//		$category 			= $conn->real_escape_string($_POST['category']);		
		$status 			= $conn->real_escape_string($_POST['status']);		
//		$category 			= $conn->real_escape_string($_POST['category']);		
//		$seo_url 			= $conn->real_escape_string($_POST['seo_url']);		
//		$meta_title 		= $conn->real_escape_string($_POST['meta_title']);		
//		$meta_keywords		= $conn->real_escape_string($_POST['meta_keywords']);		
//		$meta_description 	= $conn->real_escape_string($_POST['meta_description']);
//		$source 			= $conn->real_escape_string($_POST['source']);
		$image_source 		= $conn->real_escape_string($_POST['image_source']);
		$user=$_SESSION['id'];
//		$date_added = date('Y-m-d h:i:s'); id_category
		
                  $addPost = "INSERT INTO r_current_affairs (title,description,status) VALUES ('" . $title . "','" . $description . "','" . $status . "')";
		
		if ($conn->query($addPost) === TRUE) {
			$postid=$conn->insert_id;
//			$seo_url  = strtolower(preg_replace('/\s+/', '-', $seo_url));
//			$conn->query("INSERT INTO  `r_seo_url` (seo_url ,`id_post`) VALUES (  '".$seo_url."',  ".$postid.")");
			if($postid && $_FILES['photo']['name']!=''){
				$temp = explode(".", $_FILES["photo"]["name"]);
			    $pic =$title. '' .$postid. '.' . end($temp);
				$rows = $conn->query("update r_current_affairs SET image='$pic' where id_current_affairs=$postid")or die(mysql_error());
				move_uploaded_file($_FILES["photo"]["tmp_name"], "../images/currentaffairs/" . $pic);
			}
			$message = "<strong>Success!</strong> Current Affair Added Successfully.";
			header('location:'.SITE_ADMIN_URL.'currentaffairs.php?response=success&message='.$message);
			} else {
			header('location:'.SITE_ADMIN_URL.'currentaffairs.php?response=warning');
		}
		
		} else if ($_POST['action'] == 'edit') {
		
		$id = (int)$_POST['id'];
		$title = $conn->real_escape_string($_POST['title']);	
		if (isset($_FILES['photo']['name']) && $_FILES['photo']['name']!='') {
			$removeimage = $_POST['preview_image']; 
			$Path2 =  "../images/currentaffairs/".$removeimage;
			unlink($Path2);
//			$seo_url 			= $_POST['seo_url'];	
			$temp = explode(".", $_FILES["photo"]["name"]);
			$pic =$title. '' .$id. '.' .end($temp);
			move_uploaded_file($_FILES["photo"]["tmp_name"], "../images/currentaffairs/" . $pic);
			} else {
			$pic = $_POST['preview_image']; 
		}
		
			
		$description 		= $conn->real_escape_string($_POST['description']);
		//$description 		= addslashes($_POST['description']);
//		$short_description 	= $conn->real_escape_string($_POST['short_description']);
//		$category 			= $conn->real_escape_string($_POST['category']);		
		$status 			= $conn->real_escape_string($_POST['status']);		
//		$category 			= $conn->real_escape_string($_POST['category']);		
//		$seo_url 			= $conn->real_escape_string($_POST['seo_url']);		
//		$meta_title 		= $conn->real_escape_string($_POST['meta_title']);		
//		$meta_keywords		= $conn->real_escape_string($_POST['meta_keywords']);		
//		$meta_description 	= $conn->real_escape_string($_POST['meta_description']);
//		$source 			= $conn->real_escape_string($_POST['source']);
//		$image_source 		= $conn->real_escape_string($_POST['image_source']);
		
		$editPost = $conn->query( "update r_current_affairs SET title='".$title."',image='".$pic."',description='".$description."', status = '".$status."'	where id_current_affairs='".$id."' ");
		$page = $_POST['page'];
		if ($editPost) {
		
			
			
			$message = "<strong>Success!</strong> Current Affairs Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'currentaffairs.php?response=success&message='.$message.'&page=$page');
			} else {
			$message = "<strong>Warning!</strong> Current Affairs Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'currentaffairs.php?response=danger&message='.$message.'&page=$page');
		}
	}
	else if ($_REQUEST['action'] == 'delete') {
		
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$result2= $conn->query("select image from  r_current_affairs where id_current_affairs=".$messageid[$i])or die(mysqli_error());
			$image = $result2->fetch_assoc();
			$Path =  "../images/currentaffairs/".$image['image'];
			unlink($Path);
			/*Delete SEO URL Details*/
//			$result= $conn->query("DELETE FROM r_seo_url WHERE id_post=".$messageid[$i])or die(mysqli_error());
			$result= $conn->query("DELETE FROM r_current_affairs WHERE id_current_affairs=".$messageid[$i])or die(mysqli_error());
			
			
		} 
		
		if ($result) {
			$message = "<strong>Success!</strong> Current Affair Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'currentaffairs.php?response=success&message='.$message.'&page=$page');
			}else{
			$message = "<strong>Warning!</strong> Current Affair Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'currentaffairs.php?response=danger&message='.$message.'&page=$page');
		}
	}
?>

