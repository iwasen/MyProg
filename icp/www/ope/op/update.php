<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:���ڥ졼������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���ϥѥ�᡼��
$operator_id = $_GET['operator_id'];

$sql = "SELECT * FROM t_operator WHERE op_operator_id=" . sql_number($operator_id);
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net ���ڥ졼�����ڡ���</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function on_submit_form1(f) {
	if (f.name1.value == "") {
		alert("��̾�ʻ�ˤ����Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("��̾��̾�ˤ����Ϥ��Ƥ���������");
		f.name2.focus();
		return false;
	}
	if (f.mail_addr.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("������ѥ���ɤ����Ϥ��Ƥ���������");
		f.password.focus();
		return false;
	}
	if (f.org_id.value == "") {
		alert("��ȡ��ȿ������򤷤Ƥ���������");
		f.org_id.focus();
		return false;
	}
	return confirm("���ڥ졼�����򹹿����ޤ���������Ǥ�����");
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
			<td>

				<form method="post" name="form1" action="comp.php" onsubmit="return on_submit_form1(this)">
				<input type="hidden" name="operator_id" <?=value($operator_id)?>>
				<input type="hidden" name="proc" value="update">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>�����ڥ졼��������</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">��̾</th>
					<td width="70%">�ᡧ<input type="text" name="name1" <?=value($fetch->op_name1)?> size="20" class="form"><br>
						̾��<input type="text" name="name2" <?=value($fetch->op_name2)?> size="20" class="form"></td>
				</tr>
				<tr>
					<th>��̾�ʥեꥬ�ʡ�</th>
					<td>�ᡧ<input type="text" name="name1_kana" <?=value($fetch->op_name1_kana)?> size="20" class="form"><br>
						̾��<input type="text" name="name2_kana" <?=value($fetch->op_name2_kana)?> size="20" class="form"></td>
				</tr>
				<tr>
					<th>�᡼�륢�ɥ쥹</th>
					<td><input type="text" name="mail_addr" <?=value($fetch->op_mail_addr)?> size="50" class="form"></td>
				</tr>
				<tr>
					<th>������ѥ����</th>
					<td><input type="password" name="password" <?=value($fetch->op_password)?> size="40" class="form"></td>
				</tr>
				<tr>
					<th>��ȡ��ȿ�</th>
					<td><select name="org_id"><? select_org(DEFAULT_SELECT, $fetch->op_org_id) ?></select></td>
				</tr>
				<tr>
					<th>�˥å��͡���</th>
					<td><input type="text" name="nickname" <?=value($fetch->op_nickname)?> size="40" class="form"></td>
				</tr>
				<tr>
					<th>�����Ը���</th>
					<td><input type="checkbox" name="admin_auth" <?=value_checked('1', $fetch->op_admin_auth)?> id="f0-1"><label for="f0-1">ICP�����Ը���</label></td>
				</tr>
				<tr>
					<th>����</th>
					<td><input type="radio" name="status" <?=value_checked('1', $fetch->op_status)?> id="f1-1"><label for="f1-1">ͭ��</label>
					<input type="radio" name="status" <?=value_checked('9', $fetch->op_status)?> id="f1-2"><label for="f1-2">̵��</label></td>
				</tr>
				<tr>
					<th>�ץ������ȥ��о��󹹿�</th>
					<td><input type="checkbox" name="pjt_update" value="1" id="f2-1"><label for="f2-1">��ʬ�Υץ������ȥ��о���⹹������</label></td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%">
				<tr>
					<td align="center"><input type="submit" value="��������" class="btn">
					<input type="button" value="����󥻥�" class="btn" onclick="history.back()"></td>
				</tr>
				</table>
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
