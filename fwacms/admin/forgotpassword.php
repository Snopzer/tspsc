<?php
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (isset($_SESSION['id'])) {
		header('location:home.php');
	}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo SITE_NAME;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="<?php echo SITE_KEYWORDS;?>" />
        <meta name="keywords" content="<?php echo SITE_DESCRIPTION;?>" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <link href="css/font-awesome.css" rel="stylesheet"> 
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
	</head>
    <body>
        <div class="login">
            <h1><a href="home.php"><?php echo SITE_NAME;?></a></h1>
            <div class="login-bottom">
                <h2>Forgot Password</h2>
				
                <?php
					if (isset($_GET['msg'])) {
					?>
                    <p style="color:red"><?php echo  $_GET['msg'];?></p>
                    <?php
					}
					?>  <div class="">
					<form action="controller.php?type=forgetpassword" method="POST" >
                        <div class="login-mail" id="mail_field">
                            <input type="text" id="email" name="email" placeholder="Email" >
                            <i class="fa fa-envelope"></i>
						</div>
						<div class="login-do">
							<label class="hvr-shutter-in-horizontal login-sub">
								<input type="submit" id="forgetpassword" value="Submit">
							</label>
						</div>
					</form>
				</div>
				<div class="text-center" >
				<a href="index.php">Login </a>
				<div>
				<div class="clearfix"> </div>
				
			</div>
		</div>
        <div class="copy-right"><p><?php echo SITE_COPY_RIGHTS;?></p></div>  
        <script src="js/jquery.nicescroll.js"></script>
        <script src="js/scripts.js"></script>
	</body>
</html>

<script>
    $("#forgetpassword").click(function ()
    {
        var email = $("#email").val();
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if ((email == '') || (!regex.test(email)))
        {
            $("#mail_field").css({"border-style": "solid", "border-color": "red"});
            $("#email").focus();
            return false;
			
		}
	});
</script>