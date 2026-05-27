<?php
session_start();

// 「csrftoken」生成
$byte = openssl_random_pseudo_bytes(16);
$token = bin2hex($byte);
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
    	</header>
    
    <div id="box">
    <h1>ログイン</h1>
    <!-- エラー時のメッセージ表示 -->
    <?php if(isset($_GET['error'])): ?>
        <p style="color: red;">ユーザー名かパスワードが違います。</p>
    <?php endif; ?>

    <form action="../commonObject/loginProcess.php" method="POST">
            <label>ユーザーID: </label><input type="text" name="userId" size="40" placeholder="ユーザID入力">
            <br>
            <br>
            <label>パスワード: </label><input type="password" name="pw" size="40" placeholder="パスワード入力">
            <br>
            <br>
            <!-- hiddenでtoken送信 -->
             <input type="hidden" name="token" value=<?php echo $token?>>

            <input type="submit" size="100" value="ログイン">
    </form>
    </div>
</body>
</html>