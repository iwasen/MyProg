<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:����Ͽ����
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

// ��Ͽ���դ��᡼������
function mail_send($reg, $seq_no) {
	get_mail_template('reg_kari', $subject, $from, $cc, $bcc, $repty_to, $body);
	$fetch = get_system_info('sy_url_top');
	$body = str_replace('%MAIL_ADDR%', $reg->mb_mail_addr, $body);
	$body = str_replace('%MEMBER_ID%', $reg->mb_id, $body);
	$body = str_replace('%PASSWORD%', $reg->mb_password, $body);
	$body = str_replace('%REGIST_URL%', "{$fetch->sy_url_top}regist.php?key=" . encrypt_number($seq_no, 8), $body);
	send_mail($subject, $reg->mb_mail_addr, $from, $body, $cc, $bcc, $reply_to);
}
// ���å�������
session_start();
if (!isset($_SESSION['ss_regist1']))
	redirect('top.php');
$reg = &$_SESSION['ss_regist1'];

// ����Ͽ�������¸
$_SESSION['ss_seq_no'] = $reg->save_db_kari();

// �᡼������
mail_send($reg, $_SESSION['ss_seq_no']);

// ���å�����ѿ����
session_destroy();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������Eco���������Ȥ�Eco���������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
<img src="img/kado_1.gif" width="35" height="35"></td>
<td align="center" valign="bottom">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><img src="img/nyuukai/title/m_end.gif" width="196" height="38" alt="̵����λ���ޤ���"></td>
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

<table border="0" cellspacing="0" cellpadding="0" width="640" height="90%">
<tr height=250>
<td>
<div align="center"><img src="img/nyuukai/zou_img/info.gif" width="530" height="222" border="0"></a></div>
</td>
</tr>
</table>

<hr width="95%" size="1"></td>
<td><br></td>
</tr>
<tr>
<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
<td align="center" valign="bottom">
<table border="0" cellspacing="0" cellpadding="0">

</table>
<br>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><font size=3>
�����³����Ƴ�����ˤ�<font color="#0000FF">�᡼���URL</font>��<font color="#FF0000">����å�</font>���Ƥ���������<br><br>
</font>
<font size=2 color="#505050">������᡼�뤬�Ϥ��ʤ����ϡ��ȥåץڡ�����������󤷤�ĺ����<br>
������Ǥ���<a href="mailto:info@ecofootprint.jp">������</a>�ޤǤ�Ϣ����������<br><br>
<a href="mailto:info@ecofootprint.jp">xxx��������Club��̳��</a></font>
</td>
</tr>
<tr>
<td><br></td>
</tr>
<tr>
<td align="center">
<a href="top.php"><img src="img/nyuukai/button/top.gif" width="99" height="40" border="0" alt="TOP�����"></a>
</td>
<tr>
<td height="7"><img src="img/spacer.gif"  width="520" height="7"></td>
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