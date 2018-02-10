<?php 
session_start();

require('dbconnect.php');

// 書き直し処理（check.phpで書き直し、というボタンが押された時）
  if (isset($_GET['action']) && $_GET['action'] =='rewrite'){

    // 書き直すために初期表示する情報を変数に格納
    $user_name = $_SESSION['user_name'];
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    

  }else{
    // 通常の初期表示
    $user_name = '';
    $email = '';
    $password = '';
  }


if(isset($_POST) && !empty($_POST)){

 // 入力チェック
  // ニックネームが空だったら$errorという、エラーの情報を格納する変数にnick_nameはblankだったというマークを保存しておく
  if ($_POST["user_name"]==''){
    $error['user_name'] = 'blank';
  }
  
  if ($_POST["email"]==''){
    $error['email'] = 'blank';
  }
  // password
  // strlen 文字の長さ（文字数）を数字で返しえてくれる関数
  if ($_POST["password"]==''){
    $error['password'] = 'blank';
  } elseif (strlen($_POST["password"]) < 4){
    $error["password"] = 'length';
  }

  // 入力チェック後、エラーが何もなければ、check.phpに移動
  // $errorという変数が存在していなかった場合、入力が正常と認識
  if (!isset($error)){
   
    //emailの重複チェック
    //dbに同じemailがあるか確認
    //as 別名　取得したデータで別の名前をつけ扱いやすくする
    try {
      $sql = "SELECT COUNT(*) as `cnt` FROM `packingme_users` WHERE `email`=? ";
      //sql
      $data = array($_POST["email"]);
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);

      // 件数取得
      $count = $stmt->fetch(PDO::FETCH_ASSOC);

      if($count['cnt'] > 0){
        // 重複エラー
        $error['email'] = "duplicated";

      }

    } catch (Exception $e) {
      
    }

    if(!isset($error)){

    // SESSION変数に入力された値を保存（変数をSESSIONに登録する）(どこの画面からでも利用できる！)
    // 注意！必ずファイルの一番上にsession_start();と書く
    // POST送信された情報をjoinというキー指定で保存
     $_SESSION["user_name"] = $_POST["user_name"];
     $_SESSION["email"] = $_POST["email"];
     $_SESSION["password"] = $_POST["password"];
    
     // check.phpに移動
    header('Location: check.php');
    // これ以下のコードを無駄に処理しないように、このページの処理を終了させる
    exit();
    
    }
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


    </style>
    <header class="masthead">
      <div class="container">
        <div class="intro-text" style="padding: 100px">
          <div class="intro-lead-in"></div>
          <div class="intro-heading text-uppercase">Packing me!</div>
          <div class="login">
            <div class="container">
              <div class="col-lg-6 col-lg-offset-3">
                <div class="login-box">
                  <h1>SIGN IN</h1>
                    <form role="form" method="post" action="">
                    <div class="col-lg-12">
                      <label>Name</label>
                      <div class="form-group">
                        <input type="user_name" name="user_name" id="user_name" class="form-control" placeholder="" value="<?php echo $user_name; ?>">
                        <?php if((isset($error["user_name"])) && ($error['user_name'] == 'blank')){ ?>
                        <p clas>* ニックネームを入力してください。</p>
                        <?php } ?>
                      </div>
                    </div>
                    
                    <div class="col-lg-12">
                      <label>Email</label>
                      <div class="form-group">
                      <input type="email" name="email" id="email" class="form-control" placeholder="" value="<?php echo $email; ?>">
                      <?php if((isset($error['email'])) && ($error['email'] == 'blank')) { ?>
                        <p>* メールアドレスを入力してください。</p>
                      <?php } ?>
                      <?php if((isset($error['email'])) && ($error['email'] == 'duplicated')) { ?>
                        <p class="error">* 入力されたemailは登録済みです。</p>
                      <?php } ?>
                      </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <label>Password</label>
                        <div class="form-group">
                      <input type="password" name="password" id="password" class="form-control" placeholder="" value="<?php echo $password; ?>" >
                      <?php if((isset($error["password"])) && ($error['password'] == 'blank')) { ?>
                        <p>* パスワードを入力してください。</p>
                        <?php } ?>
                        <?php if((isset($error["password"])) && ($error['password'] == 'length')) { ?>
                        <p>* パスワードは４文字以上を入力してください。</p>
                      <?php } ?>
                      </div>
                    </div>
                    <br>
                    <input type="submit" value="SEND" class="logIn btn btn-primary btn-xl text-uppercase js-scroll-trigger">
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
            <a href="top.php"><span class="copyright">Copyright &copy; Flash Packers with N</span></a>
          </div>
          <div class="col-md-4">
          </div>
          <div class="col-md-4">
            <ul class="list-inline quicklinks">
              <li class="list-inline-item">
                <a href="privacy_policy.html">Privacy Policy</a>
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
