<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�����ƥ�����ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$key = $_GET['key'];

// ��Ͽ�Ѥߥ����ƥ�������
$sql = "SELECT * FROM t_system WHERE sys_key=" . sql_char($key);
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('�����ƥ���󤬼����Ǥ��ޤ���', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("�����ƥ��������򹹿����ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡å����ƥ������ѹ����ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�������ƥ��������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���ܥ���</td>
		<td class="n1">
			<?=$fetch->sys_key?>
			<input type="hidden" name="key" <?=value($key)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="caption" size=100 maxlength=100 <?=value($fetch->sys_caption)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1">
			<input type="text" name="value" size=100 maxlength=100 <?=value($fetch->sys_value)?>>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="update">
</form>
</div>

<? center_footer() ?>

</body>
</html>
