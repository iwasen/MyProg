<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ���My�ѡ��ȥʡ�����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ�����', BACK_TOP);
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
	if (f.monitor_name.value == "") {
		alert("��˥���̾�����Ϥ��Ƥ���������");
		f.monitor_name.focus();
		return false;
	}
	return true;
}
function onsubmit_form2(f) {
	if (f.room_name.value == "") {
		alert("��ļ�̾�����Ϥ��Ƥ���������");
		f.room_name.focus();
		return false;
	}
	return true;
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td colspan=2>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0">��������������˥�����������Ϥ��Ƥ�������</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">��˥���̾</td>
		<td class="n1">
			<form method="post" name="form1" action="list_menber.php" target="_blank" onsubmit="return onsubmit_form1(this)">
			<input type="text" name="monitor_name" size=40 maxlength=100>
			<input type="submit" value="����"><br>
			<small>�����ä�����ļ��ΰ�����ɽ�����ޤ���</small>
			</form>
		</td>
	</tr>
	<tr>
		<td class="m1">��ļ�</td>
		<td class="n1">
			<form method="post" name="form2" action="list_room.php" target="_blank" onsubmit="return onsubmit_form2(this)">
			<input type="text" name="room_name" size=40 maxlength=100>
			<input type="submit" value="����"><br>
			<small>����ļ���ɽ�����ޤ���</small>
			</form>
		</td>
	</tr>
</table>
<br>
<input type="button" value="����롡" onclick="history.back()">

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
