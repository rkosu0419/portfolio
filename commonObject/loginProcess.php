<?php
require_once(__DIR__ . '/dbInfo.php');
require_once(__DIR__ . '/validation.php');

session_start();

// 「validation.php」バリデーションチェック

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
    // sql(userIdプレースホルダー)
    $sql = "SELECT id,
                   userId,
                   pw,
                   name,
                   email,
                   auth
            FROM user WHERE userId = :userId";
    // プリペアドステートメント
    $stm = $pdo->prepare($sql);
    // プレースホルダーバインド
    $stm->bindValue(':userId', $_POST['userId']);
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

    // csrftoken、アカウントデータ、パスワード照合
    if ($_POST['token'] && $result && password_verify($_POST['pw'], password_hash($uData['pw'], PASSWORD_DEFAULT))) {
        // 成功：セッションにIDを保存
        $_SESSION['id'] = $uData['id'];
        $_SESSION['userId'] = $uData['userId'];
        $_SESSION['pw'] = $uData['pw'];
        $_SESSION['name'] = $uData['name'];
        $_SESSION['email'] = $uData['email'];
        $_SESSION['auth'] = $uData['auth'];
        $_SESSION['token'] = $_POST['token'];
        header('Location: ../admin/selectPage.php'); // ログイン後のマイページへ
        exit();
    } else {
        // 失敗
        header('Location: ../admin/login.php?error=1');
        exit();
    }

} catch (PDOException $e) {
    die('エラー:' . $e->getMessage());
}

// ?>