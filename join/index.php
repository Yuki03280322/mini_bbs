<?php
if ($_POST['name'] === '') {
	$error['name'] = 'blank';
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
        	<input type="text" name="name" size="35" maxlength="255" value="" />
					<?php if ($error['name'] === 'blank'): ?>
					<!-- phpはif,while,for,foreach,switchの各構造において開き波括弧をコロン:、閉じ波括弧をそれぞれendif,end~に変更することで,phpとhtmlを混在して記入する時視認性を向上させる -->
					<p class ="error">* ニックネームを入力してください</p>
					<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="" />
		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" value="" />
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
