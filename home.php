<?php
  require('dbconnect.php');

  // 投稿を取得するsql
  $sql = "SELECT `packingme_posts`.*,`packingme_users`.`user_name`,`picture_path` FROM`packingme_posts` INNER JOIN `packingme_users` ON `packingme_posts`.`user_id`=`packingme_users`.`id`ORDER BY `packingme_posts`.`modified` DESC";
  // sql実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  // フェッチ
  $post_list = array();

  while(1){
  $one_post = $stmt->fetch(PDO::FETCH_ASSOC);

    if($one_post == false){
      break;
    }
  $post_list[] = $one_post;
  }
// ここまで
  // ここからLIke数をカウントするSql
  $like_sql = "SELECT COUNT(*)as`like_count` FROM `packingme_likes` WHERE `post_id`=1";
  // sql実行
  $like_stmt = $dbh->prepare($like_sql);
  $like_stmt->execute();
  // フェッチ
  $like_number = $like_stmt->fetch(PDO::FETCH_ASSOC);
  $one_post["like_count"] = $like_number["like_count"];


?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Home</title>

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
              <a href="ranking.php">
                <img src="img/portfolio/crown.png">
              </a>
            </div>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="mypage.php?id=<?php echo $_GET["id"];?>">My Page</a>
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

    <!-- 右側固定部分 -->
    <div class="container-fluid display-table">
      <div class="row display-table-row">
        <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
          <div class="logo">
            <a href="home.html">Caregories</a>
          </div>
          <div class="navi">
            <ul>
              <li class=""><a href="#"><i class="fa fa-globe" aria-hidden="true"></i><span class="hidden-xs hidden-sm">２週間以上</span></a></li>
              <li><a href="#"><i class="fa fa-globe" aria-hidden="true"></i><span class="hidden-xs hidden-sm">２週間以内</span></a></li>
              <li><a href="#"><i class="fa fa-globe" aria-hidden="true"></i><span class="hidden-xs hidden-sm">１週間以内</span></a></li>
              <li><a href="#"><i class="fa fa-globe" aria-hidden="true"></i><span class="hidden-xs hidden-sm">３日 以内</span></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
        <!--ここまで右側固定部分  -->



<!-- 投稿画像表示部分 -->
    <section class="bg-light mypage-home" id="portfolio">
      <div class="container">

        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="section-heading text-uppercase">Timeline<?php echo $one_post["like_count"]; ?></h2>
            <h3 class="section-subheading text-muted"></h3>
          </div>
        </div>

<!-- 繰り返す部分 -->
        <?php foreach($post_list as $one_post){?>

        <div class="row">
          <div class="profile-container">
            <a class="profile-link" href="mypage.php">
              <img  class="image-with-link" src="picture_path/<?php echo $one_post["picture_path"];?>">
              <span class="name-with-link"><?php echo $one_post["user_name"];?></span>
            </a>
          </div>
          <div class="col-md-12 col-sm-12 portfolio-item">
            <a class="portfolio-link" data-toggle="modal" href="#portfolioModal1">
              <div class="portfolio-hover">
                <div class="portfolio-hover-content">
                  <i class="fa fa-plus fa-3x"></i>
                </div>
              </div>
              <img class="img-fluid  change-img-size" src="picture_path/<?php echo $one_post["pic"];?>" alt="">
            </a>
            <div class="portfolio-caption">
              <i class="fa fa-suitcase fa-2x"> like</i>
            </div>
          </div>
          <?php } ?>
