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

// irohaデータベース接続
$pdo = new PDO($dsn, $dbUser, $dbPw);

try {
    // sql(pwとemailプレースホルダー)
    $sql1 = "SELECT pw FROM user WHERE pw = :pw";
    $sql2 = "SELECT email FROM user WHERE email = :email";
    // プリペアドステートメント
    $stm1 = $pdo->prepare($sql1);
    $stm2 = $pdo->prepare($sql2);
    // プレースホルダーバインド
    $stm1->bindValue(':pw', $_POST['pw']);
    $stm2->bindValue(':email', $_POST['email']);
    // クエリ実行
    $stm1->execute();
    $stm2->execute();
    // all Data 連想配列指定で取得
	  $result1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
	  $result2 = $stm2->fetchAll(PDO::FETCH_ASSOC);
    // DBデータバリデーションチェック(pw)
    foreach ($result1 as $uDataP){
        // 文字エンコードの検証
        if (!cken($uDataP)){
          $encoding = mb_internal_encoding();
          $err = "Encoding Error! The expected encoding is " . $encoding ;
          // エラーメッセージを出して、以下のコードをすべてキャンセルする
          exit($err);
        }
        // HTMLエスケープ（XSS対策）
        $uDataP = es($uDataP);
    }
    // DBデータバリデーションチェック(email)
    foreach ($result2 as $uDataE){
        // 文字エンコードの検証
        if (!cken($uDataE)){
          $encoding = mb_internal_encoding();
          $err = "Encoding Error! The expected encoding is " . $encoding ;
          // エラーメッセージを出して、以下のコードをすべてキャンセルする
          exit($err);
        }
        // HTMLエスケープ（XSS対策）
        $uDataE = es($uDataE);
    }
    // パワード、メールアドレスの重複エラー
    if ($uDataP['pw'] || $uDataE['email']) {
        echo "パスワード、もしくはメールアドレスが既に登録されています。<br>「戻る」をクリックして再度、設定してください。<br>";
        echo '<a href="../admin/registration.php">戻る</a>';
    // 登録処理
    } else {
        //「管理者権限付与」確認
        if ($_POST["auth"] == "はい"){
            $_POST["auth"] = 1;
          } else {
            $_POST["auth"] = 0;
          }

        // プリペアドステートメントのエミュレーションを無効にする
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // 例外がスローされる設定にする
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // SQL
        $sql = "INSERT INTO user (userId, pw, name, email, auth) VALUES (:userId, :pw, :name, :email, :auth)";
        // プリペアドステートメントを作る
        $stm = $pdo->prepare($sql);
        // プレースホルダに値をバインドする
        $stm->bindValue(':userId', $_POST["userId"], PDO::PARAM_STR);
        $stm->bindValue(':pw', $_POST["pw"], PDO::PARAM_STR);
        $stm->bindValue(':name', $_POST["name"], PDO::PARAM_STR);
        $stm->bindValue(':email', $_POST["email"], PDO::PARAM_STR);
        $stm->bindValue(':auth', $_POST["auth"], PDO::PARAM_INT);
        // SQL文を実行する
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
    <h1>登録完了。</h1>
    <a href="../admin/selectPage.php">戻る</a>
    </div>
</body>
</html>

<?php
    }
} catch (PDOException $e) {
    die('エラー:' . $e->getMessage());
}
?>