<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��˥����Ŀ͸���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/select.php");

set_global('monitor', '��˥�������', '��˥����Ŀ͸���', BACK_TOP);
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
	if (f.birthday_y.value != '') {
		if (f.birthday_y.value.length != 4 || !is_number(f.birthday_y.value)) {
			alert("��ǯ������ǯ�ˤϣ���ο��������Ϥ��Ƥ���������");
			f.birthday_y.focus();
			return false;
		}
	}
	if (f.regist_y.value != '') {
		if (f.regist_y.value.length != 4 || !is_number(f.regist_y.value)) {
			alert("��Ͽ����ǯ�ˤϣ���ο��������Ϥ��Ƥ���������");
			f.regist_y.focus();
			return false;
		}
	}
	return true;
}
function is_number(str) {
	return !isNaN(parseInt(str));
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="list.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td colspan=2>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0">��������������˥�����������Ϥ��Ƥ�������</td>
					<td align="right">
						<input type="radio" name="and_or" value="A" checked>AND
						<input type="radio" name="and_or" value="O">OR
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">��˥���ID</td>
		<td class="n1">
			<input class="number" type="text" name="monitor_id" size=8 maxlength=6>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">�᡼�륢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=40 maxlength=100>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʴ�����</td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size=25 maxlength=25>��
			<br>
			<input class="kanji" type="text" name="name2" size=25 maxlength=25>̾
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1">
			<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25>��
			<br>
			<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25>̾
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����</td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4>ǯ
			<select name="birthday_m"><? select_month() ?></select>��
			<select name="birthday_d"><? select_day() ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">��Ͽ��</td>
		<td class="n1">
			����<input class="number" type="text" name="regist_y" size=4>ǯ
			<select name="regist_m"><? select_month() ?></select>��
			<select name="regist_d"><? select_day() ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<select name="type"><? select_monitor_type('- ����ʤ� -') ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<select name="status"><? select_monitor_status('- ����ʤ� -') ?></select>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="pset" value="1">
<input type="submit" value="��������">
<input type="button" value="����󥻥�" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
