<?php
require_once(__DIR__ . '/../commonObject/dbInfo.php');
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
if (isSet($_POST["userId"], $_POST["pw"])){
    $userId = $_POST["userId"];
    $pw = $_POST["pw"];
    $error = "";
    $valiThrough = false;
    // 空白チェック
    if (empty($_POST["userId"]) || empty($_POST["pw"])) {
        $userId = $_POST["userId"];
        $pw = $_POST["pw"];
        $error = "未入力の項目があります。";
        // メールアドレスチェック
    } else {
        try {
            // irohaデータベース接続
            $pdo = new PDO($dsn, $dbUser, $dbPw);
            // sql(userIdプレースホルダー)
            $sql = "SELECT id,
                           userId,
                           pw,
                           name,
                           email,
                           auth
                    FROM user WHERE pw = :pw" ;
            // プリペアドステートメント
            $stm = $pdo->prepare($sql);
            // プレースホルダーバインド
            $stm->bindValue(':pw', $_POST['pw']);
            // クエリ実行
            $stm->execute();
            // all Data 連想配列指定で取得
	        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            // 接続を解除する
            $pdo = NULL;
            // DBのセレクトデータバリデーションチェック
            foreach ($result as $uData){
                // 文字エンコードの検証
                if (!cken($uData)){
                  $encoding = mb_internal_encoding();
                  $err = "Encoding Error! The expected encoding is " . $encoding ;
                  // エラーメッセージを出して、以下のコードをすべてキャンセルする
                  exit($err);
                }
                // HTMLエスケープ（XSS対策）
                $uData = es($uData);
            }

            // アカウントデータ、パスワード照合
            //if ($result && password_verify($_POST['pw'], password_hash($uData['pw'], PASSWORD_DEFAULT))) {
            if ($result && $_POST['userId'] == $uData['userId']) {
                // 実在する
                if ($uData['auth'] == 1) {
                    $uData['auth'] = "はい";
                } else {
                    $uData['auth'] = "いいえ";
                }
                $valiThrough = true;
                
            } else {
                // 失敗
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
                exit();
            }
        } catch (PDOException $e) {
            die('エラー:' . $e->getMessage());
        }
    }
// POSTなしアクセス
} else {
    $error = "";
    $valiThrough = false;
}
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
    	    <p><?php echo $_SESSION['name'] . 'さん ログイン中。' ; ?></p>
            <a class="link" href="./selectPage.php">戻る</a>
            <a class="link" href="./logout.php">ログアウト</a>
    	</header>
    
    <div id="box">
    <h1>削除するアカウントの入力</h1>
    <form action="<?php echo es($_SERVER['PHP_SELF']); ?>" method="POST">
            <label>ユーザーID: </label><input type="text" name="userId" size="40" placeholder="ユーザID入力">
            <br>
            <br>
            <label>パスワード: </label><input type="password" name="pw" size="40" placeholder="パスワード入力">
            <br>
            <br>
            <!-- エラー時のメッセージ表示 -->
            <p style="color: red;"><?php echo $error; ?></p>
            <br>
            <?php if(isset($_GET['error'])): ?>
            <p style="color: red;">ユーザー名かパスワードが違います。</p>
            <?php endif; ?>

            <input type="submit" size="100" value="確認">
    </form>
        <!-- バリデーションチェックOK -->
    <?php if ($valiThrough) : echo "<HR>"; ?>
    <h2>以下のアカウントを削除しますか？</h2>
    <form action="?" method="POST">
    <label>ユーザーID: <?php echo $uData['userId']; ?></label>
    <br>
    <label>パスワード: <?php echo $uData['pw']; ?></label>
    <br>
    <label>名前: <?php echo $uData['name']; ?></label><input type="hidden" name="name" value="<?php echo $uData['name']; ?>">
    <br>
    <label>メールアドレス: <?php echo $uData['email']; ?>
    <br>
    <label>管理者権限付与: <?php echo $uData['auth']; ?></label>
    <br>
    <input type="hidden" name="id" value="<?php echo $uData['id']; ?>">
    <br>
    <button type="submit" formaction="<?php echo es($_SERVER['PHP_SELF']); ?>">中止</button>
    <button type="submit" formaction="../commonObject/deleteProcess.php">削除</button>
    </form>
    <?php endif; ?>
    </div>
</body>
</html>