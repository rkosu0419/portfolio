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
<script src="./js/homeLink.js"></script>
</head>

<body>
<!-- ヘッダー -->
<article>
	<header>
		<div class="logo">
		<img src="./image/commonImg/icon.jpg">
		</div>
		<h1><?php echo es($selectData['title0']); ?></h1>
		<div class="menu1">
			<article>
			<a class="coach0" href="./healthCoach.php">ヘルスコーチ</a>
			<a class="coach1" href="./theoryCooking.php">理論とクッキング講座</a>
			<a class="coach2" href="./enzymeJuice.php">酵素ジュース教室</a>
			<a class="coach3" href="./IntestinalAct.php">腸活料理教室</a>
			</article>
		</div>
		<div class="menu2">
			<a class="rental" href="./rental.php">レンタルキッチン＆スペース</a>
		</div>

	</header>
</article>

<!-- 画像切替 -->
<img id="pic" src="./image/homeImg/">
<script defer src="./js/home.js"></script>

<!-- ご挨拶 -->
<main id="greeting">
	<div class="wrapper">
		<img src="./image/homeImg/publicityPhoto.jpg">
	  <h1>PROFILE： <?php echo es($selectData['profile']); ?></h1>
	  <article>
		<h2><?php echo es($selectData['greetingTitle']); ?></h2>
		<p class="text_content"><?php echo es($selectData['greetingContent']); ?></p>
	  </article>
	</div>
</main> 

<!-- お知らせ -->
<main id="notice">
	<div class="wrapper">
	  <h1>お知らせ</h1>
	  <article id="part1">
		<p class="text_date"><time><?php echo es($selectData['title1Date']); ?></time></p>
		<h2><?php echo es($selectData['title1']); ?></h2>
		<p class="text_content"><?php echo es($selectData['content1']); ?></p>
	  </article>
	  <article id="part2">
		<p class="text_date"><time><?php echo es($selectData['title2Date']); ?></time></p>
		<h2><?php echo es($selectData['title2']); ?></h2>
		<p class="text_content"><?php echo es($selectData['content2']); ?></p>
	  </article>
	  <article id="part3">
		<p class="text_date"><time><?php echo es($selectData['title3Date']); ?></time></p>
		<h2><?php echo es($selectData['title3']); ?></h2>
		<p class="text_content"><?php echo es($selectData['content2']); ?></p>
	  </article>
	</div>
  </main>

<!-- アクセス情報 -->
<h2 id="access"><?php echo es($selectData['infoTitle']); ?></h2>
<p id="station"><?php echo es($selectData['infoContent']); ?></p>
<img class="lineQR" src="./image/homeImg/lineQR.jpg">

<!-- フッター -->
<footer>
	<p>2026 いろは堂 present</p>
</footer>
</body>
</html>