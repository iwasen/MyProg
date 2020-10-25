<?
/******************************************************
' System :「きゃららFactory」ユーザページ
' Content:似顔絵申し込み内容入力
'******************************************************/

$inc = "../inc";
include_once("$inc/common.php");
include_once("$inc/database.php");
include_once("$inc/status.php");

// ID入力チェック
if ($id == '') {
	$msg = '申し込みIDが入力されていないようです。';
	include('error1.php');
	exit;
}

// IDを大文字に変換
$id = strtoupper($id);

// ID存在チェック
$sql = "SELECT nd_status FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
$status = db_fetch1($sql, 0);
if ($status == 0) {
	$msg = '申し込みIDが正しくないようです。ご確認の上、もう一度入力してください。';
	include('error1.php');
	exit;
}

// 受付済みチェック
if ($status > STATUS_USER_RECEIVED) {
	$msg = 'お申し込みはすでに受け付けられています。';
	include('error1.php');
	exit;
}

// デフォルトのドメイン名設定
if ($mail_addr == '') {
	switch (substr($id, 0, 2)) {
	case 'DS':
		$mail_addr = '@docomo.ne.jp';
		break;
	}
}

// デフォルトの性別
if ($sex_code == '')
	$sex_code = 2;

// Shift_JISで出力
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>きゃららFactory</title>
</head>
<body>
<form method="post" name="form1" action="accept.php">
<h2 align="center"><font color="#FFCC00">★</font><font color="#dd0000">きゃらら</font><font color="#FFCC00">★</font></h2>
<hr color="#5555dd">
<font color="red">●</font>メールアドレス<br>
<center>
	<input type="text" name="mail_addr" <?=value($mail_addr)?> istyle="3"><br>
</center>
<font color="red">●</font>性別<br>
<center>
	<input type="radio" name="sex_code" <?=value_checked('2', $sex_code)?>>女性
	<input type="radio" name="sex_code" <?=value_checked('1', $sex_code)?>>男性
</center>
<font color="red">●</font>年齢（任意）<br>
<center>
	<input type="text" name="age" size="3" <?=value($age)?> istyle="4">才
</center>
<hr color="#5555dd">
<center>
	<input type="submit" value="　送信　">
</center>
<input type="hidden" name="id" value="<?=$id?>">
</form>
</body>
</html>
