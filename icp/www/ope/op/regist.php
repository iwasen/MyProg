<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:���ڥ졼����Ͽ
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
	return confirm("���ڥ졼��������Ͽ���ޤ���������Ǥ�����");
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
				<input type="hidden" name="proc" value="regist">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>���������ڥ졼������Ͽ</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">��̾</th>
					<td width="70%">�ᡧ<input type="text" name="name1" value="" size="20" class="form"><br>
						̾��<input type="text" name="name2" value="" size="20" class="form"></td>
				</tr>
				<tr>
					<th>��̾�ʥեꥬ�ʡ�</th>
					<td>�ᡧ<input type="text" name="name1_kana" value="" size="20" class="form"><br>
						̾��<input type="text" name="name2_kana" value="" size="20" class="form"></td>
				</tr>
				<tr>
					<th>�᡼�륢�ɥ쥹</th>
					<td><input type="text" name="mail_addr" value="" size="50" class="form"></td>
				</tr>
				<tr>
					<th>������ѥ����</th>
					<td><input type="password" name="password" value="" size="40" class="form"></td>
				</tr>
				<tr>
					<th>��ȡ��ȿ�</th>
					<td><select name="org_id"><? select_org(DEFAULT_SELECT) ?></select></td>
				</tr>
				<tr>
					<th>�˥å��͡���</th>
					<td><input type="text" name="nickname" value="" size="40" class="form"></td>
				</tr>
				<tr>
					<th>�����Ը���</th>
					<td><input type="checkbox" name="admin_auth" value="1" id="f0-1"><label for="f0-1">ICP�����Ը���</label></td>
				</tr>
				<tr>
					<th>����</th>
					<td><input type="radio" name="status" value="1" checked id="f1-1"><label for="f1-1">ͭ��</label>
					<input type="radio" name="status" value="9" id="f1-2"><label for="f1-2">̵��</label></td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%">
				<tr>
					<td align="center"><input type="submit" value="����Ͽ��" class="btn">
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
