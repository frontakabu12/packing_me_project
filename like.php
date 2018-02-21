<?php
  session_start();
  require('dbconnect.php');

  if(!empty($_SESSION)){
    // 一行データ取得するsql
    $one_sql = "SELECT * FROM`packingme_users`WHERE`id`=".$_SESSION["id"];
    // sql実行
    $one_stmt = $dbh->prepare($one_sql);
    $one_stmt->execute();
    // フェッチ
    $user = $one_stmt->fetch(PDO::FETCH_ASSOC);
 

    
  // ログインしている人がいいねしている投稿を取得するsql
  $like_list_sql = "SELECT * FROM `packingme_posts` INNER JOIN `packingme_likes` ON `packingme_posts`.`post_id`=`packingme_likes`.`post_id` WHERE `packingme_likes`.`user_id`=? ORDER BY `packingme_posts`.`modified` DESC";
  // sql実行
  $data = array($_SESSION["id"]);
  $like_list_stmt = $dbh->prepare($like_list_sql);
  $like_list_stmt->execute($data);
  // フェッチ
  $like_list = array();

  while(1){
  $like_post = $like_list_stmt->fetch(PDO::FETCH_ASSOC);

    if($like_post == false){
      break;
    }else{

      // like数を求めるSQL文
      $like_sql = "SELECT COUNT(*) as `like_count` FROM `packingme_likes` WHERE `post_id`=".$like_post["post_id"];
      $like_stmt = $dbh->prepare($like_sql);
      $like_stmt->execute();

      $like_number = $like_stmt->fetch(PDO::FETCH_ASSOC);
 
      // 一行分のデータに新しいキーを用意して、like数を代入
      $like_post["like_count"] = $like_number["like_count"];

      // // ログインしている人がlikeした数を求めるSQL文
      $login_like_sql = "SELECT COUNT(*) as `like_flag` FROM `packingme_likes` WHERE `user_id`=".$_SESSION["id"]." AND `post_id`=".$like_post["post_id"]." ORDER BY `like_flag` DESC";

      $login_like_stmt = $dbh->prepare($login_like_sql);
      $login_like_stmt->execute();

      $login_like_number = $login_like_stmt->fetch(PDO::FETCH_ASSOC);

      $like_post["login_like_flag"] = $login_like_number["like_flag"];


      $like_list[] = $like_post;
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

    <title>Packing Me -Like</title>
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
              <a href="ranking_php.php">
                <img src="img/portfolio/crown.png">
              </a>
            </div>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="mypage.php?user_id=<?php echo $_SESSION["id"];?>">My Page</a>
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
<!-- 右側固定部分 -->
    <div class="right-right">
      <div class="right-caram">
        <div class="profile-imgs">
          <div class="top-image"><img src="picture_path/<?php echo $user["picture_path"];?>"></div>
          <h3><?php echo $user["user_name"];?></h3>
        </div>
        <br>
        <div class="profile-texts">
          <p>
            <?php echo $user["self_intro"];?>
          </p>
        </div>
        <div class="profile-texts">

          <span><?php echo $user["web_site"];?></span>
        </div>
          <div class="buttons">
            <div class="be-center"><a href="mypage.php"><button class="like-button">投稿一覧</button></a></div>
            <br>
            <div class="be-center"><a href="edit_profile.php"><button class="edit-prof-button">プロフィールを編集する</button></a></div>
          </div>
      </div>
    </div>
<!--  ここまで右側固定部分-->

<!-- 画像部分 -->
    <section class="bg-light mypage" id="portfolio">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="section-heading text-uppercase">Posts You've Liked</h2>
            <h3 class="section-subheading text-muted"></h3>
          </div>
        </div>
        <div class="row">

          <!-- 繰り返し部分 -->
          <?php foreach($like_list as $like_post){?>
          <div class="col-md-6 col-sm-6 portfolio-item">
            <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $like_post["post_id"]; ?>">
              <div class="portfolio-hover">
                <div class="portfolio-hover-content">
                  <i class="fa fa-plus fa-3x"></i>
                </div>
              </div>
              <img class="img-fluid" src="pic/<?php echo $like_post["pic"];?>" width="400" alt="">
            </a>
            <!-- いいね部分 -->
            <div class="portfolio-caption">
              <?php if($like_post["login_like_flag"] == 0){ ?>
              <a href="like_buttton.php?like_post_id=<?php echo $like_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $like_post["like_count"] ;?> like</span>
              <!-- いいね取り消し部分 -->
              <?php }else{?>
              <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $like_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $like_post["like_count"]; ?> like</span>
              <?php }?>
              <!-- ここまでいいねいいね取り消し部分 -->
            </div>
          </div>
          <?php }?>
          <!--ここまで繰り返し部分  -->

          <!-- ロード部分 -->
          <div id="load" style="margin:0 auto;">
          <i class="fa fa-spinner fa-pulse fa-3x"></i>
          <!-- <span class="sr-only">Loading...</span> -->
          </div>
        </div>
      </div>
    </section>
    <!-- ここまでロード -->

    <!-- modal部分 -->
    <?php foreach ($like_list as $like_post) {?>
    <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $like_post["post_id"]; ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <!-- <h2 class="text-uppercase">Project Name</h2> -->
                  <!-- <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p> -->
                  <img class="img-fluid d-block mx-auto" src="pic/<?php echo $like_post["pic"];?>" alt="">
                  <div class="mypage-texts">
                    <span>Type</span>
                    <p><?php echo $like_post["type"]; ?></p>
                    <!-- <p></p> -->
                    <span>Category</span>
                    <p><?php echo $like_post["category_id"]; ?></p>
                    <!-- <p></p> -->
                    <span>場所</span>
                    <p><?php echo $like_post["place"]; ?></p>
                    <span>期間</span>
                    <p><?php echo $like_post["term"]; ?></p>
                    <span>backpack</span>
                    <p><?php echo $like_post["backpack"]; ?></p>
                    <span>重量</span>
                    <p><?php echo $like_post["weight"]; ?>kg</p>
                    <span>中身詳細</span>
                    <p><?php echo $like_post["detail"]; ?></p>
                  </div>
                  <!-- <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p> -->
                  <ul class="list-inline">
                    <li><?php 
                      $modify_date = $like_post["modified"];
                      // date関数　書式を時間に変更するとき
                      // strtotime 文字型(string)のデータを日時型に変換できる
                      // 24時間表記：H, 12時間表記：h　
                      $modify_date = date("Y-m-d H:i", strtotime($modify_date));
                     echo $modify_date ; ?></li>
                  </ul>
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Close</button>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php }?>
    <!-- Modal 2 -->
  
    <!-- Modal 3 -->
   <!--  <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body"> -->
                  <!-- Project Details Go Here -->
                <!--   <h2 class="text-uppercase">Project Name</h2>
                  <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/03-full.jpg" alt="">
                  <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                  <ul class="list-inline">
                    <li>Date: January 2017</li>
                    <li>Client: Finish</li>
                    <li>Category: Identity</li>
                  </ul>
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Close Project</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->

    <!-- Modal 4 -->
   

    <!-- Modal 5 -->
   

    <!-- Modal 6 -->
   

<!-- ここまでモーダル -->
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
            <!-- <ul class="list-inline social-buttons">
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-linkedin"></i>
                </a>
              </li>
            </ul> -->
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
    <!-- <script src="js/agency.min.js"></script> -->
	
	

  </body>

</html>
