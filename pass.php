<?php
session_start();
error_reporting(0);

if (strlen($_SESSION['uid']) == "") {
  header('location:logout.php');
} else {

?>
  <?php
  include_once('function.php');
  $obj = new DB_con();
  $fetchdata = new DB_con();
  $user_id = $_SESSION['uid'];
  $notice =  $row['notice'];

  include "includes/database.php";
  $sql = $obj->get_balance($user_id);
  $balance = mysqli_fetch_array($sql);
  $diff = $balance['deposit_sum'] - $balance['withdraw_sum'];
  

$link = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if ($link === false) {
    die("ERROR: Could not linkect. " . mysqli_connect());
}

//datas

  if (isset($_POST['password'])) {
     $pasword = md5($_POST['password']);
     

  $sql = "UPDATE tblusers SET password = '$pasword' WHERE tblusers.`id` = '$user_id'";
            if (mysqli_query($link, $sql)) {
                echo "<script>alert('Password updated.');</script>";
            }
  }
  
  



  ?>
  
  <?php include('includes/head.php');
  ?>
<style>
    .card-body {
        overflow: auto;
    }
</style>
  <main id="main" class="main">

    <section class="section">
      <div class="row">
       
       
         <div class="card-body pt-0">
              <div class="card mb-3">
                <div class="card-header">
                  <div class="row flex-between-end">
                    <div class="col-auto align-self-center">
                      <h5 class="mb-0" data-anchor="data-anchor">Change Password</h5>
                    </div>
                  </div>
                </div>
                <div class="card-body bg-light">
                  <div class="row light">
                      
                      
                    <div class="bg-diffrent">
                      <div class="container card text-white bg-danger">
                        <div class="card-body">
                          <form method="post" action="">
                            <div class="card-title">new password</div>
                            <input class="form-control" type="text" name="password" required="">
                            <div class="col-12 mt-3">
                              <button class="btn btn-primary" type="submit" name="charge">Update</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            
      </div>
    </section>
  </main>

  <?php include('includes/footer.php');
  ?>
<?php } ?>