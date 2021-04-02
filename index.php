<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  //sessionに保存されたidとtimeを用いてログインしているかを判断し、ログイン後1時間内にアクションがあったかどうか
  $_SESSION['time'] = time();
  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));//sessionidを元に会員情報を引っ張り出す
  $member = $members->fetch();
} else {//sessionにidが保存されていない場合、もしくはログイン後1時間以上何も動きがなかった場合
  header('Location: login.php');
  exit();
}

if (!empty($_POST)) {//メッセージをDBへ保存するための処理
  if ($_POST['message'] !== '') {
    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()');
    $message->execute(array(
      $member['id'],
      $_POST['message'],
      $_POST['reply_post_id']
    ));

    header('Location: index.php');//$_POSTに値が残っている状態をリセットするための処理
    exit();
  }
}

$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');
//DBから取得するだけなのでqueryメソッドを使う
//m. p. はテーブル名のショートカット(エイリアス)
//m.id=p.member_id:リレーションをはるためのキーの一致

if (isset($_REQUEST['res'])) {//返信用の[Re]がクリックされた場合
  //返信の処理
  $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
  $response->execute(array($_REQUEST['res']));//p.id=?にurlパラメーターを指定
  $table = $response->fetch();//投げたクエリ情報を取得
  $message = '@' . $table['name'] . ' ' . $table['message'];//返信用のテンプレート分を作成し変数へ
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
  	<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <form action="" method="post">
      <dl>
        <dt><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt>
        <dd>
          <textarea name="message" cols="50" rows="5"><?php print(htmlspecialchars($message, ENT_QUOTES)); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>" />
          <!-- hidden属性を利用し,どのメッセージへの返信かをフォームに渡す -->
        </dd>
      </dl>
      <div>
        <p>
          <input type="submit" value="投稿する" />
        </p>
      </div>
    </form>

<?php foreach ($posts as $post): ?>
    <div class="msg">
    <img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>" />
    <p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?>
    <span class="name">（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>）</span>[<a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">Re</a>]</p>
    <p class="day"><a href="view.php?id=<?php print(htmlspecialchars($post['id'])); ?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>
    
  <?php if ($post['reply_message_id'] > 0 ): ?>
    <a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'], ENT_QUOTES)); ?>">
    返信元のメッセージ</a>
  <?php endif; ?>
  <?php if ($post['member_id'] == $_SESSION['id']): ?>
    [<a href="delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>"
    style="color: #F33;">削除</a>]
  <?php endif; ?>
    </p>
    </div>
<?php endforeach; ?>

<ul class="paging">
<li><a href="index.php?page=">前のページへ</a></li>
<li><a href="index.php?page=">次のページへ</a></li>
</ul>
  </div>
</div>
</body>
</html>
