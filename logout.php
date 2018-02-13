<?php
session_start();

//セッションの中身をからの配列で上書きする
$_SESSION = array();

//セッション情報を<?php
// 有効期限切れにする
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(),'',time() - 42000,$params['path'],$params['domain'],$params['secure'],$params['httponly']);
}


//セッション情報を破棄
session_destroy();


//cookie情報を削除
setcookie('email','',time() - 3000);
setcookie('password','',time() - 3000);


//ログイン後の画面に戻る
header("Location: top.php");
exit();

//ログイン後の画面にログインチェックの機能をじっそう

//ログイン後の画面に行くことでログアウトを確認できる




 ?>