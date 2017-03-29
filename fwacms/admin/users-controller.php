<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	if($_POST['action']=='add'){		
		$name = ($_POST['name']);	
		$email = $conn->real_escape_string($_POST['email']);	
		$password = $conn->real_escape_string($_POST['password']);	
		$phone = $conn->real_escape_string($_POST['phone']);	
		$department = $conn->real_escape_string($_POST['department']);	
		$skills = $conn->real_escape_string($_POST['skills']);	
		$name = $conn->real_escape_string($_POST['name']);	
		$country = $conn->real_escape_string($_POST['country']);	
		$userrole = $conn->real_escape_string($_POST['userrole']);	
		$status = $conn->real_escape_string($_POST['status']);	
		$seo_url = $conn->real_escape_string($_POST['seo_url']);
		
		$checkEmail=  $conn->query("SELECT * FROM r_user where email='".$email."' ") or die(mysqli_error());
		$count=mysqli_num_rows($checkEmail);
		if($count==1)
		{
			$message = "<strong>Warning!</strong> Email Already Exist";
			header('location:'.SITE_ADMIN_URL.'users.php?response=warning');
		}
		else
		{                   
			$insert = $conn->query("INSERT INTO r_user (name,email,phone,department,password,skills,id_country,id_user_role,status) VALUES ('" . $name . "','" . $email . "','" . $phone . "','" . $department . "','" . md5($password) . "','" . $skills . "','" . $country . "','" . $userrole . "','" . $status . "')") or die(mysqli_error());	
			$userid=$conn->insert_id;
			if ($insert) {
				$seo_url  = strtolower(preg_replace('/\s+/', '-', $seo_url));
				echo "INSERT INTO  `r_seo_url` (seo_url ,`id_user`) VALUES (  '".$seo_url."',  ".$userid.")";
				$conn->query("INSERT INTO  `r_seo_url` (seo_url ,`id_user`) VALUES (  '".$seo_url."',  ".$userid.")");
				$message = "<strong>Success!</strong> User Added Successfully.";
				header('location:'.SITE_ADMIN_URL.'users.php?response=success&message='.$message);
			}
			else {
				$message = "<strong>Warning!</strong> User Not Added.Please check Carefully..";
				header('location:'.SITE_ADMIN_URL.'users.php?response=warning');
			}
		}
	}
	else if($_REQUEST['action']=='edit'){
		$id=(int)$_POST['id']  ;   
		$name = $conn->real_escape_string($_POST['name']);	
		$phone = $conn->real_escape_string($_POST['phone']);	
		$department = $conn->real_escape_string($_POST['department']);	
		$skills = $conn->real_escape_string($_POST['skills']);	
		$country = $conn->real_escape_string($_POST['country']);	
		$userrole = $conn->real_escape_string($_POST['userrole']);	
		$status = $conn->real_escape_string($_POST['status']);	
		$page = $conn->real_escape_string($_POST['page']);	
		$seo_url = $conn->real_escape_string($_POST['seo_url']);	
		$email = $conn->real_escape_string($_POST['email']);	
		
		
		$result2 = $conn->query( " update r_user SET email='".$email."',name='".$name."',phone='".$phone."',department='".$department."',skills = '".$skills."',	id_country = '".$country."',	id_user_role = '".$userrole."', 	status ='" . $status . "'	where id_user='".$id."' ") or die(mysqli_error());
		
		if(isset($_POST['password']) && $_POST['password'])
		{
			$password = $_POST['password'];
			$updatePasswordQuery = "update r_user SET password='".md5($password)."' where id_user='$id' ";
			$conn->query($updatePasswordQuery);
		}
		
		if ($result2) {
			$seo_url  = strtolower(preg_replace('/\s+/', '-', $seo_url));
			$seoCheck = $conn->query("UPDATE  `r_seo_url` SET `seo_url`='".$seo_url."' where id_user=".$id);
			if($conn->affected_rows!=1)
			{
				$conn->query("INSERT INTO  `r_seo_url` (seo_url ,`id_user`) VALUES (  '".$seo_url."',  ".$id.")");
			}
			$message = "<strong>Success!</strong> User Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'users.php?response=success&message='.$message.'&page=$page');
			} else {
			$message = "<strong>Warning!</strong> User Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'users.php?response=danger&message='.$message.'&page=$page');
		}
	}
	else if($_REQUEST['action']=='delete'){
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			//Delete SEO URL Details
			$conn->query("DELETE FROM r_seo_url WHERE id_user=".$messageid[$i]);
			
			$row="DELETE FROM r_user WHERE id_user=".$messageid[$i];
			$result= $conn->query($row);
		}
		
		
		if ($result) {
			$message = "<strong>Success!</strong> User Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'users.php?response=success&message='.$message.'&page=$page');
			}else{
			$message = "<strong>Warning!</strong> User Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'users.php?response=danger&message='.$message.'&page=$page');
		}
	}
?>
