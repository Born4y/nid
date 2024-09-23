<?php
session_start();
error_reporting(0);

if(isset($_COOKIE['creation'])){
  $_SESSION['uid'] = base64_decode($_COOKIE['creation']);
}

if (isset($_SESSION['uid'])){
  header('location:dashboard.php');
}

include_once('function.php');
$usercredentials = new DB_con();
$sql = $usercredentials->get_control();
while ($row = mysqli_fetch_array($sql)) {
    $recharge_msg = $row['rg_msg'];
    $notice =  $row['notice'];
    $approval = $row['approval'];
    $login =  $row['login'];
    $register = $row['register'];
    $bot_token =  $row['bot_token'];
    $log_channel = $row['log_channel'];
    $charge =  $row['charge'];
}

if (isset($_POST['signin'])) {
  
  if ($login == 1) {
      $uname = $_POST['username'];
  } else { 
      $uname = "admin";
  }
  
  $pasword = md5($_POST['password']);
  $ret = $usercredentials->signin($uname, $pasword);
  $num = mysqli_fetch_array($ret);
  if ($num > 0) {
    $_SESSION['uid'] = $num['id'];
    $_SESSION['fname'] = $num['FullName'];
    $_SESSION['username'] = $uname;
    setcookie('creation',base64_encode($num['id']),time()+60*60*24*30);
    echo "<script>window.location.href='dashboard.php'</script>";
  } else {
    // Message for unsuccessful login
    echo "<script>alert('Invalid details. Please try again');</script>";
    echo "<script>window.location.href='signin.php'</script>";
  }
}

include('includes/head2.php');
?>


<main>
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
              </a>
            </div><!-- End Logo -->

            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2"> 
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  <p class="text-center small">Enter your username & password to login</p>
                </div>

                <form class="row g-3 needs-validation" novalidate action='' method="POST">
                  <?php if ($login == 1 ) { ?>
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" id="username" name="username" class="form-control" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" id="password" name="password" class="form-control" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                    <div class="col-12">
                      <p style="width: 100%;text-align: right;" class="small mb-0"><a href="forgot.php">Forgotten password?</a></p>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" name="signin" type="submit">Login</button>
                    </div>
                  <?php } else { ?>
                    <span style="
                      font-size: 12px;
                      text-align: center;
                      color: red;
                      font-weight: 600;
                      max-width: 218px;
                      margin: auto;
                    ">User Login turned off by admin. Please try again later.</span>
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" id="username" name="username" class="form-control" disabled value="Admin">
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" id="password" name="password" class="form-control" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" name="signin" type="submit">Login</button>
                    </div>
                  <?php } ?>
                  <div class="col-12">
                    <p class="small mb-0">Don't have an account? <a href="register.php">Create an account</a></p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Add the image below the container -->
  <div class="container">
    <img src="path_to_your_image.jpg" alt="Your Image" style="width: 100%; height: auto;">
  </div>

</main><!-- End #main -->

<?php include('includes/footer.php'); ?>
