<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦�����᡼��ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/follow_mail.php");
include("$inc/rel_mail.php");

// ���ϥѥ�᡼��
$user_id = $_GET['user_id'];
$vol_no = $_GET['vol_no'];
$mail = $_GET['mail'];

if ($mail == 'follow') {
	// ����ե����᡼�����
	$kind = '����ե����᡼��';
	get_follow_mail($user_id, $vol_no, '', $subject, $body, $from_name, $from_addr);
} else {
	// ��³��졼�����᡼�����
	$kind = '��³��졼�����᡼��';
	get_relation_mail($user_id, $vol_no, '', $subject, $body, $from_name, $from_addr);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header("OneToOne�᡼���{$kind}ɽ��") ?>

<div align="center">
<form>
<table order=0 cellspacing=1 cellpadding=0 width="100%">
	<tr>
		<td>Vol.<?=$vol_no?>����̾��<font color="#333388"><b><?=htmlspecialchars($subject)?></b></font></td>
		<td align="right"><input type="button" value="���Ĥ��롡" onclick="window.close()"></td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
	<tr>
		<td><pre><?=htmlspecialchars($body)?></pre></td>
	</tr>
</table>
<br>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</form>
</div>

<? center_footer() ?>


</body>
</html>
