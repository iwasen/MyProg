<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ץ쥼��ȱ������
'******************************************************/

$title_text = $TT_server_mainte;
$title_color = $TC_OTHER;

$default_msg = "�� @start_date@��@end_date@�˥����Х��ƥʥ󥹤�Ԥ��ޤ�����������ޤ��������δ֤ϥ��ޥ����ۿ����뤳�Ȥ��Ǥ��ޤ���Τǡ�����դ��������ޤ��褦���ꤤ�������ޤ���";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
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
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnSubmit_form1() {
  fm = document.form1;
	if (!check_date(fm.start_year.value, fm.start_month.value, fm.start_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.start_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.start_year.value, fm.start_month.value - 1, fm.start_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.start_day.focus();
		return(false);
	}
	if (!check_date(fm.end_year.value, fm.end_month.value, fm.end_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.end_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.end_year.value, fm.end_month.value - 1, fm.end_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.end_day.focus();
		return(false);
	}

	if (!check_date(fm.info_s_year.value, fm.info_s_month.value, fm.info_s_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.info_s_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.info_s_year.value, fm.info_s_month.value - 1, fm.info_s_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.info_s_day.focus();
		return(false);
	}
	if (!check_date(fm.info_e_year.value, fm.info_e_month.value, fm.info_e_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.info_e_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.info_e_year.value, fm.info_e_month.value - 1, fm.info_e_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.info_e_day.focus();
		return(false);
	}
  if (fm.sp_msg.value == "") {
    alert("�����Ȥ����Ϥ��Ƥ���������");
    fm.sp_msg.focus();
    return false;
  }
	return confirm("��Ͽ���ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="update.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">��ͽ��ػߴ��־ܺ�����</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="750">
		<tr>
			<td class="m4" width=120><nobr>���������</nobr></td>
			<td class="n6">
				<select name="start_year"><? select_year(2002, ' ', ''); ?></select>ǯ
				<select name="start_month"><? select_month(' ', ''); ?></select>��
				<select name="start_day"><? select_day(' ', ''); ?></select>��
				<select name="start_hour"><? select_hour(' ', ''); ?></select>�� �� 
				<select name="end_year"><? select_year(2002, ' ', ''); ?></select>ǯ
				<select name="end_month"><? select_month(' ', ''); ?></select>��
				<select name="end_day"><? select_day(' ', ''); ?></select>��
				<select name="end_hour"><? select_hour(' ', ''); ?></select>��
			</td>
		</tr>
		<tr>
			<td class="m4" width=120><nobr>���ʥ���ɽ��</nobr></td>
			<td class="n6">
				<select name="info_s_year"><? select_year(2002, ' ', ''); ?></select>ǯ
				<select name="info_s_month"><? select_month(' ', ''); ?></select>��
				<select name="info_s_day"><? select_day(' ', ''); ?></select>�������������������� 
				<select name="info_e_year"><? select_year(2002, ' ', ''); ?></select>ǯ
				<select name="info_e_month"><? select_month(' ', ''); ?></select>��
				<select name="info_e_day"><? select_day(' ', ''); ?></select>��
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>������</nobr></td>
			<td class="n6"><font class="note">������ʸ�����Ȥ��ޤ����ʳ��������� @start_date@ ����λ������ @end_date@ ��</font><br>
			<textarea name="sp_msg" cols="70" rows="5"><?=$default_msg?></textarea></td>
		</tr>
	</table><br>
	<input type="hidden" name="next_action" value="new">
	<input type="submit" value=" ��Ͽ " onclick="document.form1.next_action.value='new'">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
