<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ɲ�ȯ��Ƚ�ǥޥ����Խ�
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global('mypartner', 'My�ѡ��ȥʡ�����', '�ɲ�ȯ��Ƚ�ǥޥ����Խ�', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function add_new() {
	var tb = document.getElementById("tbl").getElementsByTagName("TBODY")[0];
	var tr = document.getElementById("add_line");
	var new_row = tr.cloneNode(true);
	var input = new_row.getElementsByTagName("INPUT");
	input[0].value = "";
	input[1].value = "";
	input[2].value = "";
	tb.insertBefore(new_row, tb.laxxxhild);
}
function check_num(n) {
	for (i = 0; i < n.length; i++){
		if (n.charAt(i) < "0" || n.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function compare(a, b) {
	return a[1] - b[1];
}
function onsubmit_form1(f) {
	var e_start, e_end, e_factor;
	var ary = Array();
	var n = 0;
	var h = 0;
	var k = 0;
	var i;
	for (i = 0; i < f["hour_start[]"].length; i++) {
		e_start = f["hour_start[]"][i];
		e_end = f["hour_end[]"][i];
		e_factor = f["factor[]"][i];
		if (e_start.value != "") {
			if (!check_num(e_start.value)) {
				alert("���֤�Ⱦ�ѿ��������Ϥ��Ƥ���������" + i);
				e_start.focus();
				return false;
			}
		}
		if (e_end.value != "") {
			if (!check_num(e_end.value)) {
				alert("���֤�Ⱦ�ѿ��������Ϥ��Ƥ���������" + i);
				e_end.focus();
				return false;
			}
		}
		if (e_factor.value != "") {
			if (!check_num(e_factor.value)) {
				alert("������Ⱦ�ѿ��������Ϥ��Ƥ���������");
				e_factor.focus();
				return false;
			}
		}
		if (e_end.value == "999" && e_start.value == "") {
			alert("���Υǡ����Ϻ���Ǥ��ޤ���");
			e_start.focus();
			return false;
		}
		if (e_start.value != "" && e_end.value != "" && e_factor.value != "")
			ary[n++] = Array(i, parseInt(e_start.value));
	}
	if (ary.length == 0) {
		alert("ͭ���ʥǡ��������Ϥ���Ƥ��ޤ���");
		return false;
	}
	ary.sort(compare);
	for (n in ary) {
		i = ary[n][0];
		e_start = f["hour_start[]"][i];
		e_end = f["hour_end[]"][i];
		e_factor = f["factor[]"][i];
		if (parseInt(e_start.value) > parseInt(e_end.value)) {
			alert("���ϤȽ�λ���֤�����������ޤ���");
			e_start.focus();
			return false;
		}
		if (parseInt(e_start.value) > h) {
			alert("���֤�Ϣ³���Ƥ��ޤ���");
			e_start.focus();
			return false;
		} else if (parseInt(e_start.value) < h) {
			alert("���֤���ʣ���Ƥ��ޤ���");
			e_start.focus();
			return false;
		}
		if (parseInt(e_factor.value) < k) {
			alert("�������������Ƥ��ޤ���");
			e_factor.focus();
			return false;
		}
		h = parseInt(e_end.value) + 1;
		k = parseInt(e_factor.value);
	}
	return true;
}
//-->
</script>
</head>
<body>

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���ɲ�ȯ��Ƚ�ǥޥ�������</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='list.php'">
		</td>
	</tr>
</table>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" id="tbl">
	<tr class="tch">
<?
sort_header(0, '����');
sort_header(0, '����');
?>
	</tr>
<?
// �����ꥹ�ȼ���
$sql = "SELECT mas_hour,mas_factor FROM m_addition_send ORDER BY mas_hour";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$hour_ary[$i] = $fetch->mas_hour;
	$factor_ary[$i] = $fetch->mas_factor;
}

for ($i = 0; $i < $nrow; $i++) {
	if ($i < $nrow - 1) {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><input type="text" name="hour_start[]" <?=value($hour_ary[$i])?> size="10">��00��<input type="text" name="hour_end[]" <?=value($hour_ary[$i + 1] - 1)?> size="10">��59</td>
		<td align="center"><input type="text" name="factor[]" <?=value($factor_ary[$i])?> size="10">%</td>
	</tr>
<?
	} else {
?>
	<tr class="tc2">
		<td align="center"><input type="text" name="hour_start[]" <?=value($hour_ary[$i])?> size="10">��00��<input type="hidden" name="hour_end[]" value="999"></td>
		<td align="center">100%<input type="hidden" name="factor[]" <?=value($factor_ary[$i])?>></td>
	</tr>
<?
	}
?>
<?
}
?>
	<tr class="tc0" id="add_line">
		<td align="center"><input type="text" name="hour_start[]" size="10">��00��<input type="text" name="hour_end[]" size="10">��59</td>
		<td align="center"><input type="text" name="factor[]" size="10">%</td>
	</tr>
	<tr>
		<td colspan="3"><input type="button" value="�����������ɲ�" onclick="add_new()"></td>
	</tr>
</table>
<br>
<input type="submit" value="��������">
<input type="reset" value="�ꥻ�å�">
</form>
</div>

<? page_footer() ?>

</body>
</html>
