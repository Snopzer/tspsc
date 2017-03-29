<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	if($_POST['action']=='add'){
	
		$title 				=  $conn->real_escape_string($_POST['title']);	
		$meta_description 	= $conn->real_escape_string($_POST['meta_description']);	
		$meta_keywords 		= $conn->real_escape_string($_POST['meta_keywords']);	
		$page_heading 		= $conn->real_escape_string($_POST['page_heading']);	
		//$page_description 	= $conn->real_escape_string($_POST['page_description']);	
		$page_description 	= addslashes($_POST['page_description']);	
		$seo_url 			= $conn->real_escape_string($_POST['seo_url']);	
		
		
        $addPage=  $conn->query("INSERT INTO r_page (title,meta_description,meta_keywords,page_heading,page_description) VALUES ('".$title."','".$meta_description."','".$meta_keywords."','".$page_heading."','".$page_description."')") or die(mysqli_error());
		
        if($addPage){
			$pageid=$conn->insert_id;
			$conn->query("INSERT INTO  `r_seo_url` (seo_url ,`id_page`) VALUES (  '".$seo_url."',  ".$pageid.")");
            $message = "<strong>Success!</strong> Page Added Successfully.";
			header('location:'.SITE_ADMIN_URL.'pages.php?response=success&message='.$message);
			} else {
				$message = "<strong>Success!</strong> Page Not Added .Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'pages.php?response=warning');
		}
		}
		else if($_POST['action']=='edit'){
		
		$id=(int)$_POST['id'];
		$page=$_POST['page'];
       
		$title 				= $conn->real_escape_string($_POST['title']);	
		$meta_description 	= $conn->real_escape_string($_POST['meta_description']);	
		$meta_keywords 		= $conn->real_escape_string($_POST['meta_keywords']);	
		$page_heading 		= $conn->real_escape_string($_POST['page_heading']);	
		//$page_description	= $conn->real_escape_string($_POST['page_description']);	
		$page_description 	= addslashes($_POST['page_description']);	
		$seo_url 			= $conn->real_escape_string($_POST['seo_url']);	
		
		$editPage=$conn->query("update r_page SET title='$title',meta_description='$meta_description',meta_keywords='$meta_keywords',page_heading='$page_heading',page_description='$page_description' where id_page='$id' ");
		if($editPage)
		{
			$seo_url  			= preg_replace('/\s+/', '-', $seo_url);
			$seo_url 			= strtolower($seo_url);
			$seoCheck = $conn->query("UPDATE  `r_seo_url` SET `seo_url`='".$seo_url."' where id_page=".$id);
			if($conn->affected_rows!=1)
			{
				$conn->query("INSERT INTO  `r_seo_url` (seo_url ,`id_page`) VALUES (  '".$seo_url."',  ".$id.")");
			}
			$message = "<strong>Success!</strong> Page Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'pages.php?response=success&message='.$message);
			
			} else {
				$message = "<strong>Warning!</strong> Page Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'pages.php?response=danger&message='.$message);
			
		}
	}
	else if($_REQUEST['action']=='delete'){
		
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$conn->query("DELETE FROM r_seo_url WHERE id_page=".$messageid[$i]);
			$result= $conn->query("DELETE FROM r_page WHERE id_page=".$messageid[$i]);
		}
		if($result)
		{
			$message = "<strong>Success!</strong> Page Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'pages.php?response=success&message='.$message);
			
			} else {
				$message = "<strong>Warning!</strong> Page Not Deleted. Please check Carefully.";
			header('location:'.SITE_ADMIN_URL.'pages.php?response=danger&message='.$message);
			
		}
	} 
?>
