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

// POSTの有無チェック
if (isSet($_POST["userId"], $_POST["pw"], $_POST["name"], $_POST["email"])){
    $userId = $_POST["userId"];
    $pw = $_POST["pw"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $error = "";
    $valiThrough = false;
    // 空白チェック
    if (empty($_POST["userId"]) || empty($_POST["pw"]) || empty($_POST["name"]) || empty($_POST["email"])) {
        $userId = $_POST["userId"];
        $pw = $_POST["pw"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $error = "未入力の項目があります。";
        // メールアドレスチェック
    } else if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $userId = $_POST["userId"];
        $pw = $_POST["pw"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $error = "メールアドレスを正しく入力してください。";
    // 正しい入力(修正ボタン)
    } else if ($_POST["valiThrough"]=="false") {
        $valiThrough = false;
    // 正しい入力(確認ボタン)
    } else {
        $valiThrough = true;
    }
// POSTなしアクセス
} else {
    $userId = "";
    $pw = "";
    $name = "";
    $email = "";
    $error = "";
    $valiThrough = false;
}
// 管理者権限付与チェック
if (isSet($_POST["auth"])){
    // 管理者権限付与かどうか確認する
    $authValues = ["はい","いいえ"];
    // $authValuesに含まれている値ならばtrue
    $isAuth = in_array($_POST["auth"], $authValues);
    if ($isAuth){
      // 選択されている値を取り出す
      $auth = $_POST["auth"];
    } else {
      $auth = "error";
      $error = "「管理者権限付与」に入力エラーがありました。";
    }
} else {
  // POSTされた値がないとき
    $isAuth = false;
    $auth = "はい";
}
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
    	<p><?php echo $_SESSION['name'] . 'さん ログイン中。' ; ?></p>
      <a class="link" href="./selectPage.php">戻る</a>
      <a class="link" href="./logout.php">ログアウト</a>
    </header>

    <div id="box">
    <h1>新規登録内容を入力してください。</h1>

    <form action="<?php echo es($_SERVER['PHP_SELF']); ?>" method="POST">
        <label>ユーザーID: </label><input type="text" name="userId" size="40" placeholder="ユーザIDを20文字以内で入力" value="<?php echo $userId; ?>">
        <br>
        <br>
        <label>パスワード: </label><input type="password" name="pw" size="40" placeholder="パスワードを20文字以内で入力" value="<?php echo $pw; ?>">
        <br>
        <br>
        <label>名前: </label><input type="text" name="name" size="40" placeholder="名前を20文字以内で入力" value="<?php echo $name; ?>">
        <br>
        <br>
        <label>メールアドレス: </label><input type="text" name="email" size="40" placeholder="メールアドレスを入力" value="<?php echo $email; ?>">
        <br>
        <br>
        <label>管理者権限付与: </label>
        <label><input type="radio" name="auth" value="はい" <?php checked("はい", $auth); ?> >はい</label>
        <label><input type="radio" name="auth" value="いいえ" <?php checked("いいえ", $auth); ?> >いいえ</label>
        <br>
        <br>
        <!-- エラー時のメッセージ表示 -->
        <p style="color: red;"><?php echo $error; ?></p>
        <br>
        <input type="submit" size="100" value="確認">
    </form>
    <!-- バリデーションチェックOK -->
    <?php if ($valiThrough) : echo "<HR>"; ?>
    <h2>以下の内容で登録しますか？</h2>
    <form action="?" method="POST">
    <label>ユーザーID: <?php echo $userId; ?></label><input type="hidden" name="userId" value="<?php echo $userId; ?>">
    <br>
    <label>パスワード: <?php echo $pw; ?></label><input type="hidden" name="pw" value="<?php echo $pw; ?>">
    <br>
    <label>名前: <?php echo $name; ?></label><input type="hidden" name="name" value="<?php echo $name; ?>">
    <br>
    <label>メールアドレス: <?php echo $email; ?></label><input type="hidden" name="email" value="<?php echo $email; ?>">
    <br>
    <label>管理者権限付与: <?php echo $auth; ?></label><input type="hidden" name="auth" value="<?php echo $auth; ?>">
    <br>
    <input type="hidden" name="valiThrough" value="false">
    <br>
    <button type="submit" formaction="<?php echo es($_SERVER['PHP_SELF']); ?>">修正</button>
    <button type="submit" formaction="../commonObject/registProcess.php">登録</button>
    </form>
    <?php endif; ?>
    </div>
</body>
</html>
