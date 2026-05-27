<?php
// HTMLエスケープ、XSS対策
function es($data, $charset='UTF-8'){
  // $dataが配列のとき
  if (is_array($data)){
    // 再帰呼び出し
    return array_map(__METHOD__, $data);
  } else {
    // nl2br()：改行を<br/>へ置換、htmlspecialchars()：HTMLエスケープ、XSS対策
    return nl2br(htmlspecialchars($data, ENT_QUOTES, $charset));
  }
}

// 配列の文字エンコードのチェックを行う
function cken(array $data){
  $result = true;
  foreach ($data as $key => $value) {
    if (is_array($value)){
      // 含まれている値が配列のとき文字列に連結する
      $value = implode("", $value);
    }
    if (!mb_check_encoding($value)){
      // 文字エンコードが一致しないとき
      $result = false;
      // foreachでの走査をブレイクする
      break;
    }
  }
  return $result;
}

// 初期値でチェックするかどうか
function checked($value, $question){
  if (is_array($question)){
    // 配列のとき、値が含まれていればtrue
    $isChecked = in_array($value, $question);
  } else {
    // 配列ではないとき、値が一致すればtrue
    $isChecked = ($value===$question);
  }
  if ($isChecked) {
    // チェックする
    echo "checked";
  } else {
    echo "";
  }
}
// ?>
