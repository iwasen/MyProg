<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�����ƥ�����ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���ϥѥ�᡼��
$key = $_GET['key'];

// ��Ͽ�Ѥߥ����ƥ�������
$sql = "SELECT * FROM m_system WHERE sy_key=" . sql_char($key);
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	program_err(__FILE__, __LINE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>icp.net ���ڥ졼�����ڡ���</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("�����ƥ��������򹹿����ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- ������ƥ�� -->
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td align="center">

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
	<tr>
		<td>�������ƥ��������Ϥ��Ƥ���������</td>
	</tr>
</table>
<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
	<tr>
		<th width="20%">���ܥ���</th>
		<td>
			<?=$fetch->sy_key?>
			<input type="hidden" name="key" <?=value($key)?>>
		</td>
	</tr>
	<tr>
		<th>����̾</th>
		<td>
			<input type="text" name="caption" size=100 maxlength=100 <?=value($fetch->sy_caption)?>>
		</td>
	</tr>
	<tr>
		<th>��������</th>
		<td>
			<input type="text" name="value" size=100 maxlength=100 <?=value($fetch->sy_value)?>>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="update">
</form>

			</td>
		</tr>
		</table>

	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
