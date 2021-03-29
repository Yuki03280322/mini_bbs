<?php
require('dbconnect.php');

if (!empty($_POST)) {
  if ($_POST['email'] == '') {
    $error['email'] = 'blank';
  }
  if ($_POST['password'] == '') {
    $error['password'] = 'blank';
  }
  if ($_POST['email'] !== '' && $_POST['password'] !== '') {
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');//prepare関数でプリペアドステートメントのSQLを設定
    $login->execute(array(//execute関数でDBからデータを取得
      $_POST['email'],
      sha1($_POST['password'])//sha1で暗号化された暗号＝不可逆暗号:同じsha1で暗号化した情報は必ず同じ文字の並びになる
    ));
    $member = $login->fetch();//fetch:該当するデータを1行返す(ログイン認証に成功すれば$memberに値が格納される)

    if ($member) {//$memberが空でない場合はtrue=ログインに成功しているときの処理
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();
      //passwordや個人情報はセッションへ保存するのは危険のため控える
      header('Location: index.php');
      exit();
      //form action=""としているため正しく動作をしている時次のページへジャンプさせる処理を書く
    } else {//ログインに失敗したときの処理
      $error['login'] = 'failed';
    }
  } 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログインする</title>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ログインする</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>メールアドレスとパスワードを記入してログインしてください。</p>
      <p>入会手続きがまだの方はこちらからどうぞ。</p>
      <p>&raquo;<a href="join/">入会手続きをする</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>メールアドレス</dt>
        <dd>
          <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" />
          <?php if ($error['email'] === 'blank'): ?>
          <p class="error">* メールアドレスを入力してください</p>
          <?php endif; ?>
          <?php if ($error['login'] === 'failed'): ?>
          <p class="error">* ログインに失敗しました。</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
          <?php if ($error['password'] === 'blank'): ?>
          <p class="error">* パスワードを入力してください</p>
          <?php endif; ?>
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" />
      </div>
    </form>
  </div>
  <div id="foot">
    <p><img src="images/txt_copyright.png" width="136" height="15" alt="(C) H2O Space. MYCOM" /></p>
  </div>
</div>
</body>
</html>
