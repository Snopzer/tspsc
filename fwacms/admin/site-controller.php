<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	if($_POST['action']=='add'){	

		$site_name = $conn->real_escape_string($_POST['site_name']);	
		$site_title = $conn->real_escape_string($_POST['site_title']);	
		$site_keywords = $conn->real_escape_string($_POST['site_keywords']);	
		$site_description = $conn->real_escape_string($_POST['site_description']);	
		$owner_name = $conn->real_escape_string($_POST['owner_name']);	
		$owner_email = $conn->real_escape_string($_POST['owner_email']);	
		$admin_page_limit = $conn->real_escape_string($_POST['admin_page_limit']);	
		$post_description_length	 = $conn->real_escape_string($_POST['post_description_length']);	
		$admin_mail = $conn->real_escape_string($_POST['admin_mail']);	
		$from_mail = $conn->real_escape_string($_POST['from_mail']);	
		$reply_to_mail = $conn->real_escape_string($_POST['reply_to_mail']);	
		$site_copy_rights = $conn->real_escape_string($_POST['site_copy_rights']); 
		$phone = $conn->real_escape_string($_POST['phone']); 
		$social_facebook_url = $conn->real_escape_string($_POST['social_facebook_url']); 
		$social_twitter_url = $conn->real_escape_string($_POST['social_twitter_url']); 
		$social_googleplus_url = $conn->real_escape_string($_POST['social_googleplus_url']); 
		$social_linkedin_url = $conn->real_escape_string($_POST['social_linkedin_url']); 
		$social_behance_url = $conn->real_escape_string($_POST['social_behance_url']); 
		$social_vimio_url = $conn->real_escape_string($_POST['social_vimio_url']); 
		$social_youtube_url = $conn->real_escape_string($_POST['social_youtube_url']); 
		$contact_mail = $conn->real_escape_string($_POST['contact_mail']); 
		$contact_phone = $conn->real_escape_string($_POST['contact_phone']); 
		$contact_address = $conn->real_escape_string($_POST['contact_address']); 
		$google_analytics = $conn->real_escape_string($_POST['google_analytics']); 
		
		$addSiteData = $conn->query("INSERT INTO `r_site_details` (`site_name`,site_title, `site_keywords`, `site_description`, `owner_name`, `owner_email`, `admin_page_limit`, `post_description_length`, `admin_mail`, `from_mail`, `reply_to_mail`, `site_copy_rights`, `phone`, `social_facebook_url`, `social_twitter_url`, `social_googleplus_url`, `social_linkedin_url`, `social_behance_url`, `social_vimio_url`, `social_youtube_url`,contact_mail,contact_phone,contact_address,google_analytics) VALUES ('".$site_name."','".$site_title."', '".$site_keywords."', '".$site_description."', '".$owner_name."', '".$owner_email."', '".$admin_page_limit."', '".$post_description_length."', '".$admin_mail."', '".$from_mail."', '".$reply_to_mail."', '".$site_copy_rights."', '".$phone."', '".$social_facebook_url ."', '".$social_twitter_url ."', '".$social_googleplus_url."', '".$social_linkedin_url."', '".$social_behance_url."', '".$social_vimio_url."', '".$social_youtube_url."','".$contact_mail."','".$contact_phone."','".$contact_address."','".$google_analytics."')") or die(mysqli_error());		
		if ($addSiteData) {
			$message = "<strong>Success!</strong> Site Added Successfully.";
			header('location:'.SITE_ADMIN_URL.'site.php?response=success&message='.$message);
			} else {
			$message = "<strong>Success!</strong> Site Not Added .Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'site.php?response=warning');
		}
		
	}
	else if($_REQUEST['action']=='edit'){
		$id=(int)$_POST['id']  ;  
		$site_name = $conn->real_escape_string($_POST['site_name']);	
		$site_title = $conn->real_escape_string($_POST['site_title']);	
		$site_keywords = $conn->real_escape_string($_POST['site_keywords']);	
		$site_description = $conn->real_escape_string($_POST['site_description']);	
		$owner_name = $conn->real_escape_string($_POST['owner_name']);	
		$owner_email = $conn->real_escape_string($_POST['owner_email']);	
		$admin_page_limit = $conn->real_escape_string($_POST['admin_page_limit']);	
		$post_description_length	 = $conn->real_escape_string($_POST['post_description_length']);	
		$admin_mail = $conn->real_escape_string($_POST['admin_mail']);	
		$from_mail = $conn->real_escape_string($_POST['from_mail']);	
		$reply_to_mail = $conn->real_escape_string($_POST['reply_to_mail']);	
		$site_copy_rights = $conn->real_escape_string($_POST['site_copy_rights']); 
		$phone = $conn->real_escape_string($_POST['phone']); 
		$social_facebook_url = $conn->real_escape_string($_POST['social_facebook_url']); 
		$social_twitter_url = $conn->real_escape_string($_POST['social_twitter_url']); 
		$social_googleplus_url = $conn->real_escape_string($_POST['social_googleplus_url']); 
		$social_linkedin_url = $conn->real_escape_string($_POST['social_linkedin_url']); 
		$social_behance_url = $conn->real_escape_string($_POST['social_behance_url']); 
		$social_vimio_url = $conn->real_escape_string($_POST['social_vimio_url']); 
		$social_youtube_url = $conn->real_escape_string($_POST['social_youtube_url']); 
		$contact_mail = $conn->real_escape_string($_POST['contact_mail']); 
		$contact_phone = $conn->real_escape_string($_POST['contact_phone']); 
		$contact_address = $conn->real_escape_string($_POST['contact_address']); 
		$google_analytics = $conn->real_escape_string($_POST['google_analytics']);
		
		
		$row = "UPDATE `r_site_details` SET `site_name` = '".$site_name."',`site_title` = '".$site_title."', `site_keywords` = '".$site_keywords."', `site_description` = '".$site_description."', `owner_name` = '".$owner_name."', `owner_email` = '".$owner_email."', `admin_page_limit` = '".$admin_page_limit."', `post_description_length` = '".$post_description_length."', `admin_mail` = '".$admin_mail."', `from_mail` = '".$from_mail."', `reply_to_mail` = '".$reply_to_mail."', `site_copy_rights` = '".$site_copy_rights."', `phone` = '".$phone ."', `social_facebook_url` = '".$social_facebook_url."', `social_twitter_url` = '".$social_twitter_url."', `social_googleplus_url` = '".$social_googleplus_url."', `social_linkedin_url` = '".$social_linkedin_url."', `social_behance_url` = '".$social_behance_url."', `social_vimio_url` = '".$social_vimio_url."', `social_youtube_url` = '".$social_youtube_url."',`contact_mail` = '".$contact_mail."',`contact_phone` = '".$contact_phone."',`contact_address` = '".$contact_address."',`google_analytics` = '".$google_analytics."' WHERE `id` = ".$id;
		
		/*echo  $row ;
		exit;*/
		
		$updateSiteData = $conn->query($row);
		
		if ($updateSiteData) {
			$message = "<strong>Success!</strong> Site Modified Successfully.";
			header('location:'.SITE_ADMIN_URL.'site.php?response=success&message='.$message);
			
			} else {
			$message = "<strong>Warning!</strong> Site Not Modified.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'site.php?response=danger&message='.$message);
			
		}
		
	}
	else if($_REQUEST['action']=='delete'){
		$messageid=explode(",",$_REQUEST["chkdelids"]);
		$count=count($messageid);
		for($i=0;$i<$count;$i++)
		{
			$deleteSite=$conn->query("DELETE FROM r_site_details WHERE id=".$messageid[$i]);
			}if ($deleteSite) {
			$message = "<strong>Success!</strong> Site Deleted Successfully.";
			header('location:'.SITE_ADMIN_URL.'site.php?response=success&message='.$message);
			
			} else {
			$message = "<strong>Warning!</strong> Site Not Deleted.Please check Carefully..";
			header('location:'.SITE_ADMIN_URL.'site.php?response=danger&message='.$message);
			
		}
	}
?>
