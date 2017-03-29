<?php
	
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if(!isset($_GET['msg'])){
		if (isset($_SESSION['id'])) {
			header('location:home.php');
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo  SITE_NAME;?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="<?php echo  SITE_KEYWORDS;?>" />
		<meta name="description" content="<?php echo  SITE_DESCRIPTION;?>" />
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
		<!-- Custom Theme files -->
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link href="css/font-awesome.css" rel="stylesheet"> 
		<script src="js/jquery.min.js"> </script>
		<script src="js/bootstrap.min.js"> </script>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>
	</head>
	<body>
		<div class="login">
			<h1><a href="home.php"><?php echo SITE_NAME;?></a></h1>
			<div class="login-bottom">
				<h2>Sign Up</h2>
				<div class="">
					<div id="showMessageDiv" style="display:none;" class="alert alert-danger">
						<span id="showMessage" class="warning">
							<?php if(isset($_SESSION['message'])){
								echo $_SESSION['message'];
							}?>	
						</span>
					</div>	
					<div id="showSuccessMessageDiv" <?php if(!isset($_SESSION['message'])){?> style="display:none;"<?php }?> class="alert alert-success">
						<span id="showSuccessMessage" >	</span>
					</div>	
					<form id="SignUpForm">		
						<div class="login-mail" id="mail_field">
							<input type="text" placeholder="Email" name="email"  id="email">
							<i class="fa fa-envelope"></i>
						</div>
						<div class="login-mail" id="password_field">
							<input type="password" placeholder="Password" name="password" id="password" >
							<i class="fa fa-lock"></i>
						</div>
						<div class="login-mail" id="cpassword_field">
							<input type="password" placeholder="Repeated password" id="cpassword" >
							<i class="fa fa-lock"></i>
						</div>
						<a class="news-letter" id="checkbox" >
							<input  class="input-checkbox" type="checkbox" name="checkbox" id="terms" value="1" >
							<label class="input-label" >I agree with the terms</label>
						</a>
						<div class="text-center">
							
							<button id="signup" class="btn btn-warning submit-button" >CREATE  ACCOUNT</button><br/>
							<a href="index.php">Already Have an Account Login Now</a>
						</div>
					</form>
					
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<div class="copy-right"><p><?php echo SITE_COPY_RIGHTS;?></p></div>
		<script src="js/jquery.nicescroll.js"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>
<script type="text/javascript" language="JavaScript">
	
	$("#signup").click(function()
	{
		$("#showMessageDiv").hide();
		$("#showMessage").html('');
		var email = $("#email").val();
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if((email=='') || (!regex.test(email)))
		{
			$("#mail_field").css({"border-style": "solid", "border-color": "red"});
			$("#showMessageDiv").show();
			$("#showMessage").html('Please Enter Valid Email');
			$("#email").focus();
			return false;
			}else{
			$("#mail_field").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		
		if($("#password").val()=='')
		{
			$("#password_field").css({"border-style": "solid", "border-color": "red" });
			$("#showMessageDiv").show();
			$("#showMessage").html('Please Enter Valid Password');
			$("#password").focus();
			return false;
		} 
		else{
			$("#password_field").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		var password = $("#password").val();
		var cpassword = $("#cpassword").val();
		if( (cpassword=='') || password!=cpassword)
		{
			$("#cpassword_field").css({"border-style": "solid", "border-color": "red" });
			$("#showMessageDiv").hide();
			$("#showMessage").html('Passwords are Not matching');
			$("#cpassword").focus();
			return false;
		} 
		else{
			$("#cpassword_field").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		if($("#terms").prop("checked")==false)
		{
			$("#check_field").css({"border-style": "solid", "border-color": "red" });
			$("#showMessageDiv").show();
			$("#showMessage").html('Please Check Terms and Conditions');
			$(".terms").focus();
			return false;
		} 
		else{
			$("#check_field").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		
		$.ajax({
			url: "controller.php",
			method: "POST",
			data: { singupData : $("#SignUpForm").serialize(), 'action':'signup'},
			dataType: "json",
			success: function (response) {
				if(response["success"]==true)
				{
					$("#showMessageDiv").hide();
					$("#showSuccessMessageDiv").show();
					$("#showSuccessMessage").html(response["message"]);
					}else{
					$("#showMessageDiv").show();
					$("#showMessage").html(response["message"]);
				}
				
			},
			error: function (request, status, error) {
				$("#showMessageDiv").show();
				$("#showMessage").html("OOPS! Something Went Wrong Please Try After Sometime!");
			}
		});
		return false;
		
		
	});
	
</script>