<!-- ここまで -->

          <div class="profile-container">
            <a class="profile-link" href="mypage.php">
              <img  class="image-with-link" src="img/zakiyama.png">
              <span class="name-with-link">zakiyama</span>
            </a>
          </div>
          <div class="col-md-12 col-sm-12 portfolio-item">
            <a class="portfolio-link" data-toggle="modal" href="#portfolioModal3">
              <div class="portfolio-hover">
                <div class="portfolio-hover-content">
                  <i class="fa fa-plus fa-3x"></i>
                </div>
              </div>
              <img class="img-fluid" src="img/zaki.png" alt="">
            </a>
            <div class="portfolio-caption">
              <i class="fa fa-suitcase fa-2x"><span>200,000 like</span></i>
              <!-- <p class="text-muted">more</p> -->
            </div>
          </div>
          
          <div id="load" style="margin:0 auto;">
            <div ><i class="fa fa-spinner fa-pulse fa-3x"></i></div>
            <!-- <span class="sr-only">Loading...</span> -->
          </div>
        </div>
      </div>
    </section>
    <!-- ここまで画像表示部分 -->

    <!-- modal部分 -->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <img class="img-fluid d-block mx-auto" src="packing.png" alt="">
                  <div class="mypage-texts">
                    <span>Type</span>
                    <p><?php echo $one_post["user_name"];?></p>
                    <!-- <p></p> -->
                    <span>Category</span>
                    <p><?php echo $one_post["user_name"];?></p>
                    <!-- <p></p> -->
                    <span>場所</span>
                    <p><?php echo $one_post["user_name"];?></p>
                    <span>期間</span>
                    <p><?php echo $one_post["user_name"];?></p>
                    <span>backpack</span>
                    <p><?php echo $one_post["user_name"];?></p>
                    <span>重量</span>
                    <p><?php echo $one_post["user_name"];?></p>
                    <span>中身詳細</span>
                    <p><?php echo $one_post["user_name"];?></p>
                  </div>
                  <!-- <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p> -->
                  <ul class="list-inline">
                    <li>
                      <?php 
                        $modify_date = $one_post["modified"];
                        // strtotime 文字型のデータを日時型に変換できる
                        $modify_date = date("Y-m-d H:i",strtotime($modify_date));
                        echo $modify_date;   
                      ?>
                    </li>
                  </ul>
                  <!-- <div class="edit-delete">
                    <button class="delete-button">delete</button>
                    <button class="edit-button">edit</button>
                  </div> -->
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


<!-- ここまで投稿画像表示部分 -->

<!-- 
    <div>
      <div class="post-group top-top">
        <img class="post-img" src="img/header-bg.jpg" >
        <div class="post-text">
          <i class="fa fa-suitcase fa-green"></i><span class="badge badge-pill badge-default">10</span>
          <div class="post-coment">
            <p class="open-close">more..</p>
            <p class="coment answer">
              <img src="img/header-bg.jpg">
              <span class="user-name">Love extra Naoki</span>
              <span>categories</span><br>旅行（１週間以内）<br>
              <span>場所</span><br>アメリカ　ニューヨーク<br>
              <span>期間</span><br>4泊<br>
              <span>backpack</span><br>the north faith 45l<br>
              <span>重量</span><br>15kg<br>
              <span>中身詳細</span><br>
            macbookpro<br>
            gopro hero5<br>
            Ipad<br>
            omd-em-5 mark-2<br>
            t-thirts 3枚<br>
            pants 3枚<br>
            マッサージ割引券<br>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="post-group">
        <img class="post-img" src="img/header-bg.jpg" >
        <div class="post-text">
          <i class="fa fa-suitcase"></i><span class="badge badge-pill badge-default">10</span>
          <div class="post-coment">
            <p class="open-close">more..</p>
            <p class="coment answer">
              <img src="img/header-bg.jpg">
              <span class="user-name">Love extra Naoki</span>
              <span>categories</span><br>旅行（１週間以内）<br>
              <span>場所</span><br>アメリカ　ニューヨーク<br>
              <span>期間</span><br>4泊<br>
              <span>backpack</span><br>the north faith 45l<br>
              <span>重量</span><br>15kg<br>
              <span>中身詳細</span><br>
            macbookpro<br>
            gopro hero5<br>
            Ipad<br>
            omd-em-5 mark-2<br>
            t-thirts 3枚<br>
            pants 3枚<br>
            マッサージ割引券<br>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="post-group">
        <img class="post-img" src="img/header-bg.jpg" >
        <div class="post-text">
          <i class="fa fa-suitcase"></i><span class="badge badge-pill badge-default">10</span>
          <div class="post-coment">
            <p class="open-close">more..</p>
            <p class="coment answer">
              <img src="img/header-bg.jpg">
              <span class="user-name">Love extra Naoki</span>
              <span>categories</span><br>旅行（１週間以内）<br>
              <span>場所</span><br>アメリカ　ニューヨーク<br>
              <span>期間</span><br>4泊<br>
              <span>backpack</span><br>the north faith 45l<br>
              <span>重量</span><br>15kg<br>
              <span>中身詳細</span><br>
            macbookpro<br>
            gopro hero5<br>
            Ipad<br>
            omd-em-5 mark-2<br>
            t-thirts 3枚<br>
            pants 3枚<br>
            マッサージ割引券<br>
            </p>
          </div>
        </div>
      </div> -->
  <!--   </div>
 -->
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
    <script src="../packing_me.js"></script>
  </body>

</html>
