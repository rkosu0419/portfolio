<?php
require_once(__DIR__ . '/dbInfo.php');
require_once(__DIR__ . '/validation.php');

session_start();

// トークンがセッションに存在しないアクセスを拒否
if (empty($_SESSION['token'])) {
    die("不正なアクセスです。ログインしてください。" . "<br>" . "<a href='../admin/login.php'>ログイン画面へ</a>");
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

// homeのsql
if ($_POST['page'] == "home") {
    // 改行削除
    $title0 = $_POST['title0'];
    $profile = $_POST['profile'];
    $greetingTitle = $_POST['greetingTitle'];
    $greetingContent = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['greetingContent']);
    $title1Date = $_POST['title1Date'];
    $title1 = $_POST['title1'];
    $content1 = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['content1']);
    $title2Date = $_POST['title2Date'];
    $title2 = $_POST['title2'];
    $content2 = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['content2']);
    $title3Date = $_POST['title3Date'];
    $title3 = $_POST['title3'];
    $content3 = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['content3']);
    $infoTitle = $_POST['infoTitle'];
    $infoContent = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['infoContent']);
    $page = $_POST['page'];
    // sql
    $sql = "UPDATE notice SET title0 = :title0,
                              profile = :profile,
                              greetingTitle = :greetingTitle,
                              greetingContent = :greetingContent,
                              title1Date = :title1Date,
                              title1 = :title1,
                              content1 = :content1,
                              title2Date = :title2Date,
                              title2 = :title2,
                              content2 = :content2,
                              title3Date = :title3Date,
                              title3 = :title3,
                              content3 = :content3,
                              infoTitle = :infoTitle,
                              infoContent = :infoContent
                              WHERE pageName = :page";
// healthCoach theoryCooking IntestinalAct rentalのsql
} else if ($_POST['page'] == "healthCoach" || $_POST['page'] == "theoryCooking" || $_POST['page'] == "IntestinalAct" || $_POST['page'] == "rental") {
    // 改行削除
    $title0 = $_POST['title0'];
    $greetingTitle = $_POST['greetingTitle'];
    $greetingContent = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['greetingContent']);
    $page = $_POST['page'];
    // sql
    $sql = "UPDATE notice SET title0 = :title0,
                              greetingTitle = :greetingTitle,
                              greetingContent = :greetingContent
                              WHERE pageName = :page";

} else if ($_POST['page'] == "enzymeJuice") {
    // 改行削除
    $title0 = $_POST['title0'];
    $greetingTitle = $_POST['greetingTitle'];
    $greetingContent = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['greetingContent']);
    $content1 = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['content1']);
    $content2 = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['content2']);
    $content3 = str_replace(array("&lt;br /", "&gt;&lt;br /", "&gt;<br />", "&gt;"), "", $_POST['content3']);
    $page = $_POST['page'];
    // sql
    $sql = "UPDATE notice SET title0 = :title0,
                              greetingTitle = :greetingTitle,
                              greetingContent = :greetingContent,
                              content1 = :content1,
                              content2 = :content2,
                              content3 = :content3
                              WHERE pageName = :page";
}

try {
    // irohaデータベース接続
    $pdo = new PDO($dsn, $dbUser, $dbPw);
    // プリペアドステートメントのエミュレーションを無効にする
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // 例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // プリペアドステートメント
    $stm = $pdo->prepare($sql);
    // プレースホルダーバインド
    if ($page == "home") {
        $stm->bindValue(':page', $page, PDO::PARAM_STR);
        $stm->bindValue(':title0', $title0, PDO::PARAM_STR);
        $stm->bindValue(':profile', $profile, PDO::PARAM_STR);
        $stm->bindValue(':greetingTitle', $greetingTitle, PDO::PARAM_STR);
        $stm->bindValue(':greetingContent', $greetingContent, PDO::PARAM_STR);
        $stm->bindValue(':title1Date', $title1Date, PDO::PARAM_STR);
        $stm->bindValue(':title1', $title1, PDO::PARAM_STR);
        $stm->bindValue(':content1', $content1, PDO::PARAM_STR);
        $stm->bindValue(':title2Date', $title2Date, PDO::PARAM_STR);
        $stm->bindValue(':title2', $title2, PDO::PARAM_STR);
        $stm->bindValue(':content2', $content2, PDO::PARAM_STR);
        $stm->bindValue(':title3Date', $title3Date, PDO::PARAM_STR);
        $stm->bindValue(':title3', $title3, PDO::PARAM_STR);
        $stm->bindValue(':content3', $content3, PDO::PARAM_STR);
        $stm->bindValue(':infoTitle', $infoTitle, PDO::PARAM_STR);
        $stm->bindValue(':infoContent', $infoContent, PDO::PARAM_STR);
    } else if ($page == "healthCoach" || $page == "theoryCooking" || $page == "IntestinalAct" || $page == "rental") {
        $stm->bindValue(':page', $page, PDO::PARAM_STR);
        $stm->bindValue(':title0', $title0, PDO::PARAM_STR);
        $stm->bindValue(':greetingTitle', $greetingTitle, PDO::PARAM_STR);
        $stm->bindValue(':greetingContent', $greetingContent, PDO::PARAM_STR);
    } else if ($page == "enzymeJuice") {
        $stm->bindValue(':page', $page, PDO::PARAM_STR);
        $stm->bindValue(':title0', $title0, PDO::PARAM_STR);
        $stm->bindValue(':greetingTitle', $greetingTitle, PDO::PARAM_STR);
        $stm->bindValue(':greetingContent', $greetingContent, PDO::PARAM_STR);
        $stm->bindValue(':content1', $content1, PDO::PARAM_STR);
        $stm->bindValue(':content2', $content2, PDO::PARAM_STR);
       $stm->bindValue(':content3', $content3, PDO::PARAM_STR);
    }
    // クエリ実行
    $stm->execute();

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
    <h1>更新完了。</h1>
    <a href="../admin/selectPage.php">戻る</a>
    </div>
</body>
</html>

<?php
} catch (PDOException $e) {
    die('データベースexception発生' . $e->getMessage()  . "<br>" . "<a href='../admin/login.php'>ログイン画面へ</a>");
}
?>