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

// POST「page」確認
if (isSet($_POST["page"])){
    // 「page」値確認
    $pageValues = ["home","healthCoach", "theoryCooking","enzymeJuice","IntestinalAct", "rental"];
    $ispage = in_array($_POST["page"], $pageValues);
    // displayValuesに含まれている値ならばOK
    if ($ispage){
      // フォーム表示の値で使う
      $page = $_POST["page"];
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
      <a class="link" href="./selectPage.php">文章編集</a>
      <a class="link" href="./logout.php">ログアウト</a>
    </header>

    <div id="box">
    <h1>画像編集画面を選択してください。</h1>
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
        <h2><?php echo $_POST["page"] . "画面の画像編集"?></h2>
        <br>
        <form action="./upload.php" method="POST" enctype="multipart/form-data">
            <label>スクロール(image1～image3): </label><input type="file" name="upfile[]" accept="image/jpeg" multiple>
            <br>
            <br>
            <label>※画像名称は「image1.jpg～image3.jpg」とすること！</label>
            <br>
            <br>
            <img src="./image/scroll.jpg" class="pic">
            <br>
            <br>
            <label>プロファイル画像: </label><input type="file" name="upfile[]" accept="image/jpeg" multiple>
            <br>
            <br>
            <label>※画像名称は「publicityPhoto.jpg」とすること！</label>
            <br>
            <br>
            <img src="./image/profileImg.jpg" class="pic">
            <br>
            <br>
            <input type="hidden" name="page" value="<?php echo $_POST['page']; ?>">
            <input type="submit" value="アップロード" >
        </form>
        <?php endif; ?>
    </div>
</body>
</html>