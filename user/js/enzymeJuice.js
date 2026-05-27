'use strict';
const img1 = ["./image/enzymeJuiceImg/image1.jpg", "./image/enzymeJuiceImg/image2.jpg", "./image/enzymeJuiceImg/image3.jpg"];
let count1 = -1;
picChange1(); // 関数を実行
function picChange1() {
  count1++;
  // カウントが最大になれば配列を初期値に戻すため「0」を指定する
  if (count1 == img1.length) count1 = 0;
  // 画像選択
  document.getElementById("pic1").src = img1[count1];
  // 1.5秒ごとに実行
  setTimeout("picChange1()", 1500);
}

'use strict';
const img2 = ["./image/enzymeJuiceImg/image13.JPG", "./image/enzymeJuiceImg/image12.JPG", "./image/enzymeJuiceImg/image11.JPG", "./image/enzymeJuiceImg/image6.JPG"];
let count2 = -1;
picChange2();
function picChange2() {
  count2++;
  if (count2 == img2.length) count2 = 0;
  //画像選択
  document.getElementById("pic2").src = img2[count2];
  //秒数の指定2
  setTimeout("picChange2()", 2000);
}
