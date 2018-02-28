<?php
require('function.php');

// ログインチェック
login_check();

// つぶやき削除
delete_tweet();

// DBの接続
// require('dbconnect.php');

// // 削除したいtweet_id
// $delete_tweet_id = $_GET['tweet_id'];

// // 論理削除用のUPDATE文


// $sql = "UPDATE `tweets` SET `delete_flag` = '1' WHERE `tweets`.`tweet_id` = ".$delete_tweet_id;


// // SQL実行

// $stmt = $dbh->prepare($sql);
// $stmt->execute();


// // 一覧画面に戻る
// header("Location: index.php");
// exit();



?>