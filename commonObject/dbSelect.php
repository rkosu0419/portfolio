<?php
require_once( __DIR__ . '/dbInfo.php');
require_once( __DIR__ . '/validation.php');

// MySQL select関数
function dbSelect($dsn, $dbUser, $dbPw, $page){
	// global命令
	global $dsn;
	global $dbUser;
	global $dbPw;
	global $page;
	// 
	// MySQLデータベースに接続する
	try {
	    $pdo = new PDO($dsn, $dbUser, $dbPw);
	    // プリペアドステートメントのエミュレーションを無効にする
	    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	    // 例外がスローされる設定にする
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    // SQL
	    $sql = "SELECT title0, 
	                    profile,
	    		    	greetingTitle,
	    				greetingContent,
	    				title1Date,
	    				title1,
	    				content1,
	    				title2Date,
	    				title2,
	    				content2,
	    				title3Date,
	    				title3,
	    				content3,
	    				infoTitle,
	    				infoContent
	            FROM notice
	    		WHERE pageName = '$page'";
	    // プリペアドステートメントを作る
	    $stm = $pdo->prepare($sql);
	    // SQL文を実行する
	    $stm->execute();
	    // 結果の取得（連想配列で返す）
	    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
	    foreach ($result as $selectData){
			// 文字エンコードの検証
			if (!cken($selectData)){
			  echo "文字コードが不正です。";
			  exit();
			}
		}
	} catch (Exception $e) {
	    echo '<span class="error">エラーがありました。</span><br>';
	    echo $e->getMessage();
	    exit();
	}
return $selectData;
}
// ?>