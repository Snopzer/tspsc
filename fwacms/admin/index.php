<?php
session_start();
include_once('../config.php');

if (!file_exists('../config.php')) {
    header('Location:../install.php');
}


include_once('../parameter.php');
if (isset($_SESSION['id'])) {
    header('location:home.php');
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo SITE_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />
        <meta name="keywords" content="<?php echo SITE_DESCRIPTION; ?>" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <link href="css/font-awesome.css" rel="stylesheet"> 
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="login">
            <h1><a href="home.php"><?php echo SITE_NAME; ?></a></h1>
            <div class="login-bottom">
                <h2>Login</h2>
                <div id="showMessageDiv" style="display:none;" class="alert alert-danger">
                    <span id="showMessage" class="warning">
                    </span>

                </div>
                <div id="showSuccessMessageDiv" <?php if (!isset($_SESSION['message'])) { ?> style="display:none;"<?php } ?> class="alert alert-success">
                    <span id="showSuccessMessage" >	</span>
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                    ?>	
                </div>	
                <div class="">
                    <form id="loginForm" >
                        <div class="login-mail" id="mail_field">
                            <input type="text" id="email" name="email" placeholder="Email" >
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="login-mail" id="password_field">
                            <input type="password" id="password" name="password" placeholder="Password" >
                            <i class="fa fa-lock"></i>
                        </div>
                        <a class="left" href="forgotpassword.php">Forget Password </a>
                        <a class="right" href="signup.php">Create New Account</a>
                        <div class="text-center">
                            <button class="btn btn-info" type="button" id="LogIn" ><i class="fa fa-sign-in" aria-hidden="true"></i> LogIn</button>
                        </div>
                    </form>
                </div>

                <div class="clearfix"> </div>

            </div>
        </div>
        <div class="copy-right"><p><?php echo SITE_COPY_RIGHTS; ?></p></div>  
        <script src="js/jquery.nicescroll.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

<script type="text/javascript" language="JavaScript">
    $("#LogIn").click(function ()
    {
        $("#showSuccessMessageDiv").hide();
        $("#showMessageDiv").hide();
        $("#showMessage").html('');
        $("#showSuccessMessage").html('');
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var email = $("#email").val();
        if ((email == '') || (!regex.test(email)))
        {
            $("#mail_field").css({"border-style": "solid", "border-color": "red"});
            $("#showMessageDiv").show();
            $("#showMessage").html('<strong>Warning! </strong> Please enter your email.');
            $("#email").focus();
            return false;
        } else {
            $("#mail_field").css({"border-style": "solid", "border-color": "#E9E9E9"});
        }
        //var regex = /(?=^.{5,}$)/;
        var password = $("#password").val();
        //if((password=='') || (!regex.test(password)))
        if (password == '')
        {
            $("#password_field").css({"border-style": "solid", "border-color": "red"});
            $("#showMessageDiv").show();
            $("#showMessage").html('<strong>Warning! </strong> Please enter your password.');
            $("#password").focus();
            return false;
        } else {
            $("#password_field").css({"border-style": "solid", "border-color": "#E9E9E9"});
        }
        $.ajax({
            url: "controller.php",
            method: "GET",
            data: {loginData: $("#loginForm").serialize(), 'action': 'login'},
            dataType: "json",
            success: function (response) {
                if (response["success"] == true)
                {
                    $("#showMessageDiv").hide();
                    $("#showSuccessMessageDiv").show();
                    $("#showSuccessMessage").html("<strong>Success! </strong> Please Wait...!");
                    location = '<?php echo SITE_ADMIN_URL; ?>/controller.php?action=checkLogin&loginid=' + response["id"];
                    window.open(location);
                } else {
                    $("#showMessageDiv").show();
                    $("#showMessage").html(response["message"]);
                }
            },
            error: function (request, status, error) {
                $("#showMessage").html("OOPS! Something Went Wrong Please Try After Sometime!");
            }
        });
        return false;
    });
</script>
