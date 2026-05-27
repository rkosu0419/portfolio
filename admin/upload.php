<?php
require_once(__DIR__ . '/../commonObject/validation.php');

session_start();

// トークンがセッションに存在しないアクセスを拒否
if (empty($_SESSION['token'])) {
    die('不正なアクセスです。');
}

// validation.phpのバリデーションチェック
// 文字エンコード
if (!cken($_POST)){
    $encoding = mb_internal_encoding();
    $err = "Encoding Error! The expected encoding is " . $encoding ;
    // エラーメッセージを出して、以下のコードをすべてキャンセルする
    exit($err);
}
// HTMLエスケープ（XSS対策）
$_POST = es($_POST);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo basename(__FILE__, ".php"); ?></title>

  <link rel="stylesheet" href="./css/common.css" type="text/css">
</head>
<body>
    <!-- ヘッダー -->
    <header>
    	<div id="logo">
    	<img src="./image/icon.jpg">
    	</div>
    	<p><?php echo $_SESSION['name'] . 'さん ログイン中。' ?></p>
      <!-- 管理者権限のみ新規登録、削除ボタン表示 -->
      <?php if ($_SESSION['auth'] == 1):?>
      <a class="link" href="./registration.php">新規登録</a>
      <a class="link" href="./delete.php">登録削除</a>
      <?php endif;?>
      <a class="link" href="./imgEdit.php">画像編集</a>
      <a class="link" href="./logout.php">ログアウト</a>
    </header>
    <div id="box">
    <?php
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['upfile'])) {
            exit('不正なアクセスです');
        }
        
        // 選択画像
        $files = $_FILES['upfile'];
        // ページ名からパス変数作成
        $imageDir = $_POST['page'] . "Img/";
        $uploadDir = "../user/image/" . $imageDir;

        // 保存先フォルダがなければ作成
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // 複数ファイル分ループ
        for ($i = 0; $i < count($files['name']); $i++) {
            // アップロードエラーチェック
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                echo ($i + 1) . " 個目のファイルが未選択か、アップロード失敗。<br>";
                continue; // 次のファイルへ
            }
            // 元のファイル名を取得（セキュリティのため basename を通す）
            $originalName = basename($files['name'][$i]);
            $savePath = $uploadDir . $originalName;

            // 上書き保存
            if (move_uploaded_file($files['tmp_name'][$i], $savePath)) {
                echo htmlspecialchars($originalName) . "のアップロード成功 " . "<br>";
            } else {
                echo htmlspecialchars($originalName) . "のアップロード失敗 " . "<br>";
            }
        }
    ?>
    </div>
</body>
</html>
