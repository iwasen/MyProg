<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ץ���������Ͽ
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

$sql = "SELECT op_mail_addr,op_password,op_org_id,op_name1,op_name2,op_name1_kana,op_name2_kana,op_nickname"
		. " FROM t_operator"
		. " WHERE op_operator_id=$g_operator_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
else
	program_err(__FILE__, __LINE__);
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
	if (f.pj_name.value == "") {
		alert("�ץ�������̾�����Ϥ��Ƥ���������");
		f.pj_name.focus();
		return false;
	}
	if (f.pjt_cd.value == "") {
		alert("�ץ������ȥ����ɤ����Ϥ��Ƥ���������");
		f.pjt_cd.focus();
		return false;
	}
	if (f.pjt_cd.value.match(/[0-9A-Za-z_]+/g) != f.pjt_cd.value) {
		alert("�ץ������ȥ����ɤ�Ⱦ�ѱѿ��������Ϥ��Ƥ���������");
		f.pjt_cd.focus();
		return false;
	}
	return confirm("�ץ������Ȥ���Ͽ���ޤ���������Ǥ�����");
}
//-->
	</script>
</head>

<body onload="document.form1.pj_name.focus()">
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
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>�������ץ���������Ͽ</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">�ץ�������̾</th>
					<td width="70%"><input type="text" name="pj_name" value="" size="50" class="form"></td>
				</tr>
				<tr>
					<th>�ץ������Ⱦܺ�</th>
					<td><textarea name="description" cols="60" rows="5" class="form"></textarea></td>
				</tr>
				<tr>
					<th>�ץ������ȥ�����</th>
					<td><input type="text" name="pjt_cd" value="" size="50" class="form">��Ⱦ�ѱѿ���</td>
				</tr>
				<tr>
					<th>�ץ������ȥڡ����������ե�����</th>
					<td>
						<input type="radio" name="src_file" value="link" checked>�̾�����
						<input type="radio" name="src_file" value="copy">�������ޥ�������
					</td>
				</tr>
				</table>
				<br>
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>���ץ������ȥ��о���</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">��̾</th>
					<td width="70%">�ᡧ<input type="text" name="op_name1" <?=value($fetch->op_name1)?> size="20" class="form"><br>
						̾��<input type="text" name="op_name2" <?=value($fetch->op_name2)?> size="20" class="form"></td>
				</tr>
				<tr>
					<th>��̾�ʥեꥬ�ʡ�</th>
					<td>�ᡧ<input type="text" name="op_name1_kana" <?=value($fetch->op_name1_kana)?> size="20" class="form"><br>
						̾��<input type="text" name="op_name2_kana" <?=value($fetch->op_name2_kana)?> size="20" class="form"></td>
				</tr>
				<tr>
					<th>�᡼�륢�ɥ쥹</th>
					<td><input type="text" name="mail_addr" <?=value($fetch->op_mail_addr)?> size="40" class="form"></td>
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

<? ope_footer() ?>

</div>
</body>

</html>
