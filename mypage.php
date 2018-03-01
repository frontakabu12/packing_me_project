<?php
  session_start();
  require('dbconnect.php');

   // ページング処理ーーーーーーーーーー
// 
  $page = "";

  // パラメータが存在していたらページ番号代入
  if(isset($_GET["page"])){
    $page = $_GET["page"];
  }else{
    // 存在していない時はページ番号を１とする
    $page = 1;
  }

  // 投稿があるかないかの判定
  // $sql = "SELECT * FROM `packingme_posts` WHERE
  //  `user_id`=".$_GET['user_id']."";
  // $stmt = $dbh->prepare($sql);
  // $stmt->execute();
  // $post = $stmt->fetch(PDO::FETCH_ASSOC);
  // var_dump($post);


  // １以下のイレギュラーな数字が入ってきたときページ番号を強制的に１にする
    // Max カンマ区切りで羅列された数字の中から最大の数字を取得
  $page = max($page,1);


  // １ページ分の表示件数
  $page_row = 4;

  // データの件数から最大ページ数を計算する
  $page_sql = "SELECT COUNT(*) AS `cnt` FROM`packingme_posts`WHERE `user_id`=".$_GET["user_id"];
  $page_stmt = $dbh->prepare($page_sql);
  $page_stmt->execute();

  $record_count = $page_stmt->fetch(PDO::FETCH_ASSOC);
  // ceil 小数点の切り上げ
  $all_page_number = ceil($record_count['cnt'] / $page_row);

  // パラメータのページ番号が最大ページを超えていれば強雨静的に最後のページとする
  // min カンマ区切りの数字の羅列の中から、最小の数字を取得する
  $page = min($page,$all_page_number);

  // 表皮するデータを取得開始場所
  $start = ($page-1)*$page_row;




  if(isset($_GET) && !empty($_GET)){
    $p_sql ="SELECT * FROM`packingme_users`WHERE`id`=".$_GET["user_id"];
    $p_stmt = $dbh->prepare($p_sql);
    $p_stmt->execute();
    $user = $p_stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($user);exit;
  }
  // if(!empty($_SESSION)){
  //   // 一行データ取得するsql
  //   $one_sql = "SELECT * FROM`packingme_users`WHERE`id`=".$_SESSION["id"];
  //   // sql実行
  //   $one_stmt = $dbh->prepare($one_sql);
  //   $one_stmt->execute();
  //   // フェッチ
  //   $user = $one_stmt->fetch(PDO::FETCH_ASSOC);
  // }


  // 個人の投稿を取得するsql
  $sql = "SELECT * FROM`packingme_posts`WHERE`user_id`=? 
          ORDER BY `packingme_posts`.`modified` DESC LIMIT ".$start.",4";
  // sql実行
  $data = array($_GET["user_id"]);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  // フェッチ
  $post_list = array();

  while(1){
  $one_post = $stmt->fetch(PDO::FETCH_ASSOC);

    if($one_post == false){
      break;
    }else{
  // LIKE数を求めるSQL文作成
      $like_sql = "SELECT COUNT(*)as`like_count` FROM `packingme_likes` WHERE `post_id`=".$one_post["post_id"];

      // Sql実行
      $like_stmt = $dbh->prepare($like_sql);
      $like_stmt->execute();

      $like_number = $like_stmt->fetch(PDO::FETCH_ASSOC);
// one_tweetの中身
// one_tweet["tweet"]つぶやき
// one_tweet["member_id"]つぶやいた人のID
// one_tweet["nick_name"]つぶやいた人のニックネーム
// one_tweet["picture_path"]つぶやいた人のプロフィール画像
// one_tweet["modified"]つぶやいた日時

//一行ぶんのデータに新しいキーを用意してLIKE数を代入 
      $one_post["like_count"] = $like_number["like_count"];

//ログインしている人がLIKEしているかどうかの情報を取得
      $login_like_sql = "SELECT COUNT(*)as`like_flag` FROM `packingme_likes` WHERE `post_id`=".$one_post["post_id"]." AND `user_id`=".$_SESSION["id"]; 

// SQL実行
      $login_like_stmt = $dbh->prepare($login_like_sql);
      $login_like_stmt->execute();

// フェッチして取得
      $login_like_number = $login_like_stmt->fetch(PDO::FETCH_ASSOC);


      $one_post["login_like_flag"] = $login_like_number["like_flag"];


      $post_list[] = $one_post;  
    }
  }
