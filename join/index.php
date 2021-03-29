<?php
session_start();//個人情報を含むためcookieは危険
require('../dbconnect.php');

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
	$fileName = $_FILES['image']['name'];//imageというname属性がついたファイルアップロードからアップロードされたファイルのファイル名を変数へ
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);//ファイルの後ろ3文字を切り取る(ファイルの拡張子を見る)
		if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
			$error['image'] = 'type';
		}
	}

	if (empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		//SELECT COUNT(*):件数を数字で取得し、emailを?で絞り込む
		$member->execute(array($_POST['email']));//絞り込むemailを取得
		$record = $member->fetch();//取得結果をfetchで取り出す(検索したemailアドレスがあれば1,なければ0を返す)
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}
	//emailの重複チェック
	
	if (empty($error)) {
		$image = date('YmdHis') . $_FILES['image']['name'];//['image']['name']:アップロードするファイル名を作るex:20210328mybirthday.png
		move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/' . $image);//tmp_name:一時的にアップロードされている場所であり、このあと消えてしまうため削除されない専用のディレクトリに保存する為move_uploaded_file関数を使用
		//$_FILES['image]['tmp_name]:移動前のパス指定
		//../member_picture/ . $image:移動先のパス指定,member_pictureというディレクトリにさっき作成した変数で保存
		$_SESSION['join'] = $_POST;//joinというキーに対してPOSTの内容を保存
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
	// エラーが発生していなければ($errorの配列に設定されているかどうか)check.phpという画面にジャンプする
		exit();
	// この画面を終わらせる
	}
	// これらのチェックはフォームが送信されたときに処理を走らせる必要がある	
}

if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {//urlパラメーターにrewriteがついていれば
	$_POST = $_SESSION['join'];
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
<!-- 
form action="":空の場合には自分自身のファイルにジャンプさせる→正しい場合check.phpにジャンプさせる
enctype="multipart/form-data":フォームにファイルをアップロードするための決り文句でtype="file"で活用
-->
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
					<?php if ($error['email'] === 'duplicate'): ?>
					<p class ="error">* 指定されたメールアドレスは既に使用されています</p>
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
					<?php if ($error['image'] === 'type'): ?>
					<p class = "error">* 写真は'jpg','png','gif'のみアップロード可能です</p>
					<?php endif; ?>
					<?php if (!empty($error)): ?>
					<p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
					<?php endif; ?>
        </dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>
