<?php
session_start();
if (strlen($_SESSION['uid']) == "") {
  header('location:logout.php');
} else {

?>

  <?php
  include_once('function.php');
  $obj = new DB_con();
  $fetchdata = new DB_con();


  $user_id = $_SESSION['uid'];

  $sql = $obj->get_balance($user_id);
  $balance = mysqli_fetch_array($sql);
  $diff = $balance['deposit_sum'] - $balance['withdraw_sum'];


  $id =  $_SESSION['uid'];
  $username = $_SESSION['username'];

  $data = $fetchdata->get_control();
  while ($row = mysqli_fetch_array($data)) {
    $recharge_msg = $row['rg_msg'];
    $notice =  $row['notice'];
    $approval = $row['approval'];
    $login =  $row['login'];
    $register = $row['register'];
    $bot_token =  $row['bot_token'];
    $log_channel = $row['log_channel'];
    $charge =  $row['charge'];
  }

  if (isset($_POST['submit'])) {
    $deposit = $_POST['deposit'];
    $number = $_POST['number'];
    $txn_id = $_POST['txn_id'];

    $result = $obj->request_deposit($number, $txn_id, $deposit, $id, $username);
    if ($result) {
      echo "<script>alert('Recharge request send successfully. Wait for approve.');</script>";
    } else {
      echo "something went wrong ";
    }
  }
  include('includes/head.php');

  ?>

  <main id="main" class="main">

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title text-center card-title col-sm-10 col-md-8 mx-auto align-center text-center">
                <?php echo $recharge_msg; ?>
              </h5>
              
              <h4 class="page-title mt-3 mb-3" style="font-weight: bold; color: #ff3e1d;">সর্বনিম্ন রিচার্জ <?php echo $approval; ?> টাকা</h4>
                
              <form action="#" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <div class="mb-3 mt-3">
<a href="https://t.me/Cpanel088" class="btn btn-primary w-100 rounded-pill">রিচার্জ করতে এখানে ক্লিক করুন</a>

                     </div>
                </div>
              </form>


              <div class="container mt-3">
                <div class="row">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th> ID </th>
                          <th> User ID </th>
                          <th> Deposit</th>
                          <th> Date</th>

                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql = $obj->get_deposit($id);
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($sql)) {
                        ?>
                          <tr>
                            <td> <?php echo $row['id']; ?> </td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['deposit']; ?></td>
                            <td><?php echo $row['date']; ?></td>

                          </tr>
                        <?php
                          $cnt = $cnt + 1;
                        } ?>
                      </tbody>
                    </table>
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