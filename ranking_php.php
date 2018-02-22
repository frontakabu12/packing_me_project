<?php 
  session_start();
  require('dbconnect.php');

  // 仮の数字 SESSIONに保存されているログインユーザーIDのこと
  $login_user_id = 10;
  // ログインユーザーIDからMembersテーブルとPostテーブルを結合して全件取得するsql
  $sql = "SELECT `packingme_posts`.*,`packingme_users`.`user_name`,`picture_path`, `packingme_likes`.`post_id`, packingme_likes.created - interval date_format(packingme_likes.created,'%w') day as each_week, COUNT(*) as `like_count` FROM`packingme_posts` INNER JOIN `packingme_users` ON `packingme_posts`.`user_id`=`packingme_users`.`id` INNER JOIN `packingme_likes` ON `packingme_posts`.`post_id`=`packingme_likes`.`post_id` WHERE `packingme_posts`.`created` BETWEEN (CURDATE() - INTERVAL 7 DAY) AND (CURDATE() + INTERVAL 1 DAY) GROUP BY `packingme_likes`.`post_id` ORDER BY `like_count` DESC";
  // 実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  // モーダル用
  $modal_sql = "SELECT `packingme_posts`.*,`packingme_users`.`user_name`,`picture_path`, `packingme_likes`.`post_id`, packingme_likes.created - interval date_format(packingme_likes.created,'%w') day as each_week, COUNT(*) as `like_count` FROM`packingme_posts` INNER JOIN `packingme_users` ON `packingme_posts`.`user_id`=`packingme_users`.`id` INNER JOIN `packingme_likes` ON `packingme_posts`.`post_id`=`packingme_likes`.`post_id` WHERE `packingme_posts`.`created` BETWEEN (CURDATE() - INTERVAL 7 DAY) AND (CURDATE() + INTERVAL 1 DAY) GROUP BY `packingme_likes`.`post_id` ORDER BY `like_count` DESC";
  // 実行
  $modal_stmt = $dbh->prepare($modal_sql);
  $modal_stmt->execute();

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
              <a href="ranking_php.php">
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
        <?php for ($i=0; $i < 9; $i++) { ?>
          <?php $packingme_posts[$i] = $stmt->fetch(PDO::FETCH_ASSOC); ?>
          <!-- ログインユーザーのいいね判定 -->
          <?php
            $login_user_sql = 'SELECT COUNT(*) as `user_like` FROM `packingme_likes` WHERE `user_id`=? AND `post_id`=?';
            $login_user_data = array($login_user_id, $packingme_posts[$i]['post_id']);
            $login_user_stmt = $dbh->prepare($login_user_sql);
            $login_user_stmt->execute($login_user_data);
            $login_user_like[$i] = $login_user_stmt->fetch(PDO::FETCH_ASSOC);
          ?>
            <?php if ($i == 0) { ?>
            <div class="col-lg-12  portfolio-item">
              <div class="big-crown">
              <img src="img/portfolio/crown.png" width="100" height="100" ><p>No.1</p></div>
            <div class="profile-container" id="<?php echo $packingme_posts[$i]["post_id"] ;?>">
              <a class="profile-link" href="mypage.php?user_id=<?php echo $packingme_posts[$i]["user_id"];?>">
                <img  class="image-with-link" src="picture_path/<?php echo $packingme_posts[$i]["picture_path"];?>">
                <span class="name-with-link"><?php echo $packingme_posts[$i]["user_name"];?></span>
              </a>
            </div>
              <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $packingme_posts[$i]["post_id"];?>">
                <div class="portfolio-hover">
                  <div class="portfolio-hover-content">
                    <i class="fa fa-plus fa-3x"></i>
                  </div>
                </div>
                <img class="img-fluid" src="pic/<?php echo $packingme_posts[$i]["pic"]; ?>" width="600" height="400" alt="">
              </a>
              <div class="portfolio-caption">
                <!-- いいね部分 -->
                <?php if($login_user_like[0]['user_like'] == 0){ ?>
                <a href="like_buttton.php?like_post_id=<?php echo $packingme_posts[$i]["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $packingme_posts[$i]["like_count"] ;?> like</span>
                <!-- いいね取り消し部分 -->
                <?php }else{?>
                <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $packingme_posts[$i]["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $packingme_posts[$i]["like_count"]; ?> like</span>
                <?php }?>
                <!-- ここまでいいねいいね取り消し部分 -->
              </div>
            </div>
            <?php } ?>

            <!-- 2位表示 -->
            <?php if ($i == 1) { ?>
            <div class="col-lg-12  portfolio-item">
              <div class="big-crown ranking_top">
              <img src="img/portfolio/icon-crown.png" width="100" height="100" ><p>No.2</p></div>
            <div class="profile-container" id="<?php echo $packingme_posts[$i]["post_id"] ;?>">
              <a class="profile-link" href="mypage.php?user_id=<?php echo $packingme_posts[$i]["user_id"];?>">
                <img  class="image-with-link" src="picture_path/<?php echo $packingme_posts[$i]["picture_path"];?>">
                <span class="name-with-link"><?php echo $packingme_posts[$i]["user_name"];?></span>
              </a>
            </div>
              <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $packingme_posts[$i]["post_id"];?>">
                <div class="portfolio-hover">
                  <div class="portfolio-hover-content">
                    <i class="fa fa-plus fa-3x"></i>
                  </div>
                </div>
                <img class="img-fluid" src="pic/<?php echo $packingme_posts[$i]["pic"]; ?>" width="600" height="400" alt="">
              </a>
              <div class="portfolio-caption">
                <!-- いいね部分 -->
                <?php if($login_user_like[$i]['user_like'] == 0){ ?>
                <a href="like_buttton.php?like_post_id=<?php echo $packingme_posts[$i]["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $packingme_posts[$i]["like_count"] ;?> like</span>
                <!-- いいね取り消し部分 -->
                <?php }else{?>
                <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $packingme_posts[$i]["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $packingme_posts[$i]["like_count"]; ?> like</span>
                <?php }?>
                <!-- ここまでいいねいいね取り消し部分 -->
              </div>
            </div>
            <?php } ?>

            <!-- 3位表示 -->
            <?php if ($i == 2) { ?>
            <div class="col-lg-12  portfolio-item">
              <div class="big-crown ranking_top">
              <img src="img/portfolio/icon-crown.png" width="100" height="100" ><p>No.3</p></div>
            <div class="profile-container" id="<?php echo $packingme_posts[$i]["post_id"] ;?>">
              <a class="profile-link" href="mypage.php?user_id=<?php echo $packingme_posts[$i]["user_id"];?>">
                <img  class="image-with-link" src="picture_path/<?php echo $packingme_posts[$i]["picture_path"];?>">
                <span class="name-with-link"><?php echo $packingme_posts[$i]["user_name"];?></span>
              </a>
            </div>
              <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $packingme_posts[$i]["post_id"];?>">
                <div class="portfolio-hover">
                  <div class="portfolio-hover-content">
                    <i class="fa fa-plus fa-3x"></i>
                  </div>
                </div>
                <img class="img-fluid" src="pic/<?php echo $packingme_posts[$i]["pic"]; ?>" width="600" height="400" alt="">
              </a>
              <div class="portfolio-caption">
                <!-- いいね部分 -->
                <?php if($login_user_like[$i]['user_like'] == 0){ ?>
                <a href="like_buttton.php?like_post_id=<?php echo $packingme_posts[$i]["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $packingme_posts[$i]["like_count"] ;?> like</span>
                <!-- いいね取り消し部分 -->
                <?php }else{?>
                <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $packingme_posts[$i]["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $packingme_posts[$i]["like_count"]; ?> like</span>
                <?php }?>
                <!-- ここまでいいねいいね取り消し部分 -->
              </div>
            </div>
            <?php } ?>

          <!-- 4位〜9位表示 -->
          <?php if ($i >= 3) { ?>
          <div class="col-md-4 col-sm-6  portfolio-item">
            <div class="small-crown">
              <img src="img/portfolio/small-crown.png" width="60" height="60" ><p>No.<?php echo $i+1; ?></p></div>
          <div class="profile-container2" id="<?php echo $packingme_posts[$i]["post_id"] ;?>">
              <a class="profile-link" href="mypage.php?user_id=<?php echo $packingme_posts[$i]["user_id"];?>">
                <img  class="image-with-link" src="picture_path/<?php echo $packingme_posts[$i]["picture_path"];?>">
                <span class="name-with-link"><?php echo $packingme_posts[$i]["user_name"];?></span>
              </a>
            </div>
              <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $packingme_posts[$i]["post_id"];?>">
                <div class="portfolio-hover">
                  <div class="portfolio-hover-content">
                    <i class="fa fa-plus fa-3x"></i>
                  </div>
                </div>
                <img class="img-fluid" src="pic/<?php echo $packingme_posts[$i]["pic"]; ?>" width="350" height="300" alt="">
              </a>
              <div class="portfolio-caption">
                <!-- いいね部分 -->
                <?php if($login_user_like[$i]['user_like'] == 0){ ?>
                <a href="like_buttton.php?like_post_id=<?php echo $packingme_posts[$i]["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" style="color:#d4cfc0; "></i></a><span style="font-size:2em;line-height:2em;"><?php echo $packingme_posts[$i]["like_count"] ;?> like</span>
                <!-- いいね取り消し部分 -->
                <?php }else{?>
                <a class="unlike" href="like_buttton.php?unlike_post_id=<?php echo $packingme_posts[$i]["post_id"] ;?>"><i class="fa fa-suitcase fa-2x" ></i></a><span style="font-size:2em;line-height:2em;"><?php echo $packingme_posts[$i]["like_count"]; ?> like</span>
                <?php }?>
                <!-- ここまでいいねいいね取り消し部分 -->
              </div>
            </div>
          <?php } ?>
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

    <!-- Modal 1位表示 -->
    <?php for ($i=0; $i < 9; $i++) { ?>
      <?php $packingme_posts[$i] = $modal_stmt->fetch(PDO::FETCH_ASSOC); ?>
      <?php if($i == 0){ ?>
      <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $packingme_posts[$i]["post_id"];?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <img class="img-fluid d-block mx-auto" src="pic/<?php echo $packingme_posts[$i]["pic"]; ?>" alt="">
                  <div class="mypage-texts">
                    <span>Type</span>
                    <p><?php echo $packingme_posts[$i]["type"]; ?></p>
                    <!-- <p></p> -->
                    <span>Category</span>
                    <p><?php echo $packingme_posts[$i]["category_id"]; ?></p>
                    <!-- <p></p> -->
                    <span>場所</span>
                    <p><?php echo $packingme_posts[$i]["place"]; ?></p>
                    <span>期間</span>
                    <p><?php echo $packingme_posts[$i]["term"]; ?></p>
                    <span>backpack</span>
                    <p><?php echo $packingme_posts[$i]["backpack"]; ?></p>
                    <span>重量</span>
                    <p><?php echo $packingme_posts[$i]["weight"]; ?></p>
                    <span>中身詳細</span>
                    <p><?php echo $packingme_posts[$i]["detail"]; ?></p>
                  </div>
                  <ul class="list-inline">
                    <li><?php 
                      $modify_date = $packingme_posts[$i]["modified"];
                      // date関数　書式を時間に変更するとき
                      // strtotime 文字型(string)のデータを日時型に変換できる
                      // 24時間表記：H, 12時間表記：h　
                      $modify_date = date("Y-m-d H:i", strtotime($modify_date));
                     echo $modify_date ; ?></li>
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
    <?php } ?>

    <!-- Modal 2位表示 -->
    <?php if($i == 1){ ?>
    <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $packingme_posts[$i]["post_id"];?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <img class="img-fluid d-block mx-auto" src="pic/<?php echo $packingme_posts[$i]["pic"]; ?>" alt="">
                  <div class="mypage-texts">
                    <span>Type</span>
                    <p><?php echo $packingme_posts[$i]["type"]; ?></p>
                    <!-- <p></p> -->
                    <span>Category</span>
                    <p><?php echo $packingme_posts[$i]["category_id"]; ?></p>
                    <!-- <p></p> -->
                    <span>場所</span>
                    <p><?php echo $packingme_posts[$i]["place"]; ?></p>
                    <span>期間</span>
                    <p><?php echo $packingme_posts[$i]["term"]; ?></p>
                    <span>backpack</span>
                    <p><?php echo $packingme_posts[$i]["backpack"]; ?></p>
                    <span>重量</span>
                    <p><?php echo $packingme_posts[$i]["weight"]; ?></p>
                    <span>中身詳細</span>
                    <p><?php echo $packingme_posts[$i]["detail"]; ?></p>
                  </div>
                  <ul class="list-inline">
                    <li><?php 
                      $modify_date = $packingme_posts[$i]["modified"];
                      // date関数　書式を時間に変更するとき
                      // strtotime 文字型(string)のデータを日時型に変換できる
                      // 24時間表記：H, 12時間表記：h　
                      $modify_date = date("Y-m-d H:i", strtotime($modify_date));
                     echo $modify_date ; ?></li>
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
    <?php } ?>

    <!-- Modal 3位表示 -->
    <?php if($i == 2){ ?>
    <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $packingme_posts[$i]["post_id"];?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <img class="img-fluid d-block mx-auto" src="pic/<?php echo $packingme_posts[$i]["pic"]; ?>" alt="">
                  <div class="mypage-texts">
                    <span>Type</span>
                    <p><?php echo $packingme_posts[$i]["type"]; ?></p>
                    <!-- <p></p> -->
                    <span>Category</span>
                    <p><?php echo $packingme_posts[$i]["category_id"]; ?></p>
                    <!-- <p></p> -->
                    <span>場所</span>
                    <p><?php echo $packingme_posts[$i]["place"]; ?></p>
                    <span>期間</span>
                    <p><?php echo $packingme_posts[$i]["term"]; ?></p>
                    <span>backpack</span>
                    <p><?php echo $packingme_posts[$i]["backpack"]; ?></p>
                    <span>重量</span>
                    <p><?php echo $packingme_posts[$i]["weight"]; ?></p>
                    <span>中身詳細</span>
                    <p><?php echo $packingme_posts[$i]["detail"]; ?></p>
                  </div>
                  <ul class="list-inline">
                    <li><?php 
                      $modify_date = $packingme_posts[$i]["modified"];
                      // date関数　書式を時間に変更するとき
                      // strtotime 文字型(string)のデータを日時型に変換できる
                      // 24時間表記：H, 12時間表記：h　
                      $modify_date = date("Y-m-d H:i", strtotime($modify_date));
                     echo $modify_date ; ?></li>
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
    <?php } ?>

    <!-- Modal 4〜9位表示 -->
    <?php if($i >= 3){ ?>
    <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $packingme_posts[$i]["post_id"];?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <img class="img-fluid d-block mx-auto" src="pic/<?php echo $packingme_posts[$i]["pic"]; ?>" alt="">
                  <div class="mypage-texts">
                    <span>Type</span>
                    <p><?php echo $packingme_posts[$i]["type"]; ?></p>
                    <!-- <p></p> -->
                    <span>Category</span>
                    <p><?php echo $packingme_posts[$i]["category_id"]; ?></p>
                    <!-- <p></p> -->
                    <span>場所</span>
                    <p><?php echo $packingme_posts[$i]["place"]; ?></p>
                    <span>期間</span>
                    <p><?php echo $packingme_posts[$i]["term"]; ?></p>
                    <span>backpack</span>
                    <p><?php echo $packingme_posts[$i]["backpack"]; ?></p>
                    <span>重量</span>
                    <p><?php echo $packingme_posts[$i]["weight"]; ?></p>
                    <span>中身詳細</span>
                    <p><?php echo $packingme_posts[$i]["detail"]; ?></p>
                  </div>
                  <ul class="list-inline">
                    <li><?php 
                      $modify_date = $packingme_posts[$i]["modified"];
                      // date関数　書式を時間に変更するとき
                      // strtotime 文字型(string)のデータを日時型に変換できる
                      // 24時間表記：H, 12時間表記：h　
                      $modify_date = date("Y-m-d H:i", strtotime($modify_date));
                     echo $modify_date ; ?></li>
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
    <?php } ?>
    <?php } ?>

    

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
