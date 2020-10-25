<?
/******************************************************
' System :ICPオペレータページ
' Content:リマインダページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/header.php");

$sql = sprintf(
			"SELECT op_mail_addr,op_password,op_name1,op_name2"
		. " FROM t_operator"
		. " WHERE op_mail_addr=%s AND op_status=1",
		sql_char(strtolower($_POST['mail_addr'])));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// 送信メールテンプレート取得
	get_mail_template('ope_reminder', $subject, $from, $cc, $bcc, $repty_to, $body);

	// 差し込み変数処理
	$body = str_replace('%NAME1%', $fetch->op_name1, $body);
	$body = str_replace('%NAME2%', $fetch->op_name2, $body);
	$body = str_replace('%MAIL_ADDR%', $fetch->op_mail_addr, $body);
	$body = str_replace('%PASSWORD%', $fetch->op_password, $body);

	send_mail_one($subject, $from, $fetch->op_mail_addr, $reply_to, $body);

	$msg = 'パスワードをメールでお送りしました。';
} else {
	$msg = '入力されたメールアドレスは登録されていないようです。';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net オペレータログインページ</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>

<body>
<div align="center">

<? ope_header() ?>

<!-- main -->
<form method="post" name="form1" action="login_check.php" onSubmit="return login(this)">
<table border="0" cellspacing="0" cellpadding="0" width="800" height="200" class="main">
<tr>
	<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
	<td align="center" width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="400" class="contents">
		<tr>
			<td>
				■<?=$msg?>
			</td>
		</tr>
		</table>
		<br>
		<table border="0" cellspacing="0" cellpadding="3" width="400" class="contents">
		<tr>
			<td align="right"><a href="javascript:history.back()">ログインページに戻る</a></td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
</form>
<!-- main -->

<!-- copyright -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="copyright">
<tr>
	<td align="center">
	本システムは Internet Explorer 5.0/Netscape 6.0 以上でご利用ください。<br>
	<?=COPYRIGHT?>
	</td>
</tr>
</table>
<!-- copyright -->

</div>
</body>

</html>
