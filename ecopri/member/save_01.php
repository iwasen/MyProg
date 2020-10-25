<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:メール送信
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/regist.php");

// 登録受付けメール送信
function mail_send($reg, $seq_no) {
	get_mail_template('reg_kari', $subject, $from, $cc, $bcc, $repty_to, $body);
	$fetch = get_system_info('sy_url_member');
	$body = str_replace('%MAIL_ADDR%', $reg->mb_mail_addr, $body);
//	$body = str_replace('%MEMBER_ID%', $reg->mb_id, $body);
//	$body = str_replace('%PASSWORD%', $reg->mb_password, $body);
	$body = str_replace('%REGIST_URL%', "{$fetch->sy_url_member}member_input_exp.php?key=" . encrypt_number($seq_no, 8), $body);
	send_mail($subject, $reg->mb_mail_addr, $from, $body, $cc, $bcc, $reply_to);
}
// セッション処理
session_start();
if (!isset($_SESSION['ss_regist1']))
	redirect('top.php');
$reg = &$_SESSION['ss_regist1'];

// メール送信
mail_send($reg, $_SESSION['ss_seq_no']);

// セッション変数削除
session_destroy();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">


<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="90%">
<tr>
<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
<td align="center" valign="bottom" width="644"><img src="img/nyuukai/title/save_01.gif" alt="ここまでのデータは保存されました">
<br>
<hr width="95%" size="1">
</td>

<td width="35" align="right" valign="top">
<img src="img/kado_2.gif" width="35" height="35"></td>
</tr>
    <tr>
<td align="center" valign="top" colspan="3">

<table width="550" border="0" cellspacing="0" cellpadding="0">
<br>
<tr>
<td><img src="img/nyuukai/zou_img/save01.gif" width="550" height="313" alt="再開するときは「登録する」ボタンを押してね"></td>
</tr>
</table>

<br>
<font size=3>
入会手続きを再開するには<font color="#0000FF">メールのURL</font>を、<font color="#FF0000">クリック</font>してください。<br><br>
</font>
<font size=2 color="#505050">※万一メールが届かない場合は、トップページからログインして頂くか<br>
お手数ですが<a href="mailto:info@ecofootprint.jp">こちら</a>までご連絡ください。<br><br>
<a href="mailto:info@ecofootprint.jp">Ecoゾウclub事務局</a></font>
</tr>
<tr>
<td width="35" align="left" valign="bottom">
<img src="img/kado_3.gif" width="35" height="35"></td>

<td align="center" valign="bottom">
<hr width="95%" size="1">
<table border="0" cellspacing="0" cellpadding="0">

<tr>
<td align="center"><a href="top.php"><img src="img/nyuukai/button/top.gif" width="99" height="40" border="0" alt="TOPへ戻る"></a></td>
</tr>
<tr>
<td height="7"><img src="img/spacer.gif" width="99" height="7"></td>
</tr>
</table>

</td>
<td width="35" height="35" align="right" valign="bottom">
<img src="img/kado_4.gif" width="35" height="35"></td>
</tr>
</table>

</div>
</body>
</html>