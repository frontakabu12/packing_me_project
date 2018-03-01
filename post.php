<?php 
session_start();
require('dbconnect.php');

// POST送信されていたら
if(isset($_POST) && !empty($_POST)){

  // pic
  // if ($_POST["pic"] == ''){

  //     $error["pic"] = 'blank';
  //   }

  // type
  if ($_POST["type"] == ''){

      $error["type"] = 'blank';
    }

  // category_id
    if ($_POST["category"] == ''){

      $error["category"] = 'blank';
    }

  // place
    if ($_POST["place"] == ''){

      $error["place"] = 'blank';
    }

    // term
    if ($_POST["term"] == ''){

      $error["term"] = 'blank';
    }

    // backpack
    if ($_POST["backpack"] == ''){

      $error["backpack"] = 'blank';
    }

    // weight
    if ($_POST["weight"] == ''){

      $error["weight"] = 'blank';
    }

    // detail
    if ($_POST["detail"] == ''){

      $error["detail"] = 'blank';
    }
 
if(!isset($error)){

  // 画像の拡張子チェック
  // jpg,png,gifはok
  $ext = substr($_FILES['pic']['name'], -3);

  if(($ext == 'png') || ($ext == 'jpg') || ($ext == 'gif') || ($ext == 'JPG')){
    // 画像のアップロード処理
    $pic_name = date('YmdHis') . $_FILES['pic']['name'];
    // アップロード
    move_uploaded_file($_FILES['pic']['tmp_name'], 'pic/' . $pic_name);

    // SESSION変数に入力された画像を保存
    $_SESSION['pic'] = $pic_name;

  
  // 投稿をDBに登録
  $sql = "INSERT INTO `packingme_posts` (`user_id`,`pic`,`type`, `category_id`, `place`, `term`, `backpack`, `weight`, `detail`, `created`) VALUES (?,?,?,?,?,?,?,?,?,now());";

  // SQL実行
  $data = array($_SESSION["id"],$_SESSION["pic"],$_POST["type"],$_POST["category"],$_POST["place"],$_POST["term"],$_POST["backpack"],$_POST["weight"],$_POST["detail"]);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);

  
    header('Location: home.php');
    exit();
  }else{
    $error["image"] = 'type';
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
        <a class="navbar-brand js-scroll-trigger" href="home.php">Packing Me!</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <div class="crown-icon">
              <a href="ranking_php.php">
                <img src="img/portfolio/crown.png">
              </a>
            </div>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="mypage.php?user_id=<?php echo $_SESSION["id"]; ?>">My Page</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="post.php">投稿する</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="logout.php">Log out</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<!-- ヘッダーここまで -->
    <div class="post-form">
      <form method="POST" action="" role="form" enctype="multipart/form-data">
        <!-- 投稿写真 -->
        <div class="top-top-top">
        <input type="file" name="pic" class="form-control"></div>
        <div class="preview">
        </div>
          <select name="type"> 
            <option value="1" selected="">タイプを選択</option> 
            <option value="Traveler">Traveler</option> 
            <option value="Engineer" >Engineer</option>  
          </select>
          <?php if((isset($error["type"])) && ($error["type"] == 'blank')) { ?>
                <p class="error">＊タイプを選択してください</p>
          <?php }?>
          <select name="category"> 
            <option value="0" selected="">カテゴリを選択</option> 
            <option value="5">3日以内</option> 
            <option value="4" >1週間以内</option> 
            <option value="3">2週間以内</option> 
            <option value="2">2週間以上</option> 
            <option value="1">1ヶ月以上</option> 
          </select>
          <?php if((isset($error["category"])) && ($error["category"] == 'blank')) { ?>
                <p class="error">＊カテゴリーを選択してください</p>
          <?php }?>
          <center>場所</center>
          <input type="" name="place" placeholder="例）フィリピン　セブ島">
          <?php if((isset($error["place"])) && ($error["place"] == 'blank')) { ?>
                <p class="error">＊場所を入力してください</p>
          <?php }?>
          <center>期間</center>
          <input type="" name="term" placeholder="例）４日間">
          <?php if((isset($error["term"])) && ($error["term"] == 'blank')) { ?>
                <p class="error">＊期間を入力してください</p>
          <?php }?>
          <center>backpack</center>
          <input type="" name="backpack" placeholder="例）the north face Caelus 35L">
          <?php if((isset($error["backpack"])) && ($error["backpack"] == 'blank')) { ?>
                <p class="error">＊バックパックを入力してください</p>
          <?php }?>
          <center>重量</center>
          <input type="" name="weight" placeholder="例）13kg">
          <?php if((isset($error["weight"])) && ($error["weight"] == 'blank')) { ?>
                <p class="error">＊重量を入力してください</p>
          <?php }?>
          <center>中身詳細</center>
          <textarea name="detail" placeholder="例）mackbookpro, dji spark, omd-em5 mark2, t-shirts 3, pants 3..."></textarea>
          <?php if((isset($error["detail"])) && ($error["detail"] == 'blank')) { ?>
                <p class="error">＊詳細を入力してください</p>
          <?php }?>
          <br>
          <input type="submit" value="投稿する" class="btn btn-xl btn-primary">
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
                <a href="privacy_policy.php">Privacy Policy</a>
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
