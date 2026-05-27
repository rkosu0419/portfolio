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

// 変数格納と日付のフォーマット
$title0 = $_POST['title0'];
$profile = $_POST['profile'];
$greetingTitle = $_POST['greetingTitle'];
$greetingContent = $_POST['greetingContent'];
$title1Date = date($_POST['year1'] . "-" . $_POST['month1'] . "-" . $_POST['day1']);
$title1 = $_POST['title1'];
$content1 = $_POST['content1'];
$title2Date = date($_POST['year2'] . "-" . $_POST['month2'] . "-" . $_POST['day2']);
$title2 = $_POST['title2'];
$content2 = $_POST['content2'];
$title3Date = date($_POST['year3'] . "-" . $_POST['month3'] . "-" . $_POST['day3']);
$title3 = $_POST['title3'];
$content3 = $_POST['content3'];
$infoTitle = $_POST['infoTitle'];
$infoContent = $_POST['infoContent'];
$page = $_POST['page'];
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
    <?php echo "<h1>" . $page . "の内容を以下に更新しますか？</h1>"; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "healthCoach" || $_POST["page"] == "theoryCooking" || $_POST["page"] == "enzymeJuice" || $_POST["page"] == "IntestinalAct" || $_POST["page"] == "rental"): ?>
        <form action="?" method="POST">
        <input type="hidden" name="page" value="<?php echo $page;?>">
        <label>タイトル: <?php echo $title0; ?></label><input type="hidden" name="title0" value="<?php echo es($title0); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>
        <label>プロファイル: <?php echo $profile; ?></label><input type="hidden" name="profile" value="<?php echo es($profile); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "healthCoach" || $_POST["page"] == "theoryCooking" || $_POST["page"] == "enzymeJuice" || $_POST["page"] == "IntestinalAct" || $_POST["page"] == "rental"): ?>
        <label>挨拶タイトル: <?php echo $greetingTitle; ?></label><input type="hidden" name="greetingTitle" value="<?php echo es($greetingTitle); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "healthCoach" || $_POST["page"] == "theoryCooking" || $_POST["page"] == "enzymeJuice" || $_POST["page"] == "IntestinalAct" || $_POST["page"] == "rental"): ?>
        <label>挨拶本文:  <?php echo "<br><br>" . $greetingContent; ?></label><input type="hidden" name="greetingContent" value="<?php echo es($greetingContent); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>
        <label>お知らせ１の掲載日:  <?php echo $title1Date; ?></label><input type="hidden" name="title1Date" value="<?php echo es($title1Date); ?>">
        <br>
        <br>
        <label>お知らせ１のタイトル:  <?php echo $title1; ?></label><input type="hidden" name="title1" value="<?php echo es($title1); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "enzymeJuice"): ?>
        <label>お知らせ１の本文:  <?php echo "<br><br>" . $content1; ?></label><input type="hidden" name="content1" value="<?php echo es($content1); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>            
        <label>お知らせ２の掲載日:  <?php echo $title2Date; ?></label><input type="hidden" name="title2Date" value="<?php echo es($title2Date); ?>">
        <br>
        <br>
        <label>お知らせ２のタイトル:  <?php echo $title2; ?></label><input type="hidden" name="title2" value="<?php echo es($title2); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "enzymeJuice"): ?>
        <label>お知らせ２の本文:  <?php echo "<br><br>" . $content2; ?></label><input type="hidden" name="content2" value="<?php echo es($content2); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>            
        <label>お知らせ３の掲載日:  <?php echo $title3Date; ?></label><input type="hidden" name="title3Date" value="<?php echo es($title3Date); ?>">
        <br>
        <br>
        <label>お知らせ３のタイトル:  <?php echo $title3; ?></label><input type="hidden" name="title3" value="<?php echo es($title3); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "enzymeJuice"): ?>
        <label>お知らせ３の本文:  <?php echo "<br><br>" . $content3; ?></label><input type="hidden" name="content3" value="<?php echo es($content3); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>            
        <label>インフォメーションタイトル:  <?php echo $infoTitle; ?></label><input type="hidden" name="infoTitle" value="<?php echo es($infoTitle); ?>">
        <br>
        <br>
        <label>インフォメーション本文:  <?php echo "<br><br>" . $infoContent; ?></label><input type="hidden" name="infoContent" value="<?php echo es($infoContent); ?>">
        <br>
        <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "healthCoach" || $_POST["page"] == "theoryCooking" || $_POST["page"] == "enzymeJuice" || $_POST["page"] == "IntestinalAct" || $_POST["page"] == "rental"): ?>
        <input type="hidden" name="page" value="<?php echo $_POST["page"]; ?>">
        <button type="submit" formaction="./selectPage.php">修正</button>
        <button type="submit" formaction="../commonObject/registNotice.php">登録</button>
    <?php endif; ?>
        </form>
    </div>
</body>
</html>
