<?php 
  session_start();
  require('dbconnect.php');


?>

<!DOCTYPE html>
<html lang="ja">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit profile</title>

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
        <a class="navbar-brand js-scroll-trigger" href="home.php">Packing me!</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <div class="crown-icon">
              <a href="ranking.php">
                <img src="img/portfolio/crown.png">
              </a>
            </div>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="mypage.php">My Page</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="post.php">投稿する</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="top.php">Log out</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<!-- ヘッダーここまで -->
    <div class="profile-edit">
      <div class="container">
        <div class="profile-img">
          <div class="top-top-top">
            <input type="file" name="pic" class="form-control">
          </div>
          <div class="preview">
          </div>
          <img src="naoki2.png">
          <h2>aaagon</h2>
          <input type="file" id="file" style="" onchange="$('#fake_input_file').val($(this).val())">
          <button onClick="$('#file').click();">プロフィール画像を変更する</button>
        </div>
        <br>
        <div class="profile-text">
          <span>名前  </span>
          <input type="" name="" placeholder="Naoki">
        </div>
        <div class="profile-text">
          <span>メールアドレス</span>
          <input type="" name="" placeholder="naoki-love-massagel@gmail.com">
        </div>
        <div class="profile-text">
          <span>ウェブサイト</span>
          <input type="" name="" placeholder="https://seizetheday.jp/about-me/">
        </div>
        <div class="profile-textarea">
          <span>自己紹介</span>
          <textarea>Hello, lets go travel with us.
            See you in the world. Thank you.
          </textarea>
        </div>
        <div class="editButton"><a href="mypage.php"><button>変更する</button></a>
        </div>
      </div>
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
    <script src="js/packing_me.js"></script>
    <script src="js/post_pic.js"></script>

  </body>

</html>
