<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ȿ���Ͽ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

function select_org() {
	$sql = "SELECT or_org_id,or_name"
			. " FROM m_org"
			. " WHERE or_status=1 AND or_type=1"
			. " ORDER BY or_org_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option value="', $fetch->or_org_id, '">', $fetch->or_org_id, '.', htmlspecialchars($fetch->or_name), "</option>\n";
	}
}
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
	return confirm("�ȿ�����Ͽ���ޤ���������Ǥ�����");
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
					<td>�������ȿ���Ͽ</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">�ȿ�̾</th>
					<td width="70%"><input type="text" name="or_name" value="" size="50" class="form"></td>
				</tr>
				<tr>
					<th>�ȿ�����</th>
					<td><textarea name="description" cols="60" rows="5" class="form"></textarea></td>
				</tr>
				<tr>
					<th>�ȿ�������</th>
					<td>
						<input type="radio" name="type" value="1" id="1"><label for="1">���</label><br>
						<input type="radio" name="type" value="2" id="2"><label for="2">�ͥåȥ��</label>

							<div class="shozoku">�ͥåȥ���ν�°��Ȥ����򤷤Ƥ���������<br>
								<select name="parent_org_id1" class="form">
									<option value="" selected>�����餫�餪���Ӥ�������</option>
<? select_org() ?>
								</select>
							</div>

						<input type="radio" name="type" value="3" id="3"><label for="3">�ϥ����ꥹ��</label><br>

							<div class="shozoku">�ϥ����ꥹ�Ȥν�°��Ȥ����򤷤Ƥ���������<br>
								<select name="parent_org_id2" class="form">
									<option value="" selected>�����餫�餪���Ӥ�������</option>
<? select_org() ?>
								</select>
							</div>

					</td>
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
