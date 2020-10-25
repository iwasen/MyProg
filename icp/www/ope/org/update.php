<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ȿ�����
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

// �ȿ�����
function select_org2($selected) {
	$sql = "SELECT or_org_id,or_name"
			. " FROM m_org"
			. " WHERE or_status=1 AND or_type=1"
			. " ORDER BY or_org_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->or_org_id, $selected), '>', $fetch->or_org_id, '.', htmlspecialchars($fetch->or_name), "</option>\n";
	}
}

// ���ϥѥ�᡼��
$org_id = $_GET['org_id'];

$sql = "SELECT * FROM m_org WHERE or_org_id=" . sql_number($org_id);
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
	switch (f.proc.value) {
	case "update":
		return check_update(f);
	case "delete":
		return check_delete(f);
	}
	return false;
}
function check_update(f) {
	var i, c;
	if (f.or_name.value == "") {
		alert("�ȿ�̾�����Ϥ��Ƥ���������");
		f.or_name.focus();
		return false;
	}
	c = false;
	for (i = 0; i < f.type.length; i++) {
		if (f.type[i].checked) {
			c = true;
			break;
		}
	}
	if (!c) {
		alert("�ȿ�����������򤷤Ƥ���������");
		f.type[0].focus();
		return false;
	}
	if (f.type[1].checked) {
		if (f.parent_org_id1.value == "") {
			alert("�ͥåȥ���ν�°��Ȥ����򤷤Ƥ���������");
			f.parent_org_id1.focus();
			return false;
		}
	}
	if (f.type[2].checked) {
		if (f.parent_org_id2.value == "") {
			alert("�ϥ����ꥹ�Ȥν�°��Ȥ����򤷤Ƥ���������");
			f.parent_org_id2.focus();
			return false;
		}
	}
	return confirm("�����ȿ��򹹿����ޤ���������Ǥ�����");
}
function check_delete(f) {
	return confirm("�����ȿ��������ޤ���������Ǥ�����");
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
				<input type="hidden" name="org_id" <?=value($org_id)?>>
				<input type="hidden" name="proc">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>���ȿ�����</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">�ȿ�̾</th>
					<td width="70%"><input type="text" name="or_name" <?=value($fetch->or_name)?> size="50" class="form"></td>
				</tr>
				<tr>
					<th>�ȿ�����</th>
					<td><textarea name="description" cols="60" rows="5" class="form"><?=htmlspecialchars($fetch->or_description)?></textarea></td>
				</tr>
				<tr>
					<th>�ȿ�������</th>
					<td>
						<input type="radio" name="type" <?=value_checked('1', $fetch->or_type)?> id="1"><label for="1">���</label><br>
						<input type="radio" name="type" <?=value_checked('2', $fetch->or_type)?> id="2"><label for="2">�ͥåȥ��</label>

							<div class="shozoku">�ͥåȥ���ν�°��Ȥ����򤷤Ƥ���������<br>
								<select name="parent_org_id1" class="form">
									<option value="" selected>�����餫�餪���Ӥ�������</option>
<? select_org2($fetch->or_type == 2 ? $fetch->or_parent_org_id : '') ?>
								</select>
							</div>

						<input type="radio" name="type" <?=value_checked('3', $fetch->or_type)?> id="3"><label for="3">�ϥ����ꥹ��</label><br>

							<div class="shozoku">�ϥ����ꥹ�Ȥν�°��Ȥ����򤷤Ƥ���������<br>
								<select name="parent_org_id2" class="form">
									<option value="" selected>�����餫�餪���Ӥ�������</option>
<? select_org2($fetch->or_type == 3 ? $fetch->or_parent_org_id : '') ?>
								</select>
							</div>

					</td>
				</tr>
				<tr>
					<th>����</th>
					<td><input type="radio" name="status" <?=value_checked('1', $fetch->or_status)?> id="f1-1"><label for="f1-1">ͭ��</label>
					<input type="radio" name="status" <?=value_checked('9', $fetch->or_status)?> id="f1-2"><label for="f1-2">̵��</label></td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%">
				<tr>
					<td align="center">
						<input type="submit" value="��������" class="btn" onclick="document.form1.proc.value='update'">
						<!--<input type="submit" value="�������" class="btn" onclick="document.form1.proc.value='delete'">-->
						<input type="button" value="����󥻥�" class="btn" onclick="history.back()">
					</td>
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
