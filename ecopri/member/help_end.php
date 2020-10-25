<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ID,Password お問い合わせ
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/get_form.php");

// 問い合わせ受付メール
function mail_send($fetch) {
	get_mail_template('help', $subject, $from, $cc, $bcc, $repty_to, $body);
	$fetch2 = get_system_info('sy_url_top');
	$body = str_replace('%REMIND_URL%', "{$fetch2->sy_url_top}remind.php?key=" . encrypt_number($fetch->mb_seq_no, 10), $body);
	send_mail($subject, $fetch->mb_mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

if (!$mail_addr) {
	$msg = "メールアドレスが入力されていないようです。";
} else {
	$sql = "select mb_seq_no,mb_id,mb_step,mb_mail_addr,mb_password from t_member where mb_mail_addr=" . sql_char($mail_addr) . " and mb_step <> 9";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// メール送信
		mail_send($fetch);
		$msg = "メールを送信しました。";

	} else
		$msg = "メールアドレスが登録されていないようです。";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- タイトル部分 -->
			<br>
			<img src="img/help/help_title.gif" width="207" height="56">
			<hr width="95%" size="1"><br>
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
			<table border="0" cellpadding="0" cellspacing="0">

				<tr>
					<td align="center" height="80"><?=$msg?></td>
				</tr>
			</table>
			<!-- ここまで -->
		</td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	</tr>

	<tr>
		<td width="35" valign="bottom" height=70><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" height=70><a href="javascript:history.back()"><img src="img/button/input/back1.gif" border="0" width="72" height="44"></a></td>
		<td width="35" valign="bottom" height=70><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

<!--フッターボタン一覧-->
<!--
<br>
	<table border="0" cellpadding="0" cellspacing="0" align="center" width="714" height="54">
		<tr align="center" valign="middle" bgcolor="#ffffff">
			<td align="left" valign="top"><img src="img/kadoa_1.gif" border="0" width="10" height="54"></td>
			<td align="center" valign="middle"><a href="top.php" target="_top"><img src="img/button/top/button_top1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="what_eco.php"><img src="img/button/top/button_eco1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="what_can.php" target="_top"><img src="img/button/top/button_can1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="what_use.php" target="_top"><img src="img/button/top/button_use1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="what_family.php" target="_top"><img src="img/button/top/button_family1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="others_char.php"><img src="img/button/top/button_char1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="become_member.php" target="_top"><img src="img/button/top/button_menber1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><img src="img/button/top/button_help2.gif" border="0" width="68" height="51"></td>
			<td align="center" valign="middle"><a href="xxx.php" target="_top"><img src="img/button/top/button_xxx1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="rule.php" target="_top"><img src="img/button/top/button_exp1.gif" border="0" width="68" height="51"></a></td>
			<td align="right" valign="top"><img src="img/kadoa_2.gif" border="0" width="10" height="54"></td>
		</tr>
	</table>
-->
<!--フッターボタン一覧 終了-->

</div>
</body>
</html>