<?php
require_once(__DIR__ . '/../commonObject/dbSelect.php');

// 自身のphpファイル名取得
$page = basename(__FILE__, ".php");
$selectData = dbSelect($dsn, $dbUser, $dbPw, $page);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
<title><?php echo $page; ?></title>
<!-- CSS import -->
<div id="cssLink"></div>
<script src="./js/course.js"></script>
</head>

<body>
<!-- ヘッダー -->
<article>
	<header>
		<div class="logo">
		<img src="./image/commonImg/icon.jpg">
		</div>
		<h1><?php echo es($selectData['title0']); ?></h1>
		<div class="menu">
			<a class="return" href="./healthCoach.php">ヘルスコーチに戻る</a>
			<a class="return" href="./home.php">ホームに戻る</a>
		</div>
	</header>
</article>

<!-- 画像切替 -->
<img class="pic" id="pic" src="./image/IntestinalActImg/">
<script defer src="./js/IntestinalAct.js"></script>
	
<!-- 事業内容 -->
<main id="greeting">
	<div class="wrapper">
		<img src="./image/IntestinalActImg/publicityPhoto.jpg">
	  <article>
		<h2><?php echo es($selectData['greetingTitle']); ?></h2>
		<p class="text_content1"><?php echo es($selectData['greetingContent']); ?></p>
		<a class="text_content2" href="https://ws.formzu.net/sfgen/S78518184/"> お問い合わせフォームへ <br></a> 
	  </article>
	</div>
</main> 

<!-- フッター -->
<footer>
	<p>2026 いろは堂 present</p>
</footer>

</body>
</html>