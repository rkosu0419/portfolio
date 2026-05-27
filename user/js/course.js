let ut = navigator.userAgent;
const link = document.getElementById('cssLink');

  if(ut.indexOf('iPhone') > 0 || ut.indexOf('iPod') > 0 || ut.indexOf('Android') > 0 && ut.indexOf('Mobile') > 0){
  link.insertAdjacentHTML('beforebegin', '<link rel="stylesheet" href="./css/courseS.css" type="text/css">');
  }else if(ut.indexOf('iPad') > 0 || ut.indexOf('Android') > 0){
  link.insertAdjacentHTML('beforebegin', '<link rel="stylesheet" href="./css/courseS.css" type="text/css">');
  }else{
  link.insertAdjacentHTML('beforebegin', '<link rel="stylesheet" href="./css/courseP.css" type="text/css">');
  }