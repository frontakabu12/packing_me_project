<?php
session_start();
// DB接続

// likeが押された時
if(isset($_GET["like_post_id"])){
// like情報をLIkesテーブルを登録

  like($_GET["like_post_id"],$_SESSION["id"]);
  // $sql = "INSERT INTO `likes` (`tweet_id`, `member_id`) VALUES (".$_GET["like_tweet_id"].",".$_SESSION["id"].");";

  // // sql実行
  // $stmt = $dbh->prepare($sql);
  // $stmt->execute($data);

  // header("Location: index.php");
}
// unlikeが押された時osaretatoki
if(isset($_GET["unlike_post_id"])){
// 登録されているLike情報をテーブルから削除
  unlike($_GET["unlike_post_id"],$_SESSION["id"],$_GET["page"]);
  // $sql = "DELETE FROM `likes` WHERE `tweet_id`=".$_GET["unlike_tweet_id"]." AND `member_id`=".$_SESSION["id"];

  // // sql実行
  // $stmt = $dbh->prepare($sql);
  // $stmt->execute($data);

  // header("Location: index.php");
}


// Like関数
// 引数は3個
  function like($like_post_id,$login_user_id){
    require ('dbconnect.php');

    $sql = "INSERT INTO `packingme_likes` (`post_id`, `user_id`,`created`) VALUES (".$like_post_id.",".$login_user_id.",now());";

  // sql実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    header("Location: mypage.php");
  }

  function unlike($unlike_post_id,$login_user_id){
    require ('dbconnect.php');

    $sql = "DELETE FROM `packingme_likes` WHERE `post_id`=".$unlike_post_id." AND `user_id`=".$login_user_id;

  // sql実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    header("Location: mypage.php" );
  }
?>