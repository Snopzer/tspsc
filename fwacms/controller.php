<?php

	ob_start();
	include_once ('config.php');
	
	if($_POST['action']=='newsletter'){
	parse_str($_POST['subscribeData'], $Subscribe);
		$email=$Subscribe['email'];
		
		$checkemail=$conn->query("SELECT * FROM r_subscriber where email='".$email."' ")or die(mysql_error());
		$emailCount=mysqli_num_rows($checkemail);
		if($emailCount >=1){
			$response['message'] = "Yoy Are Already Subscribed to our channel.";
			$response['success'] = true;
		}
		if($emailCount ==0){
		$subquery=$conn->query("INSERT INTO r_subscriber (email)values('".$email."')")or die(
		mysql_error());
		if($subquery){
			$response['message'] = "Thank you for subscribing our channel.";
			$response['success'] = true;
			} else {
			$response['message'] = "There is a problem in subscription.";
			$response['success'] = false;
		}}
		echo json_encode($response);
		exit;
	}
		
	if($_POST['action']=='contact')
	{ 	
		$subject=$conn->real_escape_string($_POST['subject']);
		$name=$conn->real_escape_string($_POST['name']);
		$email=$conn->real_escape_string($_POST['email']);
		$message=$conn->real_escape_string($_POST['message']);
		$contacts = array(
		"fareed543@gmail.com",
		" Mohammadwaheed567@gmail.com",
		" abidali70722@gmail.com"
		);
		foreach($contacts as $contact) {
			$to = $contact;
			$subject2 = $subject;
			$txt = $message;
			$headers = 'From:$email' . "\r\n" .
			$mail=mail($to,$subject2,$txt,$headers);
		}
		$query=$conn->query("INSERT INTO r_message (subject,name,email,message)values('".$subject."','".$name."','".$email."','".$message."')")or die(mysql_error());
		if($mail){
			$response['message'] = "Your message sent successfully..! we will respond you shortly";
			$response['success'] = true;
		}
		else {
			$response['message'] = "There is a problem in sending please try again.";
			$response['success'] = false;
		}
		echo json_encode($response);
		exit;
	}
		
	if(isset($_POST['type'])&&($_POST['type']=='save')){
	
		parse_str($_POST['CommentData'], $CommentData);
		
		$id=(int)$CommentData['id'];
		$name=$CommentData['name'];
        $email=$CommentData['email'];
        $website=$CommentData['website'];
       
        $message=$CommentData['message'];
		
        $row="insert into r_comment (name,email,message,website,id_post) VALUES ('".$name."','".$email."','".$message."','".$website."',".$id.")";
		
        $result=$conn->query($row)or die(mysql_error());
		if($result){
				$response['message'] = "Comment Added Successfully.";
				$response['success'] = true;
				}else{
				$response['message'] = "OOPS! Something Went Wrong Please Try After Sometime!";
				$response['success'] = false;
			}
		echo json_encode($response);
		exit;
	}
?>