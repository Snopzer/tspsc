<?php

ob_start();
session_start();
include_once('../config.php');
include_once('../parameter.php');

/* if($_GET['action']=='checklogin'){ 
  header('Location:'.SITE_ADMIN_URL.'home.php');
  } */
if ($_GET['action'] == 'signup') {
    parse_str($_GET['singupData'], $singupData);
    $email = $singupData['email'];
    $password = $singupData['password'];
    $ChecMailQuery = $conn->query("SELECT * FROM r_user where email='" . $email . "'");
    $emailCount = mysqli_num_rows($ChecMailQuery);
    if ($emailCount >= 1) {
        $response['message'] = "Email Already Exist!";
        $response['success'] = false;
    }
    $ChecMailAct = $conn->query("SELECT * FROM r_user where email='" . $email . "' and status=0 ");
    $mailCount = mysqli_num_rows($ChecMailAct);
    if ($mailCount >= 1) {
        $response['message'] = "Your Account Is Not Activated.! Please Try Again";
        $response['success'] = false;
    }
    if ($emailCount == 0) {
        $activate_code = rand(0, 100000);
        $insertQuery = $conn->query("INSERT INTO r_user (email,password,image,status,activate_link) VALUES ('" . $email . "','" . md5($password) . "', 'no-user-image.png',0,'" . $activate_code . "')");
        if ($insertQuery) {
            $subject = 'Signup | Verification';
            $message = '<html>
				<head>
				<title>Thank You for signing up!</title>
				</head>
				<body align="center">
				<h3>Thank You for signing up!<h3>
				<p>Your account has been created Succesfully to activate your link please click on activation link.<p>
				<table align="center">
				<tr><td>Email  </td><td>' . $email . '</td></tr>	
				<tr><td>Password  </td><td>' . $password . '</td></tr>	
				</table>
				<a href="' . SITE_ADMIN_URL . 'controller.php?activate_link=' . $activate_code . '"><input type="button" value="Activation Link"></a>
				</body>
				</html>';

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From:' . FROM_MAIL . "\r\n";
            $sendmail = mail($email, $subject, $message, $headers);
            $response['message'] = "Thank You! Check Your Email To Activate Your Account";
            $response['success'] = true;
        } else {
            $response['message'] = "OOPS! Something Went Wrong Please Try After Sometime!";
            $response['success'] = false;
        }
    }
    echo json_encode($response);
    exit;
} elseif ($_GET['action'] == 'checkLogin') {
    $loginId = $_GET['loginid'];
    $LoginQuery = $conn->query("SELECT * FROM r_user where id_user='" . $loginId . "'");
    $user = $LoginQuery->fetch_assoc();
    $_SESSION['name'] = ($user['name'] != '') ? $user['name'] : 'New User';
    $_SESSION['image'] = ($user['image'] != '') ? $user['image'] : 'no-user-image.png';
    $_SESSION['id'] = $user['id_user'];
    $_SESSION['id_user_role'] = $user['id_user_role']; //1: Super Admin
    header('Location:' . SITE_ADMIN_URL . 'home.php');
} elseif ($_GET['action'] == 'login') {
    parse_str($_GET['loginData'], $loginData);
    $email = $loginData['email'];
    $password = md5($loginData['password']);
    $LoginQuery = $conn->query("SELECT * FROM r_user where email='" . $email . "'");
    $checkparticipentCount = mysqli_num_rows($LoginQuery);
    if ($checkparticipentCount == 1) {
        $user = $LoginQuery->fetch_assoc();
        if ($user['status'] != 1) {
            $response['message'] = "Your Account is InActive";
            $response['success'] = false;
        }

        if ($user['password'] != $password) {
            $response['message'] = "Wrong password. Try again.";
            $response['success'] = false;
        }
        if ($user['status'] == 1 && $user['password'] == $password) {
            $response['message'] = "Welcome" . $_SESSION['name'];
            $response['id'] = $user['id_user'];
            $response['success'] = true;
        }
    } else {
        $response['message'] = "Invalid Credentials. Try again.";
        $response['success'] = false;
    }
    echo json_encode($response);
    exit;
} elseif (isset($_GET['type']) && $_GET['type'] == "forgetpassword") {
    $email = $_POST['email'];
    $forgotQuery = $conn->query("SELECT * FROM r_user where email='$email'") or die(mysql_error());
    $user = $forgotQuery->fetch_assoc();
    $count = mysqli_num_rows($forgotQuery);

    if ($count == 1) {
        $password = rand(0, 100000);
        $sql = $conn->query("UPDATE r_user set password= '" . md5($password) . "' where email='$email'") or die(mysql_error());

        $subject = 'Techdefeat Password Updated'; // Give the email a subject 


        $message = '<html>
			<head>
			<title>Your Password has been changed successfully</title>
			</head>
			<body align="center">
			<h3>Your Password has been changed successfully<h3>
			<p>Your account has been created Succesfully to activate your link please click on activation link.<p>
			<table align="center">
			<tr><td>Email  </td><td>' . $email . '</td></tr>	
			<tr><td>Password  </td><td>' . $password . '</td></tr>	
			</table>
			<a href="' . SITE_ADMIN_URL . '"><input type="button" value="Login Now"></a>
			</body>
			</html>';

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From:' . FROM_MAIL . "\r\n";

        $sendmail = mail($email, $subject, $message, $headers);
        header('location:' . SITE_ADMIN_URL . 'forgotpassword.php?msg=Password Changed Successfully');
    } else {
        header('location:' . SITE_ADMIN_URL . 'forgotpassword.php?msg=No Email Exist!');
    }
} elseif (isset($_GET['type']) && $_GET['type'] == "logout") {
    if (!isset($_SESSION['id'])) {
        header('location:' . SITE_ADMIN_URL . 'index.php');
    } else {
        session_destroy();
        header('location:' . SITE_ADMIN_URL . 'index.php');
    }
} elseif (isset($_GET['activate_link']) && ($_GET['activate_link'] != '')) {
    $activate_link = $_GET['activate_link'];
    $activateUser = $conn->query("SELECT status FROM r_user WHERE activate_link='" . $activate_link . "'");
    if (mysqli_num_rows($activateUser) > 0) {
        $ChangeStatus = $conn->query("SELECT * FROM r_user WHERE activate_link='" . $activate_link . "' and status='0'");
        $i = $ChangeStatus->fetch_assoc();
        $id = $i['id_user'];
        if (mysqli_num_rows($ChangeStatus) == 1) {
            $updateStatus = $conn->query("UPDATE r_user SET status='1',activate_link='' WHERE id_user='" . $id . "' ");
            $_SESSION['message'] = "Your Account is Actived, Login Now!";
            header('location:' . SITE_ADMIN_URL . 'index.php');
        } else {
            $_SESSION['message'] = "Your account is already active, no need to activate again";
            header('location:' . SITE_ADMIN_URL . 'index.php');
            exit;
        }
    } else {
        $_SESSION['message'] = "Wrong activation code.";
        header('location:' . SITE_ADMIN_URL . 'index.php');
    }
}
?>