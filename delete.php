<?php
require('function.php');
require('dbconnect.php');


// ログインチェック
login_check();

// つぶやき削除
// delete_tweet();

// DBの接続
// require('dbconnect.php');

// // 削除したいtweet_id
// if(isset($_GET["post_id"])) && !empty($_GET["post_id"]){
$sql = "DELETE FROM `packingme_posts` WHERE `packingme_posts`.`post_id` = ".$_GET["post_id"];
$stmt = $dbh->prepare($sql);
$stmt->execute();


// // 一覧画面に戻る
header("Location: mypage.php?user_id=".$_SESSION["id"]);
// exit();
// }
// $delete_tweet_id = $_GET['tweet_id'];

// // 論理削除用のUPDATE文


// $sql = "UPDATE `tweets` SET `delete_flag` = '1' WHERE `tweets`.`tweet_id` = ".$delete_tweet_id;


// // SQL実行

// $stmt = $dbh->prepare($sql);
// $stmt->execute();


// // 一覧画面に戻る
// header("Location: index.php");
// exit();