?> 

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>my page</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

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
              <a class="nav-link js-scroll-trigger" href="mypage.php?user_id=<?php echo $_SESSION["id"];?>">My Page</a>
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
<!-- 右側固定部分 -->
    <div class="right-right">
      <div class="right-caram">
        <div class="profile-imgs">
          <div class="top-image"><img src="picture_path/<?php echo $user["picture_path"];?>"></div>

          <h3><?php echo $user["user_name"];?></h3>
        </div>
        <br>        
        <div class="profile-texts">
          <div style="color:#c9cbe0; font-size: 15px;"><center>SELF INTRODUCE</center></div>
          <p>
          <center><?php echo $user["self_intro"];?></center>
          </p>
        </div>
        <div class="profile-texts">
          <div style="color:#c9cbe0;"><center>WEBSITE</center></div>

          <span><?php echo $user["web_site"];?></span>
        </div>
        <?php if($_GET["user_id"] == $_SESSION["id"]){ ?>
          <div class="buttons">
            <div class="be-center"><a href="like.php"><button class="like-button">いいね一覧</button></a></div>
            <br>
            <div class="be-center"><a href="edit_profile.php"><button class="edit-prof-button">プロフィールを編集する</button></a></div>
          </div>
        <?php }?>
      </div>
    </div>
