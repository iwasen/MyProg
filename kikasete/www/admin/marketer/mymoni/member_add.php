<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ���˥������С��ɲý���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/my_monitor.php");

set_global('marketer', '�ޡ������������ãͣ���˥�������', '�ͣ���˥������С��ɲ�', BACK_TOP);

$mymoni = new my_monitor_class;
$mymoni->read_db($room_id);
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
	var f = document.form1;
	if (f.member.value == '') {
		alert("�ɲä�����С������Ϥ��Ƥ���������");
		f.member.focus();
		return false;
	}
	return confirm("���С����ɲä��ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="member_update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���ͣ���˥������С��ɲ�</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�롼��̾</td>
		<td class="n1" width="80%"><?=htmlspecialchars($mymoni->room_name)?></td>
	</tr>
	<tr>
		<td class="m1">�ɲå��С�</td>
		<td class="n1">
			<textarea name="member" cols=80 rows=15></textarea>
			<br><span class="note">�ʣ��Ԥ��ĥ�˥���ID�ȼ��ʾҲ�ʸ�򥫥�޶��ڤ�����Ϥ��Ƥ���������</span>
		</td>
	</tr>
</table>

<br>
<input type="submit" value="���ɲá�" onclick="document.form1.next_action.value='add'">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="room_id" value="<?=$room_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
