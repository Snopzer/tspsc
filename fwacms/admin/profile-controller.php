<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	if (isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '') {
		$pic = $_FILES['photo']['name'];
		$path = "../images/user/" . $_FILES['photo']['name'];
		if (copy($_FILES['photo']['tmp_name'], $path)) {
			echo "The file " . basename($_FILES['uploadedfile']['name']) . " has been uploaded, and your information has been added to the directory";
			} else {
			echo "Sorry, there was a problem uploading your file.";
		}
		} else {
		$pic = $_POST['preview_image'];
	}
	$name 		= $conn->real_escape_string($_POST['name']);
	$department = $conn->real_escape_string($_POST['department']);
	$email 		= $conn->real_escape_string($_POST['email']);
	$skills 	= $conn->real_escape_string($_POST['skills']);
	$phone 		= $conn->real_escape_string($_POST['phone']);
	$id_country = $conn->real_escape_string($_POST['country']);
	
	$row = "update r_user SET  name='$name',image='$pic',department='$department',email='$email',phone='$phone',skills='$skills',id_country='$id_country' where id_user=" . $_SESSION['id'];
	$result = $conn->query($row) or die(mysqli_error());
	
	if ($result) {
		$_SESSION['name'] = $name;
		if(!empty($pic)){
			$_SESSION['image'] = $pic;
		}
		header('location:'.SITE_ADMIN_URL.'profile.php');
	} 
	else 
	{
		header('location:'.SITE_ADMIN_URL.'profile.php');
	}
?>