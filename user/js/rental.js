'use strict';
const img = ["./image/rentalImg/image1.jpg", "./image/rentalImg/image2.jpg", "./image/rentalImg/image3.jpg"];
let count = -1;

picChange(); // 関数を実行
function picChange() {
  count++;
  // カウントが最大になれば配列を初期値に戻すため「0」を指定する
  if (count == img.length) count = 0;
  // 画像選択
  document.getElementById("pic").src = img[count];
  // 1.5秒ごとに実行
  setTimeout("picChange()", 1500);
}