#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�ǥե����ͽ���ǽ�Ȼ����������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	var f = document.form1;
	var n = f["start_hour[]"].length;
	for (var i = 0; i < n; i++) {
		if (f["start_hour[]"][i].value != "" || f["start_minute[]"][i].value != "" || f["end_hour[]"][i].value != "" || f["end_minute[]"][i].value != "") {
			if (f["start_hour[]"][i].value == "") {
				alert("���ϻ��֤����򤵤�Ƥ��ޤ���");
				f["start_hour[]"][i].focus();
				return false;
			}
			if (f["start_minute[]"][i].value == "") {
				alert("���ϻ��֤����򤵤�Ƥ��ޤ���");
				f["start_minute[]"][i].focus();
				return false;
			}
			if (f["end_hour[]"][i].value == "") {
				alert("��λ���֤����򤵤�Ƥ��ޤ���");
				f["end_hour[]"][i].focus();
				return false;
			}
			if (f["end_minute[]"][i].value == "") {
				alert("��λ���֤����򤵤�Ƥ��ޤ���");
				f["end_minute[]"][i].focus();
				return false;
			}
			if (f["start_hour[]"][i].value + f["start_minute[]"][i].value > f["end_hour[]"][i].value + f["end_minute[]"][i].value) {
				alert("��λ����ϳ��ϻ�����̤��λ��֤���ꤷ�Ƥ���������");
				f["end_hour[]"][i].focus();
				return false;
			}
		}
	}
	return confirm("�ǥե����ͽ���ǽ�Ȥ���Ͽ���ޤ���������Ǥ�����");
}
function add_waku() {
	var table = document.getElementById("waku");
	var new_row = table.rows[1].cloneNode(true);
	new_row.childNodes[0].innerText = table.rows.length;
	table.firxxxhild.appendChild(new_row);
}
//-->
</script>
</head>
<body>

<? page_header('�ǥե����ͽ���ǽ������', true, true) ?>

<div align="center">
<form method="post" name="form1" action="waku_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0 width="400">
	<tr>
		<td class="m0" colspan=2>���ǥե����ͽ���ǽ�Ȼ��֤����Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td>��<?=decode_shop($shop_id)?>��<?=decode_room($room_id)?>��</td>
	</tr>
	<tr>
		<td>
			<table <?=LIST_TABLE?> width="100%" id="waku">
				<tr class="tch">
					<th>�ֹ�</th>
					<th>ͽ���ǽ�Ȼ���</th>
				</tr>
<?
$sql = "SELECT dw_start_time,dw_end_time FROM m_default_waku WHERE dw_room_id=$room_id ORDER BY dw_start_time";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow || $i < 6; $i++) {
	if ($i < $nrow) {
		$fetch = pg_fetch_object($result, $i);

		sscanf($fetch->dw_start_time, "%2s:%2s", &$start_hour, &$start_minute);
		sscanf($fetch->dw_end_time, "%2s:%2s", &$end_hour, &$end_minute);
	} else {
		$start_hour = '';
		$start_minute = '';
		$end_hour = '';
		$end_minute = '';
	}
?>
				<tr>
					<td align="center"><?=$i + 1?></td>
					<td align="center">
						<select name="start_hour[]"><? select_hour($start_hour) ?></select>
						��
						<select name="start_minute[]"><? select_minute($start_minute) ?></select>
						��
						<select name="end_hour[]"><? select_hour($end_hour) ?></select>
						��
						<select name="end_minute[]"><? select_minute($end_minute) ?></select>
					</td>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="shop_id" <?=value($shop_id)?>>
<input type="hidden" name="room_id" <?=value($room_id)?>>
<input type="button" value="���ɲ�" onclick="add_waku()">
<input type="submit" value="����Ͽ��">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
