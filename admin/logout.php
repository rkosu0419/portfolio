<?php
session_start();

// トークンがセッションに存在しないアクセスを拒否
if (empty($_SESSION['token'])) {
    die('不正なアクセスです。');
}

// セッション変数の値を空にする
$_SESSION = [];
// セッションクッキーを破棄する
if (isset($_COOKIE[session_name()])){
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time()-36000, $params['path']);
}
// セッションを破棄する
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo basename(__FILE__, ".php"); ?></title>
    <link rel="stylesheet" href="./css/common.css" type="text/css">
</head>
<body>
    <!-- ヘッダー -->
    	<header>
    		<div id="logo">
    		<img src="./image/icon.jpg">
    		</div>
            <a class="link" href="./login.php">ログイン</a>
    	</header>
    
    <div id="box">
    <h1>ログアウト</h1>
    </div>

</body>
</html>