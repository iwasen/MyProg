<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ����󥱡����ɲ�ȯ������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// My���󥱡��Ⱦ����ɤ߹���
$myenq = new my_enquete_class;
$myenq->read_db($marketer_id, $my_enq_no);
$enquete = &$myenq->enquete;
$search = &$myenq->search;
$cell = &$myenq->cell;

// ��о������
$where = $search->make_sql() . " AND mn_monitor_id NOT IN (SELECT el_monitor_id FROM t_enquete_list WHERE el_enquete_id={$enquete->enquete_id})";

// �ɲ��ۿ���ǽ�������
if ($cell->cell_option == 2) {
	$monitor_ary = $cell->get_search_list2($where, 0);
	$send_num = count($monitor_ary);
} else {
	$sql = "SELECT COUNT(*) FROM t_monitor WHERE $where";
	$send_num = db_fetch1($sql, 0);
}

// �ɲ�����Ѥߡ�̤�ۿ��˷���򸺻�
$sql = "SELECT SUM(mas_send_num) FROM t_myenq_add_send WHERE mas_marketer_id=$marketer_id AND mas_my_enq_no=$my_enq_no";
$send_num -= db_fetch1($sql, 0);
if ($send_num < 0)
	$send_num = 0;

// �ɲ��ۿ������Υǥե����
$default_hour = 18;
$time = time();
if (date('H', $time) >= $default_hour)
	$time += 24 * 26 * 60;
$year = date('Y', $time);
$month = date('m', $time);
$day = date('d', $time);
$hour = $default_hour;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�ɲ��ۿ�</title>
<script type="text/javascript">
<!--
function onload_body() {
	var f = document.form1;
	returnValue = false;
	f.add_send_num.focus();
}
function onclick_add_send() {
	var f = document.form1;
	if (f.add_send_num.value <= 0) {
		alert("�ɲ��ۿ��������Ϥ��Ƥ���������");
		f.add_send_num.focus();
		return;
	}
	if (!check_num(f.add_send_num.value)) {
		alert("�ɲ��ۿ���������������ޤ���");
		f.add_send_num.focus();
		return;
	}
	if (f.add_send_num.value > <?=$send_num?>) {
		alert("�ɲ��ۿ������ɲ��ۿ���ǽ���ʲ������ꤷ�Ƥ���������");
		f.add_send_num.focus();
		return;
	}
	if (!check_date(f.year.value, f.month.value, f.day.value)) {
		alert("�ۿ�����������������ޤ���");
		f.year.focus();
		return;
	}
	if (f.hour.value > 23) {
		alert("���֤�����������ޤ���");
		f.hour.focus();
		return;
	}
	var send_date = new Date(f.year.value, f.month.value - 1, f.day.value, f.hour.value);
	if (send_date < new Date()) {
		alert("�ۿ������˲������դ����Ϥ���Ƥ��ޤ���");
		f.year.focus();
		return;
	}
	if (send_date > new Date(<?=$enquete->end_date_y?>, <?=$enquete->end_date_m?> - 1, <?=$enquete->end_date_d?>, <?=$enquete->end_date_h?>)) {
		alert("�ۿ����������󥱡��Ƚ�λ������᤮�Ƥ��ޤ���");
		f.year.focus();
		return;
	}
	dialogArguments.add_send_num.value = f.add_send_num.value;
	dialogArguments.start_date_y.value = f.year.value;
	dialogArguments.start_date_m.value = f.month.value;
	dialogArguments.start_date_d.value = f.day.value;
	dialogArguments.start_date_h.value = f.hour.value;
	returnValue = true;
	window.close();
}
function check_num(n) {
	for (i = 0; i < n.length; i++){
		if (n.charAt(i) < "0" || n.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
		if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
			return true;
		else
			return false;
	}
}
//-->
</script>
</head>
<body bgcolor="threedface" onload="onload_body()">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
	<tr>
		<td align="center" valign="center">
			<table border=0>
				<tr>
					<td>�ɲ��ۿ���ǽ��</td>
					<td>��</td>
					<td ><?=number_format($send_num)?> ��</td>
				</tr>
				<tr>
					<td>�ɲ��ۿ���</td>
					<td>��</td>
					<td><input type="text" name="add_send_num" size=8><font size="-1">��</font></td>
				</tr>
				<tr>
					<td>�ۿ�����</td>
					<td>��</td>
					<td>
						<font size="-1">
						<input type="text" name="year" size=5 maxlength=4 <?=value($year)?>>ǯ
						<input type="text" name="month" size=3 maxlength=2 <?=value($month)?>>��
						<input type="text" name="day" size=3 maxlength=2 <?=value($day)?>>��
						<input type="text" name="hour" size=3 maxlength=2 <?=value($hour)?>>��
					</font>
					</td>
				</tr>
			</table>
			<br>
			<input type="button" value="����OK����" onclick="onclick_add_send()">
			<input type="button" value="����󥻥�" onclick="window.close()">
		</td>
	</tr>
</table>
</form>
</body>
</html>
