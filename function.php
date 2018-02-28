<?php
session_start();

// ログインチェックを行う関数
// 関数とは、一定の処理をまとめて名前をつけておいているプログラムの塊
// 何度も同じ処理を実行したい場合、便利
// プログラミング言語が事前に用意している関数：組み込み関数
// 自分で定義して作成する関数：自作関数
// login_check:関数名。呼び出すときに指定するもの

function login_check(){

	if(isset($_SESSION['id'])){
    // ログインしている
  	}else{
    // ログインしていない
    // ログイン画面へ飛ばす
    header("Location: login.php");
    exit();
  }

}	

function delete_tweet(){
	require('dbconnect.php');

	// 削除したいtweet_id
	$delete_tweet_id = $_GET['tweet_id'];

	// 論理削除用のUPDATE文


	$sql = "UPDATE `tweets` SET `delete_flag` = '1' WHERE `tweets`.`tweet_id` = ".$delete_tweet_id;


	// SQL実行

	$stmt = $dbh->prepare($sql);
	$stmt->execute();


	// 一覧画面に戻る
	header("Location: index.php");
	exit();
}

?>
