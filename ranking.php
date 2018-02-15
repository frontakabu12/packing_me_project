<?php 
  session_start();
  require('dbconnect.php');

  // ログインユーザーIDからMembersテーブルとPostテーブルを結合して全件取得するsql
  $sql = "SELECT `packingme_posts`.*,`packingme_users`.`user_name`,`picture_path`, `packingme_likes`.`post_id` , COUNT(*)as `like_count`
          FROM`packingme_posts` 
          INNER JOIN `packingme_users` ON `packingme_posts`.`user_id`=`packingme_users`.`id`
          INNER JOIN `packingme_likes` ON `packingme_posts`.`post_id`=`packingme_likes`.`post_id`  
          GROUP BY `packingme_likes`.`post_id`
          ORDER BY `like_count` DESC LIMIT 9";
  // 実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  // フェッチ
  $post_list = array();
  
  


  while(1){
    // $for_login_user = $stmt->fetch(PDO::FETCH_ASSOC);
    // $one_post = $stmt->fetch(PDO::FETCH_ASSOC);
    if($for_login_user == false){
      break;
    }else{
    // LIKE数を求めるSQL文作成
      // $like_sql = "SELECT COUNT(*)as`like_count` FROM `packingme_likes` WHERE `post_id`=".$for_login_user["post_id"];

      // $like_sql = "SELECT `post_id`, COUNT(*) AS `like_count` FROM `packingme_likes` GROUP BY `post_id` ORDER BY `like_count` DESC";

      // // Sql実行
      // $like_stmt = $dbh->prepare($like_sql);
      // $like_stmt->execute();

      // $like_number = $like_stmt->fetch(PDO::FETCH_ASSOC);

//一行ぶんのデータに新しいキーを用意してLIKE数を代入 
      // $for_login_user["like_count"] = $like_number["like_count"];

//ログインしている人がLIKEしているかどうかの情報を取得
      $login_like_sql = "SELECT COUNT(*)as`like_flag` FROM `packingme_likes` WHERE `post_id`=".$for_login_user["post_id"]." AND `user_id`=".$_SESSION["id"]; 

// SQL実行
      $login_like_stmt = $dbh->prepare($login_like_sql);
      $login_like_stmt->execute();

// フェッチして取得
      $login_like_number = $login_like_stmt->fetch(PDO::FETCH_ASSOC);

      // echo "<pre>";
      // var_dump($login_like_number['like_flag']);exit;
      // echo "</pre>";

      $for_login_user["login_like_flag"] = $login_like_number["like_flag"];
      // データ取得できている      $tweet_list[] = $one_tweet;
      $post_list[] = $for_login_user;
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

    <title>Packing Me -Ranking</title>

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
              <a href="ranking.php">
                <img src="img/portfolio/crown.png">
              </a>
            </div>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="mypage.php?user_id=<?php echo $_SESSION["id"]; ?>">My page</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="post.php">投稿する</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="home.php">Log out</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- ランキング順投稿 -->
    <section class="bg-light" id="portfolio">
      <div class="container">
        <div class="row">

          <div class="col-lg-12 text-center">
            <h2 class="section-heading text-uppercase">Weekly Ranking</h2>
            <h3 class="section-subheading text-muted"></h3>
          </div>
        </div>
        <div class="row">
          <!-- 1位表示 -->
          <?php for ($i=1; $i <2 ; $i++) { 
            $one_post = $stmt->fetch(PDO::FETCH_ASSOC); ?>
          <div class="col-lg-12  portfolio-item">
            
            <div class="big-crown">
            <img src="img/portfolio/crown.png" width="100" height="100" ><p>No.1</p></div>
            <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $one_post["post_id"];?>">
              <div class="portfolio-hover">
                <div class="portfolio-hover-content">
                  <i class="fa fa-plus fa-3x"></i>
                </div>
              </div>
              <img class="img-fluid" src="pic/<?php echo $one_post["pic"]; ?>" width="600" height="400" alt="">
            </a>
            <div class="portfolio-caption">
              <!-- いいね部分 -->
              <?php if($one_post["login_like_flag"] == 0){ ?>
              <a href="like_buttton.php?like_post_id=<?php echo $one_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $one_post["like_count"] ;?> like</span>
              <!-- いいね取り消し部分 -->
              <?php }else{?>
              <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $one_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $one_post["like_count"]; ?> like</span>
              <?php }?>
              <!-- ここまでいいねいいね取り消し部分 -->
            </div>
          </div>
          <?php } ?>

          <!-- 2位表示 -->
          <?php for ($i=2; $i <3 ; $i++) { 
            $one_post = $stmt->fetch(PDO::FETCH_ASSOC); ?>
          <div class="col-lg-12  portfolio-item">
            
            <div class="big-crown ranking_top">
            <img src="img/portfolio/icon-crown.png" width="100" height="100" ><p>No.2</p></div>
            <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $one_post["post_id"];?>">
              <div class="portfolio-hover">
                <div class="portfolio-hover-content">
                  <i class="fa fa-plus fa-3x"></i>
                </div>
              </div>
              <img class="img-fluid" src="pic/<?php echo $one_post["pic"]; ?>" width="600" height="400" alt="">
            </a>
            <div class="portfolio-caption">
              <!-- いいね部分 -->
              <?php if($one_post["login_like_flag"] == 0){ ?>
              <a href="like_buttton.php?like_post_id=<?php echo $one_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $one_post["like_count"] ;?> like</span>
              <!-- いいね取り消し部分 -->
              <?php }else{?>
              <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $one_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $one_post["like_count"]; ?> like</span>
              <?php }?>
              <!-- ここまでいいねいいね取り消し部分 -->
            </div>
          </div>
          <?php } ?>

          <!-- 3位表示 -->
          <?php for ($i=3; $i <4 ; $i++) { 
            $one_post = $stmt->fetch(PDO::FETCH_ASSOC); ?>
          <div class="col-lg-12  portfolio-item">
            <div class="big-crown ranking_top">
            <img src="img/portfolio/icon-crown.png" width="100" height="100" ><p>No.3</p></div>
            <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $one_post["post_id"];?>">
              <div class="portfolio-hover">
                <div class="portfolio-hover-content">
                  <i class="fa fa-plus fa-3x"></i>
                </div>
              </div>
              <img class="img-fluid" src="pic/<?php echo $one_post["pic"]; ?>" width="600" height="400" alt="">
            </a>
            <div class="portfolio-caption">
              <!-- いいね部分 -->
              <?php if($one_post["login_like_flag"] == 0){ ?>
              <a href="like_buttton.php?like_post_id=<?php echo $one_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $one_post["like_count"] ;?> like</span>
              <!-- いいね取り消し部分 -->
              <?php }else{?>
              <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $one_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $one_post["like_count"]; ?> like</span>
              <?php }?>
              <!-- ここまでいいねいいね取り消し部分 -->
            </div>
          </div>
          <?php } ?>

          <!-- 4位〜9位表示 -->
          <?php for ($i=4; $i <10 ; $i++) { 
            $one_post = $stmt->fetch(PDO::FETCH_ASSOC); ?>
          <div class="col-md-4 col-sm-6  portfolio-item">
            <div class="small-crown">
            <img src="img/portfolio/small-crown.png" width="60" height="60" ><p>No.<?php echo $i; ?></p></div>
            <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $one_post["post_id"];?>">
              <div class="portfolio-hover">
                <div class="portfolio-hover-content">
                  <i class="fa fa-plus fa-3x"></i>
                </div>
              </div>
              <img class="img-fluid" src="pic/<?php echo $one_post["pic"]; ?>" width="350" height="300" alt="">
            </a>
            <div class="portfolio-caption">
              <!-- いいね部分 -->
              <?php if($one_post["login_like_flag"] == 0){ ?>
              <a href="like_buttton.php?like_post_id=<?php echo $one_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $one_post["like_count"] ;?> like</span>
              <!-- いいね取り消し部分 -->
              <?php }else{?>
              <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $one_post["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $one_post["like_count"]; ?> like</span>
              <?php }?>
              <!-- ここまでいいねいいね取り消し部分 -->
            </div>
          </div>
          <?php } ?>
          <!-- 繰り返し終了 -->
        </div>
      </div>
    </section>


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

    <!-- Portfolio Modals -->

    <!-- Modal 1 -->
      <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $one_post["post_id"];?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <img class="img-fluid d-block mx-auto" src="zaki.png" alt="">
                  <div class="mypage-texts">
                    <span>Type</span>
                    <p>Traveler</p>
                    <!-- <p></p> -->
                    <span>Category</span>
                    <p>2週間以内</p>
                    <!-- <p></p> -->
                    <span>場所</span>
                    <p>フィリピン　セブ島</p>
                    <span>期間</span>
                    <p>４日間</p>
                    <span>backpack</span>
                    <p>the north face Caelus 35L</p>
                    <span>重量</span>
                    <p>13kg</p>
                    <span>中身詳細</span>
                    <p>mackbookpro, dji spark, omd-em5 mark2, t-shirt 3, pant 3</p>
                  </div>
                  <!-- <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p> -->
                  <ul class="list-inline">
                    <li>29 January 2018</li>
                  </ul>
                  <div class="edit-delete">
                    <button class="delete-button">delete</button>
                    <button class="edit-button">edit</button>
                  </div>
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
    

    <!-- Modal 2 -->
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <h2 class="text-uppercase">Project Name</h2>
                  <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/02-full.jpg" alt="">
                  <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                  <ul class="list-inline">
                    <li>Date: January 2017</li>
                    <li>Client: Explore</li>
                    <li>Category: Graphic Design</li>
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
    </div>

    <!-- Modal 3 -->
    <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <h2 class="text-uppercase">Project Name</h2>
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
    </div>

    <!-- Modal 4 -->
    <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <h2 class="text-uppercase">Project Name</h2>
                  <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/04-full.jpg" alt="">
                  <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                  <ul class="list-inline">
                    <li>Date: January 2017</li>
                    <li>Client: Lines</li>
                    <li>Category: Branding</li>
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
    </div>

    <!-- Modal 5 -->
    <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <h2 class="text-uppercase">Project Name</h2>
                  <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/05-full.jpg" alt="">
                  <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                  <ul class="list-inline">
                    <li>Date: January 2017</li>
                    <li>Client: Southwest</li>
                    <li>Category: Website Design</li>
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
    </div>

    <!-- Modal 6 -->
    <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <h2 class="text-uppercase">Project Name</h2>
                  <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/06-full.jpg" alt="">
                  <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                  <ul class="list-inline">
                    <li>Date: January 2017</li>
                    <li>Client: Window</li>
                    <li>Category: Photography</li>
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
    </div>

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
