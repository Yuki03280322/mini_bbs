<?php
session_start();

$_SESSION = array();//sessionの情報を削除するため空の配列を用意
if (ini_set('session.use_cookie')) {//sesssionにcookieを使うかどうかを設定するための設定ファイル,ini_set:設定オプションの値を設定するメソッド
  $params = session_get_cookie_params();
  setcookie(session_name(). '', time() - 42000,//cookieの有効期限を切ることでそのセッションを削除する処理
  $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  //session_get_cookie_paramsメソッドが返してきた値をそれぞれ設定しsessionのcookieが使っているそれぞれのオプションを指定し、これによりsessionで使ったcookieを削除する
}
session_destroy();//sessionを完全に削除

setcookie('email', '', time()-3600);//cookieに保存されているemailを削除する処理,空の値を入れて有効期限を切る

header('Location: login.php');
exit();

?>