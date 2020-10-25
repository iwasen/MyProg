<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:入力データ表示
'******************************************************/
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/regist3.php");

// 登録受付けメール送信
function mail_send($fetch) {
	get_mail_template('regist', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%NAME1%', $fetch->mb_name1, $body);
	$body = str_replace('%NAME2%', $fetch->mb_name2, $body);
	send_mail($subject, $fetch->mb_mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist3']))
	redirect('top.php');
$reg = &$_SESSION['ss_regist3'];

// データ保存
$reg->save_db($_SESSION['ss_seq_no']);

// メール送信
$sql = "select mb_id,mb_mail_addr,mb_name1, mb_name2 from t_member where mb_seq_no=" . sql_number($_SESSION['ss_seq_no']);
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	mail_send($fetch);

	// mainページに名前セット
	$_SESSION['ss_name1'] = $fetch->mb_name1;
}
// セッション変数削除
unset($_SESSION['ss_regist3']);
session_unregister('ss_regist3');

$_SESSION['ss_member_step'] = 3;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆Ecoゾウさん一家のEco生活☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" height ="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/title/f_end01.gif" width="295" height="38" alt="設定が全て完了しました"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="500"><font size=2>これで、<font color="#FF0000">全ての項目の入力は終了です</font>。お疲れさまでした。<br>
					変更事項がある場合は、メインメニューの<font color="#0000FF">会員情報変更</font>から行ってください。</font></td>
				</tr>
			</table>
			<br>
			<table width="520" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
					<div align="center"><a href="season.php"><img src="img/nyuukai/youkoso.gif" width="644" height="476" alt="ようこそ" border="0"></a>
					</div>
					</td>
				</tr>
			</table>
			<br>
			<table width="500" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><font size=2>
					</td>
				</tr>
			</table>
			<hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><a href="season.php"><img src="img/nyuukai/button/member_main01.gif" width="143" height="40" alt="メインへ" border="0"></a></td>
					</tr>
					<tr>
					<td height="7"><img src="img/spacer.gif" width="99" height="7"></td>
				</tr>
			</table></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>