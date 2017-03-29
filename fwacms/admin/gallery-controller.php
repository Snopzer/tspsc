<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	
	if (isset($_GET['action']) & $_GET['action'] == 'add') {
		$name = mysqli_real_escape_string($_POST['image_name']);
		$pic = ($_FILES['photo']['name']);
		$addPic = $conn->query("insert into r_image (image,image_name) VALUES ('".$pic."','".$name."') ")or die(mysql_error());
		$path = "../images/gallery/" . $_FILES['photo']['name'];
		if (copy($_FILES['photo']['tmp_name'], $path)) {
			header('location:'.SITE_ADMIN_URL.'gallery.php');
			} else {
			echo "Sorry, there was a problem uploading your file.";
		}
	}
	
    if (isset($_GET['action']) & $_GET['action'] == "delete") {
        $id = $_GET['id'];
        $res = $conn->query("SELECT image FROM r_image WHERE id_image='$id'");
		
        $row = $res->fetch_assoc();
	    $deltePic = $conn->query("DELETE FROM r_image WHERE id_image='$id'");
        if ($deltePic) {
            header('location:'.SITE_ADMIN_URL.'gallery.php');
		}
		else {
			echo "cannot delete record";
		}
	}
?>
