<?php 
session_start();
require('dbconnect.php');




$sql = "SELECT * FROM `packingme_posts` WHERE `packingme_posts`.`post_id`=".$_GET["post_id"];
   // sql実行
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  // フェッチ
  $one_post = $stmt->fetch(PDO::FETCH_ASSOC);






// POST送信されていたら
// if(isset($_POST) && !empty($_POST)){

//   // type
//   if ($_POST["type"] == ''){

//       $error["type"] = 'blank';
//     }

//   // category_id
//     if ($_POST["category"] == ''){

//       $error["category"] = 'blank';
//     }

//   // place
//     if ($_POST["place"] == ''){

//       $error["place"] = 'blank';
//     }

//     // term
//     if ($_POST["term"] == ''){

//       $error["term"] = 'blank';
//     }

//     // backpack
//     if ($_POST["backpack"] == ''){

//       $error["backpack"] = 'blank';
//     }

//     // weight
//     if ($_POST["weight"] == ''){

//       $error["weight"] = 'blank';
//     }

//     // detail
//     if ($_POST["detail"] == ''){

//       $error["detail"] = 'blank';
//     }
 
// if(!isset($error)){

//   $up_sql ="UPDATE `packingme_posts` SET `pic` =?,`place`=?,`term`=?,`backpack`=?,`weight`=?,`detail`=? WHERE `packingme_posts`.`post_id` =?;"
  
  






   
   

 ?>



<!DOCTYPE html>
<html lang="ja">

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
    
    <div class="post-form">
      <form method="POST" action="home.php" role="form" enctype="multipart/form-data" >
        <!-- 投稿写真 -->
        
        
        <div class="top-top-top">
        <img src="pic/<?php echo $one_post["pic"];?>" width = "600">
        </div>
        
        
          <select name="categories"> 
            <option value="2" selected=""><?php echo $one_post["type"];?></option> 
            <!-- <option value="2">Traveler</option>  -->
            <option value="3" >Engineer</option>  
          </select>
          <select name="categories">
          <?php swiotch?> 
            <option value="<?php echo $one_post["category_id"];?>" selected=""></option> 
            <option value="2">3日以内</option> 
            <option value="3" >1週間以内</option> 
            <option value="4">2週間以内</option> 
            <option value="4">2週間以上</option> 
            <option value="4">1ヶ月以上</option> 
          </select>
          <center>場所</center>
          <input type="" name="" placeholder="フィリピン　セブ島" value="<?php echo $one_post["place"];?>">
          <center>期間</center>
          <input type="" name="" placeholder="４日間" value="<?php echo $one_post["term"];?>">
          <center>backpack</center>
          <input type="" name="" placeholder="the north face Caelus 35L" value="<?php echo $one_post["backpack"];?>">
          <center>重量</center>
          <input type="" name=""  placeholder="kg" value="<?php echo $one_post["weight"];?>">
          <center>中身詳細</center>
          <textarea><?php echo $one_post["detail"];?></textarea>
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
