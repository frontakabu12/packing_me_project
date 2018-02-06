<?php 
session_start();

//クッキー情報が存在してたら（自動ログイン）
//
if (isset($_COOKIE["email"]) && !empty($_COOKIE["email"])){
  $_POST["email"] = $_COOKIE["email"];
  $_POST["password"] = $_COOKIE["password"];
  $_POST["save"] = "on";
}

require('dbconnect.php');


if(isset($_POST) && !empty($_POST)){
  //認証処理
  try {
      //メンバーズテーブルでテーブルの中からメールアドレスとパスワードが入力された
     //データを収得
    $sql = "SELECT * FROM `packingme_users` WHERE `email`=? AND `password`=? ";

    //sql文実行
    //パスワードは入力されたものを暗号化した上使用＄$-
    $data = array($_POST["email"],sha1($_POST["password"]));
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    //一行取得
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // var_dump($member);
    // echo "</pre>";

    if($member == false){
      //認証失敗
      $error["login"] = "failed";
      }else{
      //認証成功
      //1,セッション変数に会員idを保存
      $_SESSION["id"] = $member["user_id"];

      //2,ログインした時間をセッション変数に保存 
      $_SESSION["time"] = time();

      //3自動ロギングの処理
      if ($_POST["save"] = "on"){
        //クッキーに保存
        setcookie('email',$_POST["email"],time()+60*60*24*14);
        setcookie('password',$_POST["password"],time()+60*60*24*14);

      }

      //４、ログイン後の画面に移動
      header("Location: home.php");
      exit();
     }
      
     }catch (Exception $e) {
       
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

    <title>Packing Me -login</title>

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


    </style>
    <header class="masthead">
      <div class="container">
        <div class="intro-text" style="padding: 100px; /*margin-bottom: 100px;*/background-image: cover;">
          <div class="intro-lead-in"></div>
          <div class="intro-heading text-uppercase">Packing me!</div>
          <div class="login">
           <div class="container">
              <div class="col-lg-6 col-lg-offset-3">
               <div class="login-box">
                  <h1>Login</h1>
                  <form role="form" method="post" >
                    <div class="col-lg-12">
                      <label>Email</label>
                      <div class="form-group">
                        <input type="email" name="email" id="email" class="form-control" placeholder="" required="">
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <label>Password</label>
                      <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="" required="">
                      </div>
                    </div>
                    <div class="control-group">
                      <div class="controls">
                        <label class="checkbox" for="save_card">
                          <input type="checkbox" name="save" id="save_card" value="option1">
                          Save card on file?
                        </label>
                      </div>
                    </div>
                    <br>
                    <input type="submit" class="logIn btn btn-primary btn-xl text-uppercase js-scroll-trigger" value="LOG IN" style="margin-bottom: 100px;">
                    
                   </form>
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
                <a href="#">Privacy Policy</a>
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
    <script src="js/agency.min.js"></script>

  </body>

</html>
