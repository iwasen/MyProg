<?
// ctl.php����include���줿�ΤǤʤ���н�λ�ڡ��������Ф�
if (!isset($enquete)) {
	header("location: end.html");
	exit;
}

$question_no = $enquete->get_question_no();
$gyoshu_name = $enquete->get_gyoshu_name();
$gyoshu_ary = $enquete->get_gyoshu_ary();

$category_qno = mb_convert_kana($question_no, 'N');
$gyoshu_qno = mb_convert_kana($question_no + 1, 'N');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
.font-qa {font-size: 12pt; color: black}
.matrix th {background-color: #9ddfff;}
</style>
<title>���󥱡���</title>
<script type="text/javascript">
<!--
function next() {
	var f = document.form1;
	var tel = f["category_ans[1]"];
	var addr = f["category_ans[2]"];
	var none = f["category_ans[3]"];
	if (!tel.checked && !addr.checked && !none.checked) {
		alert("��<?=$category_qno?>���ҤȤĤ����򤵤�Ƥ��ʤ��褦�Ǥ���");
		tel.focus();
		return;
	}
	var count_tel = count_check(f["gyoshu_tel[]"]);
	var count_addr = count_check(f["gyoshu_addr[]"]);
	if (tel.checked && count_tel == 0) {
		alert("��<?=$category_qno?>�ǡ����ä򤫤���פ����򤵤줿���ϡ�\n��<?=$gyoshu_qno?>���椫������ä򤫤���פ򣱤İʾ����򤷤Ƥ���������");
		f["gyoshu_tel[]"][0].focus();
		return;
	}
	if (addr.checked && count_addr == 0) {
		alert("��<?=$category_qno?>�ǡֽ����Ĵ�٤�פ����򤵤줿���ϡ�\n��<?=$gyoshu_qno?>���椫��ֽ����Ĵ�٤�פ򣱤İʾ����򤷤Ƥ���������");
		f["gyoshu_addr[]"][0].focus();
		return;
	}
	if (none.checked && (count_tel != 0 || count_addr != 0)) {
		alert("��<?=$category_qno?>�ǡ֤�����ˤϤʤ��פ����򤵤줿���ϡ�\n��<?=$gyoshu_qno?>�����򤹤뤳�ȤϤǤ��ޤ���");
		return;
	}
	if (!tel.checked && count_tel != 0) {
		alert("��<?=$category_qno?>�ǡ����ä򤫤���פ����򤵤�Ƥ��ʤ����ϡ�\n��<?=$gyoshu_qno?>�Ρ����ä򤫤���פ����򤹤뤳�ȤϤǤ��ޤ���");
		return;
	}
	if (!addr.checked && count_addr != 0) {
		alert("��<?=$category_qno?>�ǡֽ����Ĵ�٤�פ����򤵤�Ƥ��ʤ����ϡ�\n��<?=$gyoshu_qno?>�Ρֽ����Ĵ�٤�פ����򤹤뤳�ȤϤǤ��ޤ���");
		return;
	}
	f.submit();
}
function count_check(c) {
	var count = 0;
	for (var i = 0; i < c.length; i++) {
		if (c[i].checked)
			count++;
	}
	return count;
}
function onclick_tel_addr(c) {
	var f = document.form1;
	if (c)
		f["category_ans[3]"].checked = false;
}
function onclick_none(c) {
	var f = document.form1;
	if (c) {
		f["category_ans[1]"].checked = false;
		f["category_ans[2]"].checked = false;
	}
}
//-->
</script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<!-- ���� -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">����<?=$category_qno?>��<br>
			&nbsp;&nbsp;&nbsp;���ʤ�����<b><font color="#B22222"><?=htmlspecialchars($gyoshu_name)?></font>��</b>�ۤΤ����줫�ˤĤ��ơ�<br>
		&nbsp;&nbsp;&nbsp;���ä򤫤����ꡢ�����Ĵ�٤��ꤷ�ޤ��������ƤϤޤ��Τ򤤤��ĤǤ⤪���Ӥ���������<br></td>
	</tr>
</table>
<!-- ����� -->
<br>

<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">��</td>
		<td class="font-qa" align="left">
			<nobr><input type="checkbox" name="category_ans[1]" value="1" onclick="onclick_tel_addr(checked)">���ä򤫤���</nobr><br>
			<nobr><input type="checkbox" name="category_ans[2]" value="2" onclick="onclick_tel_addr(checked)">�����Ĵ�٤�</nobr><br>
			<nobr><input type="checkbox" name="category_ans[3]" value="3" onclick="onclick_none(checked)">������ˤϤʤ�</nobr><br>
		</td>
	</tr>
</table>
<br><br>
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">
			����<?=$gyoshu_qno?>��<br>
			&nbsp;&nbsp;&nbsp;��μ���ǡ����ä򤫤���١ؽ����Ĵ�٤�٤˥����å��򤵤줿���Τߤ���������������<br>
			&nbsp;&nbsp;&nbsp;���ʤ��������Υ��ƥ��꡼�ˤĤ��ơ����ä򤫤����ꡢ�����Ĵ�٤��ΤˤĤ��ơ�<br>
			&nbsp;&nbsp;&nbsp;���ƤϤޤ��Τ򤽤줾��ҤȤĤ��Ĥ����Ӥ���������<br>
		</td>
	</tr>
</table>
<!-- ����� -->
<br>
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">��</td>
		<td class="font-qa" align="left">
			<table border="1" cellspacing="0" cellpadding="2" width="80%" class="matrix">
				<tr>
					<th width="50%">&nbsp;</th>
					<th width="25%">���ä򤫤���</th>
					<th width="25%">�����Ĵ�٤�</th>
				</tr>
<?
foreach ($gyoshu_ary as $gyoshu_no => $gyoshu_name) {
?>
				<tr align="center" bgcolor="<?=$gyoshu_no % 2 ? '#E0FFFF': '#FFFFFF'?>">
					<td><b><?=htmlspecialchars($gyoshu_name)?></b></th>
					<td><input type="checkbox" name="gyoshu_tel[]" value="<?=$gyoshu_no?>"></td>
					<td><input type="checkbox" name="gyoshu_addr[]" value="<?=$gyoshu_no?>"></td>
				</tr>
<?
}
?>
			</table><br><br>
		</td>
	</tr>
</table>

<center>
<input type="button" value="���μ���" onclick="next()">
</center>
<input type="hidden" name="question_no" value="<?=$question_no?>">
<input type="hidden" name="category_id" value="<?=$enquete->get_category_id()?>">
</form>
</body>
</html>
