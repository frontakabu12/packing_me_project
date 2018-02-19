<?php 

$tmp_name = $_FILES["pic"]['name'];
$upload_path = "pic/";  // ファイルを実際にアップロードしたいディレクトリ
  if (is_uploaded_file($tmp_name)) {
    if (move_uploaded_file($tmp_name, $upload_path)) {
      echo 'アップロード成功！';
    } else {
      echo 'ファイル移動エラー';
    }
  } else {
    echo 'アップロードエラー';
  }

// header("Location: home.php");
//        exit();



?>