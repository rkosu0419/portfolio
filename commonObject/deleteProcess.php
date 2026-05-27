<?php
require_once(__DIR__ . '/dbInfo.php');
require_once(__DIR__ . '/validation.php');

session_start();

// トークンがセッションに存在しないアクセスを拒否
if (empty($_SESSION['token'])) {
    die('不正なアクセスです。');
}

// 文字エンコードの検証
if (!cken($_POST)){
  $encoding = mb_internal_encoding();
  $err = "Encoding Error! The expected encoding is " . $encoding ;
  // エラーメッセージを出して、以下のコードをすべてキャンセルする
  exit($err);
}
// HTMLエスケープ（XSS対策）
$_POST = es($_POST);

try {
    // irohaデータベース接続
    $pdo = new PDO($dsn, $dbUser, $dbPw);
    // sql(id検索削除)
    $sql = "DELETE FROM user WHERE id = :id";
    // プリペアドステートメント
    $stm = $pdo->prepare($sql);
    // プレースホルダーバインド
    $stm->bindValue(':id', $_POST['id']);
    // クエリ実行
    $stm->execute();
    // 接続を解除する
    $pdo = NULL;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo basename(__FILE__, ".php"); ?></title>
    <link rel="stylesheet" href="../admin/css/common.css" type="text/css">
</head>
<body>
    <!-- ヘッダー -->
    	<header>
    		<div id="logo">
    		<img src="../admin/image/icon.jpg">
    		</div>
            <a class="link" href="../admin/logout.php">ログアウト</a>
    	</header>
    
    <div id="box">
    <h1><?php echo $_POST['name'] . 'さんを削除しました。' ;?></h1>
    <a href='../admin/selectPage.php'>戻る</a>
    </div>
</body>
</html>

<?php
} catch (PDOException $e) {
    die('エラー:' . $e->getMessage());
}
?>