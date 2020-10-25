<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報変更 退会完了
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// 退会通知メール送信
function mail_send($fetch) {
	get_mail_template('taikai', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%NAME1%', $fetch->mb_name1, $body);
	$body = str_replace('%NAME2%', $fetch->mb_name2, $body);
	send_mail($subject, $fetch->mb_mail_addr, $from, $body, $cc, $bcc, $reply_to);
}


$sql = "select mb_seq_no from t_member where mb_step <> 9 and mb_seq_no=" . sql_number($_SESSION['ss_seq_no']);
$result = db_exec($sql);
if (pg_numrows($result)) {
	db_begin_trans();

	$sql = "update t_member set mb_step=9 where mb_seq_no=" . $_SESSION['ss_seq_no'];
	db_exec($sql);

	if ($reason)
		$reason_cd = implode(",", $reason);
	$sql = sprintf("insert into t_taikai (tk_mb_seq_no,tk_opinion_cd,tk_reason_cd,tk_reason_text) values (%s,%s,%s,%s)",
				sql_number($_SESSION['ss_seq_no']),
				sql_number($opinion),
				sql_char($reason_cd),
				sql_char($reason_text));
	db_exec($sql);

	db_commit_trans();
}

// メール送信
$sql = "select mb_id,mb_mail_addr,mb_name1, mb_name2 from t_member where mb_seq_no=" . sql_number($_SESSION['ss_seq_no']);
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	mail_send($fetch);
}

// セッション終了
session_start();
session_destroy();

SetCookie('ck_member_id', '', 0, '/');

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000">

<div align="center">

<table border="0" cellspacing="0" cellpadding="0" width="714" height="90%">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td bgcolor="#FFFFFF"><img src="img/spacer.gif" width="644" height="35"></td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td bgcolor="#FFFFFF"><br></td>
		<td align="center" bgcolor="#FFFFFF"><br><br>
		<img src="img/taikai/zou_img/o_end01.gif" alt="また会おうね" width="600" height="361"><br>
		<br><br>
			<table width="96" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><a href="top.php"><img src="img/taikai/button/ok.gif" width="96" height="49" border="0" alt="OK"></a></td>
				</tr>
			</table>
		<br>
		</td>
		<td bgcolor="#FFFFFF"><br></td>
	</tr>

	<tr>
		<td width="35" align="left" valign="bottom" bgcolor="#FFFFFF"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td bgcolor="#FFFFFF"><img src="img/spacer.gif" width="644" height="35"></td>
		<td width="35" align="right" valign="bottom" bgcolor="#FFFFFF"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>
</body>
</html>