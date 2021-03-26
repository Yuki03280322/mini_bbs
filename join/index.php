<?php
if (!empty($_POST)) {
	if ($_POST['name'] === '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] === '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	// strlenメソッド:入力された文字数を図り数字で返す
	if ($_POST['password'] === '') {
		$error['password'] = 'blank';
	}
	
	if (empty($error)) {
		header('Location: check.php');
	// エラーが発生していなければ($errorの配列に設定されているかどうか)check.phpという画面にジャンプする
		exit();
	// この画面を終わらせる
	}
	// これらのチェックはフォームが送信されたときに処理を走らせる必要がある	
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
<!-- form action="":空の場合には自分自身のファイルにジャンプさせる→正しい場合check.phpにジャンプさせる-->
	<dl>
		<dt>ニックネーム<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'],ENT_QUOTES)); ?>" />
					<?php if ($error['name'] === 'blank'): ?>
					<!--
					phpはif,while,for,foreach,switchの各構造において開き波括弧をコロン:、閉じ波括弧をそれぞれendif,end~に変更することで,phpとhtmlを混在して記入する時視認性を向上させる
					フォーム記載内容が更新の度に消えるのを防ぐためvalue属性に入力内容を設定し,htmlspecialcharsで特殊文字をhtmlエンティティに変換する
					 -->
					<p class ="error">* ニックネームを入力してください</p>
					<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'],ENT_QUOTES)); ?>" />
					<?php if ($error['email'] === 'blank'): ?>
					<p class ="error">* メールアドレスを入力してください</p>
					<?php endif; ?>
		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
					<?php if ($error['password'] === 'blank'): ?>
					<p class = "error">* パスワードを入力してください</p>
					<?php endif; ?>
					<?php if ($error['password'] === 'length'): ?>
					<p class = "error">* パスワードは4文字以上で入力してください</p>
					<?php endif; ?>
        </dd>
		<dt>写真など</dt>
		<dd>
        	<input type="file" name="image" size="35" value="test"  />
        </dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>
