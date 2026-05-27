<?php
require_once(__DIR__ . '/../commonObject/validation.php');
require_once(__DIR__ . '/../commonObject/dbSelect.php');

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

// POST「page」確認
if (isSet($_POST["page"])){
    // 「page」値確認
    $pageValues = ["home","healthCoach", "theoryCooking","enzymeJuice","IntestinalAct", "rental"];
    $ispage = in_array($_POST["page"], $pageValues);
    // displayValuesに含まれている値ならばOK
    if ($ispage){
      // フォーム表示の値で使う
      $page = $_POST["page"];
      // iroha.noticeテーブルのデータ取得
      $selectData = dbSelect($dsn, $dbUser, $dbPw, $page);
    } else {
      $page = "error";
    }
} else {
    // POSTされた値がないとき
    $ispage = false;
    $page = "home";
}
// 初期値で選択するかどうか
function selected($value, $question){
    if (is_array($question)){
        // 配列のとき、値が含まれていればtrue
        $isSelected = in_array($value, $question);
    } else {
        // 配列ではないとき、値が一致すればtrue
        $isSelected = ($value===$question);
    }
    if ($isSelected) {
        // 選択する
        echo "selected";
    } else {
        echo "";
    }
}

// 今年の前後15年のプルダウンメニューを作る
function yearOption($sYear){
    $theYear = date('Y', strtotime($sYear));
    // 今年
    $thisYear = date('Y');
    $startYear = $thisYear - 15;
    $endYear = $thisYear + 15;
    for ($i=$startYear; $i <= $endYear; $i++) {
        // POSTされた年を選択する
        if ($i==$theYear){
            echo "<option value={$i} selected>{$i}</option>", "\n";
        } else {
            echo "<option value={$i}>{$i}</option>", "\n";
        }
    }
}
// 1〜12月のプルダウンメニューを作る
function monthOption($sMonth){
    $theMonth = date('n', strtotime($sMonth));
    for ($i=1; $i <= 12; $i++) {
        // POSTされた月を選択する
        if ($i==$theMonth){
            echo "<option value={$i} selected>{$i}</option>", "\n";
        } else {
            echo "<option value={$i}>{$i}</option>", "\n";
        }
    }
}
// 1〜31日のプルダウンメニューを作る
function dayOption($sDay){
    $theDay = date('j', strtotime($sDay));
    for ($i=1; $i <= 31; $i++) {
        // POSTされた日を選択する
        if ($i==$theDay){
            echo "<option value={$i} selected>{$i}</option>", "\n";
        } else {
            echo "<option value={$i}>{$i}</option>", "\n";
        }
    }
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
    <h1>文章編集画面を選択してください。</h1>
    <form method="POST" action="<?php echo es($_SERVER['PHP_SELF']); ?>">
        <ul>
            <span>編集画面：</span>
                <select name="page">
                    <option value="home" <?php selected("home", $page) ; ?> >ホーム</option>
                    <option value="healthCoach" <?php selected("healthCoach", $page); ?> >ヘルスコーチ</option>
                    <option value="theoryCooking" <?php selected("theoryCooking", $page); ?> >食についての理論とクッキング講座</option>
                    <option value="enzymeJuice" <?php selected("enzymeJuice", $page) ; ?> >酵素ジュース教室</option>
                    <option value="IntestinalAct" <?php selected("IntestinalAct", $page); ?> >腸活料理教室</option>
                    <option value="rental" <?php selected("rental", $page); ?> >レンタルキッチン＆スペース</option>
                </select>
                <input type="submit" value="選択" >
        </ul>
    </form>
      <!-- ドキュメントの編集 -->
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "healthCoach" || $_POST["page"] == "theoryCooking" || $_POST["page"] == "enzymeJuice" || $_POST["page"] == "IntestinalAct" || $_POST["page"] == "rental"): ?>
        <h2><?php echo $_POST["page"] . "画面の文章編集"?></h2>
        <br>
        <form action="./pageDocChage.php" method="POST">
            <label>タイトル: </label><input type="text" name="title0" size="40" placeholder="20文字以内で入力" value="<?php echo es($selectData['title0']); ?>">
            <br>
            <br>
            <img src="./image/title0.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>
            <label>プロファイル: </label><input type="text" name="profile" size="40" placeholder="20文字以内で入力" value="<?php echo es($selectData['profile']); ?>">
            <br>
            <br>
            <img src="./image/profile.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "healthCoach" || $_POST["page"] == "theoryCooking" || $_POST["page"] == "enzymeJuice" || $_POST["page"] == "IntestinalAct" || $_POST["page"] == "rental"): ?>
            <label>挨拶タイトル: </label><input type="text" name="greetingTitle" size="40" placeholder="25文字以内で入力" value="<?php echo es($selectData['greetingTitle']); ?>">
            <br>
            <br>
            <img src="./image/greetingTitle.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "healthCoach" || $_POST["page"] == "theoryCooking" || $_POST["page"] == "enzymeJuice" || $_POST["page"] == "IntestinalAct" || $_POST["page"] == "rental"): ?>
            <label>挨拶本文: </label><br>
            <textarea type="memo" name="greetingContent" rows="10" cols="75" maxlength="750" placeholder="750文字以内で入力"><?php echo str_replace(array("<br>", "<br/>", "<br />"), "", es($selectData['greetingContent'])); ?></textarea>
            <br>
            <br>
            <img src="./image/greetingContent.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>
            <label>お知らせ１の掲載日: </label>
            <li>
            <select name="year1"><?php yearOption($selectData['title1Date']); ?>年</select>
            <select name="month1"><?php monthOption($selectData['title1Date']); ?>月</select>
            <select name="day1"><?php dayOption($selectData['title1Date']); ?>日</select>
            </li>
            <br>
            <br>
            <img src="./image/title1Date.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>
            <label>お知らせ１のタイトル: </label><input type="text" name="title1" size="40" placeholder="25文字以内で入力" value="<?php echo es($selectData['title1']); ?>">
            <br>
            <br>
            <img src="./image/title1.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "enzymeJuice"): ?>
            <label>お知らせ１の本文: </label><br>
            <textarea type="memo" name="content1" rows="6" cols="75" maxlength="450" placeholder="450文字以内で入力"><?php echo str_replace(array("<br>", "<br/>", "<br />"), "", es($selectData['content1'])); ?></textarea>
            <br>
            <br>
            <img src="./image/content1.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>            
            <label>お知らせ２の掲載日: </label>
            <li>
            <select name="year2"><?php yearOption($selectData['title2Date']); ?>年</select>
            <select name="month2"><?php monthOption($selectData['title2Date']); ?>月</select>
            <select name="day2"><?php dayOption($selectData['title2Date']); ?>日</select>
            </li>
            <br>
            <br>  
            <img src="./image/title2Date.jpg" class="pic">
            <br>
            <br>
            <label>お知らせ２のタイトル: </label><input type="text" name="title2" size="40" placeholder="25文字以内で入力" value="<?php echo es($selectData['title2']); ?>">
            <br>
            <br>
            <img src="./image/title2.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "enzymeJuice"): ?>
            <label>お知らせ２の本文: </label><br>
            <textarea type="memo" name="content2" rows="6" cols="75" maxlength="450" placeholder="450文字以内で入力"><?php echo str_replace(array("<br>", "<br/>", "<br />"), "", es($selectData['content2'])); ?></textarea>
            <br>
            <br>
            <img src="./image/content2.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>            
            <label>お知らせ３の掲載日: </label>
            <li>
            <select name="year3"><?php yearOption($selectData['title3Date']); ?>年</select>
            <select name="month3"><?php monthOption($selectData['title3Date']); ?>月</select>
            <select name="day3"><?php dayOption($selectData['title3Date']); ?>日</select>
            </li>
            <br>
            <br>
            <img src="./image/title3Date.jpg" class="pic">
            <br>
            <br>
            <label>お知らせ３のタイトル: </label><input type="text" name="title3" size="40" placeholder="25文字以内で入力" value="<?php echo es($selectData['title3']); ?>">
            <br>
            <br>
            <img src="./image/title3.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "enzymeJuice"): ?>
            <label>お知らせ３の本文: </label><br>
            <textarea type="memo" name="content3" rows="6" cols="75" maxlength="450" placeholder="450文字以内で入力"><?php echo str_replace(array("<br>", "<br/>", "<br />"), "", es($selectData['content3'])); ?></textarea>
            <br>
            <br>
            <img src="./image/content3.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home"): ?>            
            <label>インフォメーションタイトル: </label><input type="text" name="infoTitle" size="40" placeholder="20文字以内で入力" value="<?php echo es($selectData['infoTitle']); ?>">
            <br>
            <br>
            <img src="./image/infoTitle.jpg" class="pic">
            <br>
            <br>
            <label>インフォメーション本文: </label><br>
            <textarea type="memo" name="infoContent" rows="6" cols="75" maxlength="450" placeholder="450文字以内で入力"><?php echo str_replace(array("<br>", "<br/>", "<br />"), "", es($selectData['infoContent'])); ?></textarea>
            <br>
            <br>
            <img src="./image/infoContent.jpg" class="pic">
            <br>
            <br>
    <?php endif; ?>
    <?php if ($_POST["page"] == "home" || $_POST["page"] == "healthCoach" || $_POST["page"] == "theoryCooking" || $_POST["page"] == "enzymeJuice" || $_POST["page"] == "IntestinalAct" || $_POST["page"] == "rental"): ?>
            <input type="hidden" name="page" value="<?php echo $_POST["page"]; ?>">
            <input type="submit" value="確認" >
        </form>

      <!-- 20260309 画像変更機能未実装 -->

      <?php endif; ?>
    </div>
</body>
</html>