<!--  ここまで右側固定部分-->
<!-- 画像部分 -->
    <div class="content">
    </div>

    <section class="bg-light mypage" id="portfolio">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="section-heading text-uppercase">Posts</h2>
            <h3 class="section-subheading text-muted"></h3>
          </div>
        </div>
        <div class="row">


          <!-- 繰り返し部分 -->
          <?php foreach($post_list as $one_post){?>
          <div class="col-md-6 col-sm-6 portfolio-item">
            <a class="portfolio-link" data-toggle="modal" href="#portfolioModal<?php echo $one_post["modified"];?>">
              <div class="portfolio-hover">
                <div class="portfolio-hover-content">
                  <i class="fa fa-plus fa-3x"></i>
                </div>
              </div>
              <img class="img-fluid" max width="400px" src="pic/<?php echo $one_post["pic"];?>" alt="">
            </a>
            <div class="portfolio-caption">


              <?php if($one_post["login_like_flag"]>0){?>

                <a id="change" data-flag="<?php echo $one_post["login_like_flag"]; ?>" data-post="<?php echo $one_post["post_id"];?>" class="like_btn"><i style="font-size: 30px;" class="fa fa-suitcase button_active"></i>
                <div class="counter"><?php echo $one_post["like_count"];?></div></a>

                <?php }else{?>
                <a id="change" data-flag="<?php echo $one_post["login_like_flag"]; ?>" data-post="<?php echo $one_post["post_id"]; ?>" class="like_btn"><i style="font-size: 30px;" class="fa fa-suitcase"></i><div class="counter"><?php echo $one_post["like_count"];?></div></a>
      
              <?php }?>


            </div>
          </div>
          <?php }?>
          <!--ここまで繰り返し部分  -->
           <!-- ここまで画像表示部分 -->

          <!-- ロード部分 -->

        </div>
      </div>
      <ul class="pager">

        <?php if($page == 1){?>
        <li class="change-page-btn"><</li>
        <?php }else{?>
        <li class="active-li"><a  href="mypage.php?page=<?php echo $page-1;?>&user_id=<?php echo $user["id"];?>"><</a></li>
        <?php }?>

        <?php for($i=1;$i<=$all_page_number;$i++){?>
        <li class="active-li"><a href="mypage.php?page=<?php echo $i;?>&user_id=<?php echo $user["id"];?>"><?php echo $i;?></a></li>
        <?php  } ?>

        <?php if($page == $all_page_number){?>
        <li class="change-page-btn">></li>
        <?php }else{?>
        <li class="active-li"><a  href="mypage.php?page=<?php echo $page+1;?>&user_id=<?php echo $all_page_number;?>">></a></li>
        <?php }?>

      </ul>
    </section>

    <!-- ここまでロード -->

    <!-- modal部分 -->
    <?php foreach ($post_list as $one_post) {?>
    <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $one_post["modified"]; ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <img class="img-fluid d-block mx-auto" src="pic/<?php echo $one_post["pic"];?>" alt="">
                  <div class="mypage-texts">
                    <span>Type</span>
                    <p><?php echo $one_post["type"]; ?></p>
                    <!-- <p></p> -->
                    <span>Category</span>
  
                      <?php if($one_post["category_id"]==1){?>
                        <p>1ヶ月以上</p>
                      <?php }?>
                      <?php if($one_post["category_id"]==2){?>
                        <p>2週間以上</p>
                      <?php }?>
                      <?php if($one_post["category_id"]==3){?>
                        <p>2週間以内</p>
                      <?php }?>
                      <?php if($one_post["category_id"]==4){?>
                        <p>1週間以内</p>
                      <?php }?>
                      <?php if($one_post["category_id"]==5){?>
                        <p>3日以内</p>
                      <?php }?>

                    <!-- <p></p> -->
                    <span>場所</span>
                    <p><?php echo $one_post["place"]; ?></p>
                    <span>期間</span>
                    <p><?php echo $one_post["term"]; ?></p>
                    <span>backpack</span>
                    <p><?php echo $one_post["backpack"]; ?></p>
                    <span>重量</span>
                    <p><?php echo $one_post["weight"]; ?>kg</p>
                    <span>中身詳細</span>
                    <p><?php echo $one_post["detail"]; ?></p>
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
                  <div class="edit-delete">
                    <button href="delete.php?post_id=<?php echo$one_post["post_id"];?>" class="delete-button" onclick="return confirm('削除します、よろしいですか？')">delete</button>
                    <a href="edit_post.php?post_id=<?php echo$one_post["post_id"]?>"><button class="edit-button">edit</button>
                  </div></a>
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
    <script src="js/jscroll.js"></script>
    <script src="js/jquery.jscroll.js"></script>
    <script src="js/packing_me.js"></script>
    <script>

      var like_post_id = 0;
      var login_like_flag = $(this).attr('data-flag');
      console.log(like_post_id);
      console.log(login_like_flag);

      $(function(){



        $('.like_btn').click(function(){
          like_post_id = $(this).attr('data-post');
          login_like_flag = $(this).attr('data-flag');
          var like_count = $(this).find('.counter').text();
          console.log(like_count);

          console.log(like_post_id);
          console.log(login_like_flag);



        if(login_like_flag > 0){

          console.log("消した");
          unlikeButton(like_post_id);

          console.log(login_like_flag);
        
          login_like_flag = $(this).attr('data-flag',0);
          $(this).find('.fa-suitcase').removeClass('button_active');
          $(this).find('.counter').text(Number(like_count)-1);
          

        }else if(login_like_flag == 0){
          console.log("押した");
          likeButton(like_post_id);

          console.log(login_like_flag)
          // login_like_flag++;
          login_like_flag = $(this).attr('data-flag',1);
          $(this).find('.fa-suitcase').addClass('button_active');
          $(this).find('.counter').text(Number(like_count)+1);



        }})
      });



      function likeButton(like_post_id){
        $(function(){
  // ajaxのGet送信メソッドでMysql用のphpへ飛ばす
          $.get("like_buttton.php",{
            like_post_id: like_post_id,
          },
          function (){
            console.log('like_button.phpへ移動');
          //window.open('json_map.pp','_self');
          }
          );
        });
      }
      function unlikeButton(unlike_post_id){
        $(function(){
  // ajaxのGet送信メソッドでMysql用のphpへ飛ばす
          $.get("like_buttton.php",{
            unlike_post_id: like_post_id,
          },
          function (){
            console.log('like_button.phpへ移動unlike処理');
        //window.open('json_map.pp','_self');
          }
          );
        });
      }
    </script>
  </body>

</html>
