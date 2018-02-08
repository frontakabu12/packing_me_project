<?php 
session_start();
require('dbconnect.php');

// 投稿をDBに登録
$sql = "INSERT INTO `packingme_posts` (`user_id`, `pic`, `category_id`, `place`, `term`, `backpack`, `weight`, `detail`, `created`) VALUES (?,?,?,?,?,?,?,?,now());";

// SQL実行
$data = array($_SESSION["id"],$_POST["pic"],$_POST["category_id"],$_POST["place"],$_POST["term"],$_POST["backpack"],$_POST["weight"],$_POST["detail"]);
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

// 画像の拡張子チェック
// jpg,png,gifはok
if(!isset($error)){
  $ext = substr($_FILES['pic']['name'], -3);

  if(($ext == 'png') || ($ext == 'jpg') || ($ext == 'gif')){
    // 画像のアップロード処理
    $pic_name = date('YmdHis') . $_FILES['pic']['name'];

    // アップロード
    move_uploaded_file($_FILES['pic']['tmp_name'], 'pic/' . $pic_name);

    // SESSION変数に入力された画像を保存
    $_SESSION['post'] = $_POST;
    $_SESSION['post']['pic_name'] = $pic_name;

    header('Location: home.php');
    exit();
  }else{
    $error["image"] = 'type';
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

    <title>POST</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- Custom styles for this template -->
    <link href="css/agency.css" rel="stylesheet">
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous"> -->

  </head>

  <body id="page-top">

    <!-- ヘッダー固定部分 -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Packing Me!</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <div class="crown-icon">
              <a href="ranking.html">
                <img src="img/portfolio/crown.png">
              </a>
            </div>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="home.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="mypage.html">My Page</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="post.html">投稿する</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="top.html">Log out</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<!-- ヘッダーここまで -->
    
    <div class="post-form">
      <form method="POST" action="home.php" role="form" enctype="multipart/form-data">
        <!-- 投稿写真 -->
        <div class="top-top-top">
        <input type="file" name="pic" class="form-control"></div>
        <div class="preview">
        </div>
          <select name="categories"> 
            <option value="1" selected="">タイプを選択</option> 
            <option value="2">Traveler</option> 
            <option value="3" >Engineer</option>  
          </select>
          <select name="categories"> 
            <option value="1" selected="">カテゴリを選択</option> 
            <option value="2">3日以内</option> 
            <option value="3" >2週間以内</option> 
            <option value="4">2週間以内</option> 
            <option value="4">2週間以上</option> 
            <option value="4">1ヶ月以上</option> 
          </select>
          <center>場所</center>
          <input type="" name="" placeholder="フィリピン　セブ島">
          <center>期間</center>
          <input type="" name="" placeholder="４日間">
          <center>backpack</center>
          <input type="" name="" placeholder="the north face Caelus 35L">
          <center>重量</center>
          <input type="" name=""  placeholder="kg">
          <center>中身詳細</center>
          <textarea placeholder="mackbookpro, dji spark, omd-em5 mark2, t-shirts 3, pants 3"></textarea>
          <br>
          <input type="submit" value="編集する" class="btn btn-xl btn-primary">
        </form>
       </div> 

    <!-- 空の箱 -->
    <div class="enpty-box"></div>

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
                <a href="privacy_policy.html">Privacy Policy</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  
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
    <script src="packing_me.js"></script>
    <script src="js/post_pic.js"></script>
  </body>

</html>
