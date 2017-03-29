<!DOCTYPE HTML>
<html>
    <head>
        <title>Install FWACMS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="install/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
        <link href="install/css/style.css" rel='stylesheet' type='text/css' />
        <link href="install/css/font-awesome.css" rel="stylesheet"> 
        <script src="install/js/jquery.min.js"></script>
        <script src="install/js/bootstrap.min.js"></script>
	</head>
    <body>
        <div class="login">
            <h1><a href="index.php">Install FWACMS</a></h1>
            <div class="login-bottom">
				
				<span id="showMessage"> </span>
                <span id="installSuccess"></span>
				<div id="setupdone" style="display:none;">
				Visit Admin Panel : <a href="" id="adminURL">Manage Site</a><br />
				Your Blog : <a href="" id="siteURL">Visit Site</a>
				</div>
				
				<h2>Warning</h2>
				
				<div class="">
						<div class="login-mail" id="database_host">
                            <?php echo $_GET['warning'];?>
						</div>
						<a href="install.php"><span id="TestConnection" class="btn btn-success  right" >Install FWACMS</span></a>
					</div>
				<div class="clearfix"> </div>
				
			</div>
		</div>
        <div class="copy-right"><p> &copy; 2016 FWACMS All Rights Reserved </p></div>  
        <script src="js/jquery.nicescroll.js"></script>
        <script src="js/scripts.js"></script>
	</body>
</html>