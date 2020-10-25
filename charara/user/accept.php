<?
/******************************************************
' System :「きゃららFactory」ユーザページ
' Content:似顔絵申し込み受付処理
'******************************************************/

$inc = "../inc";
include_once("$inc/database.php");
include_once("$inc/check.php");
include_once("$inc/mail.php");
include_once("$inc/status.php");

// メールアドレス入力チェック
if ($mail_addr == '') {
	$msg = 'メールアドレスが入力されていないようです。';
	include('error2.php');
	exit;
}

// メールアドレス正当性チェック
if (!check_mail_addr($mail_addr)) {
	$msg = 'メールアドレスが正しくないようです。ご確認の上、もう一度入力してください。';
	include('error2.php');
	exit;
}

// 送信メールテンプレート取得
get_mail_template('user_acc', $subject, $from, $cc, $bcc, $repty_to, $body);

// FromアドレスにIDを付加
$from = str_replace('@', "-$id@", $from);

// 受付完了メール送信
send_mail($subject, $mail_addr, $from, $body);

// 似顔絵データテーブル更新
$rec['nd_status'] = STATUS_USER_RECEIVED;
$rec['nd_appl_date'] = 'current_timestamp';
$rec['nd_mail_addr'] = sql_char($mail_addr);
$rec['nd_sex_code'] = sql_number($sex_code);
$rec['nd_age'] = sql_number($age);
db_update('t_nigaoe_data', $rec, "nd_random_id=" . sql_char($id));

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
<h2 align="center"><font color="#FFCC00">★</font><font color="#dd0000">きゃらら</font><font color="#FFCC00">★</font></h2>
<hr color="#5555dd">
お申し込みありがとうございました。<br>
受付確認メールをお送りしましたので、顔写真を添付の上、ご返信ください。
<hr color="#5555dd">
</body>
</html>
