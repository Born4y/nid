<?php
session_start();
error_reporting(0);
if (isset($_SESSION['uid'])){
    header('location:dashboard.php');
}


define('DB_HOST','localhost');
define('DB_USER','bdgovpw_server');
define('DB_PASS','bdgovpw_server');
define('DB_NAME','bdgovpw_server');

function sentMail($id,$name,$email){
    $data = [];
    $data['id'] = $id;
    $data['name'] = $name;
    $data['email'] = $email;
    $data['time'] = strtotime("+15 minutes",strtotime(date("Y-m-d H:i:s")));
    $reset = str_replace("=","",base64_encode(bin2hex(json_encode($data))));
    $subject = "Reset your Server Copy password";
    $body = "<h3>Hello ".$name.",</h3><h4>Your password reset link is :</h4><center><p>https://tmx-army.xyz/forgot.php?reset=".$reset."</p></center><p>Use this link within 15 minutes, After 15 minutes this link is useless</p><br><center><p>If you don't recognide ".$email." , You can safely ignore this email. </p></center>";
    	
    $post = http_build_query(['name' => 'Server Copy','email' => $email,'subject' => $subject,'body' => $body]);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://mail.kaichi-tore.xyz/');
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER , 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, [
		'Content-Type: application/x-www-form-urlencoded',
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36'
	]);
	$data = curl_exec($curl);
	curl_close($curl);
    
    if($data == "200"){
        return true;
    }else{
        return false;
    }
}
if(isset($_POST['email'])){
    $email = $_POST['email'];
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $checkUserList  = mysqli_query($conn,"SELECT * FROM `tblusers` WHERE `UserEmail` = '$email'");
    $totalUserList = mysqli_num_rows($checkUserList);
		if($totalUserList > 0){
			$row = mysqli_fetch_array($checkUserList);
			$id = $row['id'];
			$name = $row['FullName'];
			$sentMail = sentMail($id,$name,$email);
			if($sentMail){
			    echo '<script>alert("Mail sent successfully!")</script>';
			    showForgotMsg('Mail sent successfully','একটি পাসওয়ার্ড রিসেট লিঙ্ক আপনার ইমেল পাঠানো হয়েছে. <br><spam style="color:red;"> স্প্যাম/প্রচারমূলক মেইল চেক করতে ভুলবেন না। আপনি সেখানে লিঙ্ক খুঁজে পেতে পারেন </spam>');   
			}else{
				echo '<script>alert("Failed to send mail!")</script>';
				showForgotPassword();  
			}
		}else{
		    echo '<script>alert("Email not found!")</script>';
		    showForgotPassword();
		}
}else if(isset($_GET['reset'])){
    $reset = json_decode(hex2bin(base64_decode($_GET['reset'])),true);
    $time = strtotime(date("Y-m-d H:i:s"));
    if($time < $reset['time']){
        $_SESSION['reset'] = $reset['id'];
        showNewPassword();
    }else{
        showForgotMsg('Link Expired','লিঙ্কের মেয়াদ শেষ। এই লিঙ্কটি শুধুমাত্র 15 মিনিটের জন্য ব্যবহারযোগ্য ছিল');
    }
}else if(isset($_POST['password'],$_POST['confirm'],$_SESSION['reset'])){
    $id = $_SESSION['reset'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    if($password == ""){
        echo '<script>alert("Input your password!")</script>';
        showNewPassword();
    }else if($password != $confirm){
        echo '<script>alert("Confirm password not matched!")</script>';
        showNewPassword(); 
    }else{
        $pass = md5($password);
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $updatePassword = mysqli_query($conn, "UPDATE `tblusers` SET `Password` = '$pass' WHERE `tblusers`.`id` = '$id'");
        if($updatePassword){
            $checkUserList  = mysqli_query($conn,"SELECT * FROM `tblusers` WHERE `id` = '$id'");
			$row = mysqli_fetch_array($checkUserList);
			$Username = $row['Username'];
            echo '<script>alert("Password reset successfully!")</script>';
            showForgotMsg('Password reset successfully!','পাসওয়ার্ড সফলভাবে আপডেট করা হয়েছে। এখন আপনি নতুন পাসওয়ার্ড দিয়ে লগইন করতে পারেন<br>Username: '.$Username);
        }else{
            echo '<script>alert("Failed to update password!")</script>';
            showNewPassword();
        }
    }
}else{
    showForgotPassword();
}

function showForgotPassword(){
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>Forgot password</title>
    <meta name="description" content="Forgot password" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
</head>
<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="justify-content-center">
                            <center><span class="app-brand-logo demo"><img width="100px" <img height="100px"src="assets/img/logo.png"></center>
                            <center><h3 style="color:red;"class="center mb-2">Welcome</h3></center>
                            <center><p style="color:green;" class="mb-4">ইমেইল ব্যবহার করে পাসওয়ার্ড রিসেট করুন</p></center>
                            <form class="loginform" action="forgot.php" method="POST">
                                <label for="" class="text-uppercase text-sm">Your Email</label>
                                <input type="text" placeholder="Email" name="email" class="form-control mb-2" required>
                                <button class="btn btn-primary d-grid w-100" name="login" type="submit">SUBMIT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
  </body>
</html>
<?php
}
function showNewPassword(){
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>New password</title>
    <meta name="description" content="New password" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
</head>
<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="justify-content-center">
                            <center><span class="app-brand-logo demo"><img width="100px" <img height="100px"src="assets/img/logo.png"></center>
                            <center><h3 style="color:red;"class="center mb-2">Password Reset</h3></center>
                            <center><p style="color:green;" class="mb-4">নতুন পাসওয়ার্ড তৈরি করুন</p></center>
                            <form class="loginform" action="forgot.php" method="POST">
                                <label for="" class="text-uppercase text-sm">New password</label>
                                <input type="text" placeholder="New password" name="password" class="form-control mb-2" required>
                                <label for="" class="text-uppercase text-sm">Confirm password</label>
                                <input type="text" placeholder="Confirm password" name="confirm" class="form-control mb-2" required>
                                <button class="btn btn-primary d-grid w-100" name="login" type="submit">SUBMIT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
  </body>
</html>
<?php
}
function showForgotMsg($title,$msg){
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title><?php echo $title; ?></title>
    <meta name="description" content="<?php echo $title; ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
</head>
<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="justify-content-center">
                            <center><span class="app-brand-logo demo"><img width="100px" <img height="100px"src="assets/img/logo.png"></center>
                            <center><h3 style="color:red;"class="center mb-2"><?php echo $title; ?></h3></center>
                            <center><p style="color:green;" class="mb-4"><?php echo $msg; ?></p></center>
                            <a href="/"><button class="btn btn-primary d-grid w-100" name="login" type="submit">OK</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
  </body>
</html>
<?php
}
?>