<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ܡ��ʥ�����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global('mypartner', 'My�ѡ��ȥʡ�����', '�ܡ��ʥ�����', BACK_CLOSE);

sscanf($ym, '%4d%2d', $y, $m);

$sql = "SELECT pmp_bonus"
		. " FROM t_mp_monitor_point"
		. " WHERE pmp_pjt_id=$pjt_id AND pmp_year_month='$ym' AND pmp_monitor_id=$monitor_id";
$bonus = db_fetch1($sql, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.bonus.value == "") {
		alert("�ܡ��ʥ��ݥ���Ȥ����Ϥ��Ƥ���������");
		f.bonus.focus();
		return false;
	}
	if (f.bonus.value.match(/^\d+$/) == null) {
		alert("�ܡ��ʥ��ݥ���Ȥ�Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.bonus.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body onload="document.form1.bonus.focus()">
<form method="post" name="form1" action="p_bonus_update.php" onsubmit="return onsubmit_form1(this)">
<table border=1 cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td class="m1"></td>
		<td class="m1"><?=$m?>��</td>
	</tr>
	<tr>
		<td class="m1">������</td>
		<td class="n1"><?=number_format($bonus)?></td>
	</tr>
	<tr>
		<td class="m1">������</td>
		<td class="n1"><input type="text" name="bonus" value="" size="5"></td>
	</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="center">
			<input type="submit" value="����">
			<input type="button" value="���" onclick="window.close()">
		</td>
	</tr>
</table>
<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
<input type="hidden" name="ym" <?=value($ym)?>>
<input type="hidden" name="monitor_id" <?=value($monitor_id)?>>
</form>
</body>
</html>
