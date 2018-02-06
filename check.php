<?php
session_start();

require('dbconnect.php');

if (isset($_POST) && !empty($_POST)) {
    //変数に入力された値を代入して扱いやすいようにする
    $nick_name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];


    try {
    //DBに会員情報を登録するSQL文を作成
      // now() MySQLが用意してくれている関数。現在日時を取得できる
      $sql = "INSERT INTO `packingme_users`(`user_name`, `email`, `password`, `created`, `modifide`) VALUES (?,?,?,now(),now())";

    //SQL文を実行
      // sha1 暗号化を行う関数
      $data = array($nick_name,$email,sha1($password));
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);

    //$_SESSIONの情報を削除
      // unset 指定した変数を削除するという意味。SESSIONじゃなくても使える
      // unset($_SESSION["name"]);
      // unset($_SESSION["email"]);
      // unset($_SESSION["password"]);

    //thanks.phpへ遷移
      header('Location: thanks.php');
      exit();
      
    } catch (Exception $e) {
      //tryで囲まれた処理でエラーが発生したときに、やりたい処理を記述する場所
      echo 'SQL実行エラー:'.$e->getMessage();
      exit();
      
    }

  }


?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Packing Me -TOP</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/agency.css" rel="stylesheet">

  </head>

  <body id="page-top">

   

    <!-- Header -->

    <style>
     *, ::after, ::before {
     box-sizing: border-box;
     margin: 0 auto;
    }

    .btn-xl {
    font-size: 18px;
    padding: 10px 40px;
    margin: 10px;
    }

    </style>
    <header class="masthead">
      <div class="container">
        <div class="intro-text" style="padding: 110px">
          <div class="intro-lead-in"></div>
          <div class="intro-heading text-uppercase">Packing me!</div>




   <div class="container">
    <div class="row">
      <div class="col-lg-4 col-lg-offset-4 content-margin-top">
        <form method="post" action="" class="form-horizontal" role="form">
          <input type="hidden" name="action" value="submit">
            <h1>PLEASE CHECK</h1>
            <table class="table table-striped table-condensed">
              <tbody>
                <!-- 登録内容を表示 -->
                <tr>
                  <td><div class="col-lg-12 text-center">Name</div></td>
                  <td><div class="text-center"><?php echo $_SESSION['name']; ?></div></td>
                </tr>
                <tr>
                  <td><div class="col-lg-12 text-center">Email</div></td>
                  <td><div class="text-center"><?php echo $_SESSION['email']; ?> </div></td>
                </tr>
                <tr>
                  <td><div class="col-lg-12 text-center">password</div></td>
                  <td><div class="text-center">●●●●●●●●</div></td>
                </tr>

                    
                
              </tbody>
            </table>
                
              <a class="logIn btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="signin.php?action=rewrite">BACK</a> 
              <input type="submit" class="logIn btn btn-primary btn-xl text-uppercase js-scroll-trigger" value="OK">   
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
      </div>
    </header>

    
    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <span class="copyright">Copyright &copy; Flash Packers with N</span>
          </div>
          <div class="col-md-4">
          </div>
          <div class="col-md-4">
            <ul class="list-inline quicklinks">
              <li class="list-inline-item">
                <a href="privacy_policy">Privacy Policy</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>

    <!-- Portfolio Modals -->

  
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <!-- <script src="js/agency.min.js"></script> -->

  </body>

</html>
