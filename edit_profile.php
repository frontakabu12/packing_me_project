<?php 
  session_start();
  require ('dbconnect.php');

  $sql ="SELECT * FROM`packingme_users` WHERE `packingme_users`.`id`=".$_SESSION["id"];
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC); 

  $ext = substr($_FILES['picture_path']['name'], -3);

  if(isset($_POST) && !empty($_POST)){
    // 入力チェック
// ニックネーム空だったら
    // nicknameはblankだったというマークを保存
    if ($_POST["user_name"] == ''){

      $error["user_name"] = 'blank';
    }

    // Email
    if ($_POST["email"] == ''){

      $error["email"] = 'blank';
    }

    // self_intro
    if ($_POST["self_intro"] == ''){

      $error["self_intro"] = 'blank';
    }
// 入力チェック後エラーがなければCheck.phpに移動
    // $errorが存在してなかったら入力は正常と認識
    if (!isset($error)) {

      // EMAILの重複チェック
      // DBに同じEmailの登録があるか確認
      try{
// 検索条件にヒットした件数を取得するSQL文
        // COUNT()_はSqlの関数　ヒットした数を取得
        // as 別名　取得したデータに別な名前をつけて扱いやすくする

        $check_sql = "SELECT COUNT(*) as`cnt` FROM`packingme_users` WHERE`email`=?";

        // sql実行
        $check_data = array($_POST["email"]);
        $check_stmt = $dbh->prepare($check_sql);
        $check_stmt->execute($check_data);
// 件数取得
        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        if($count['cnt'] > 0){
          // 重複エラー
          $error['email'] = "duplicated";
        }
      }catch(Eception $e){

      }
    }


      if(!isset($error)){

        if(($ext == 'png') || ($ext == 'jpg') || ($ext == 'gif')|| ($ext == 'JPG')){
    // 画像のアップロード処理
       $pic_name = date('YmdHis').$_FILES['picture_path']['name'];
    // アップロード
        move_uploaded_file($_FILES['picture_path']['tmp_name'], 'picture_path/'.$pic_name);
        $_SESSION["picture_path"] = $pic_name;

      $up_sql = "UPDATE `packingme_users` SET `user_name`=?,`email`=?,`web_site`=?,`picture_path`=?,`self_intro`=? WHERE `packingme_users`.`id` =?";

      $up_data = array($_POST["user_name"],$_POST["email"],$_POST["web_site"],$_SESSION["picture_path"],$_POST["self_intro"],$_SESSION["id"]);
      $up_stmt = $dbh->prepare($up_sql);
      $up_stmt->execute($up_data);

      header('Location: mypage.php?user_id='.$_SESSION["id"]);
      exit();
      }else{

      $error["image"] = 'type';

      }
    }
  }

      // $pic_sql = "INSERT INTO `packingme_users`.`picture_path` VALUES ".$_SESSION["picture"];
      // $pic__stmt = $dbh->prepare($pic_sql);
      // $pic_stmt->execute($pic_data);
      // header('Location: edit_profile.php');
  
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
    <div class="profile-edit">
      <div class="container">
        <form method="post" action="" role="form" enctype="multipart/form-data">
          <!-- profile画像 -->
          <div class="form-group">
            <div class="top-top-top">
              <div class="preview profile-img">
              <img src="picture_path/<?php echo $user["picture_path"];?>">
              <h2><?php echo $user["user_name"];?></h2>
              <!-- <input type="file" id="file" style="display:none;" onchange="$('#fake_input_file').val($(this).val())">
              <button onClick="$('#file').click();">プロフィール画像を変更する</button> -->
            </div>       
              <input type="file" name="picture_path" class="form-control" value="picture_path/<?php echo $user["picture_path"]?>">
            </div>
               
          </div>
            
            <br>
            <!-- 名前 -->
            <div class="profile-text form-group">
              <span>名前  </span>
              <input type="" name="user_name" value="<?php echo $user["user_name"];?>">
              <?php if((isset($error["user_name"])) && ($error["user_name"] == 'blank')) { ?>
                <p class="error">＊ユーザーネームを入力してください</p>
              <?php }?>
            </div>
            <!-- メールアドレス -->
            <div class="profile-text form-group">
              <span>メールアドレス</span>
              <input type="" name="email" value="<?php echo $user["email"];?>">
              <?php if((isset($error["email"])) && ($error["email"] == 'blank')) { ?>
              <p class="error">＊Emailを入力してください</p>
             <?php }?>
             <?php if((isset($error["email"])) && ($error["email"] == 'duplicated')) { ?>
              <p class="error">＊Emailが重複していますtyoufukusiteimasu</p>
              <?php }?>
            </div>
            <!-- ウェブサイト -->
            <div class="profile-text form-group">
              <span>ウェブサイト</span>
              <input type="" name="web_site" value="<?php echo $user["web_site"];?>">
            </div>
            <!-- 自己紹介 -->
            <div class="profile-textarea form-group">
              <span>自己紹介</span>
              <textarea name="self_intro"><?php echo $user["self_intro"];?></textarea>
              <?php if((isset($error["self_intro"])) && ($error["self_intro"] == 'blank')) { ?>
              <p class="error">＊自己紹介を入力してください</p>
              <?php }?>
              </div>
            <div class="editButton">
              <a href=""><button type="submit">変更する</button></a>
            </div>
        </form>
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
    <script src="js/packing_me.js"></script>
    <script src="js/post_pic.js"></script>

  </body>

</html>
