<?php 
	// 開発環境用
	// ステップ1 データベースに接続する
	$dsn = 'mysql:dbname=seed_sns;host=localhost';

	// $user データベース接続用ユーザー名
	// $password データベース接続用ユーザーのパスワード

	$user = 'root'; //rootは権限が強いユーザー
	$password='';

	

	//  データベース接続オブジェクト
	$dbh = new PDO($dsn, $user, $password);

	// 例外処理を使用可能にする方法（エラー文を表示することができる）
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//  今から実行するSQL文を文字コードutf8で送るという設定（文字化け防止）
	$dbh->query('SET NAMES utf8');



	



?>
