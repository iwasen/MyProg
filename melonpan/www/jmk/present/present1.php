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

$title_text = $TT_present;
$title_color = $TC_OTHER;

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
function  ShowHide( id, show ) {
	var disp = document.all(id).style.display;
	if (show == 'no')
		document.all(id).style.display = "none";
	else
	if (show == 'yes')
		document.all(id).style.display = "block";
}

function OnClick_showpict() {
	if (document.form1.prize_pict.value != "") {
		document.all.pict.style.width = "";
		document.all.pict.style.height = "";
		document.all.pict.src = document.form1.prize_pict.value;
	}
}
function OnClick_info() {
  var win;
  win = window.open(document.form1.link_url.value, "link_url", "scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes,location=yes");
  win.focus();
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
  if (fm.present_name.value == "") {
    alert("�ץ쥼���̾�����Ϥ��Ƥ���������");
    fm.present_name.focus();
    return false;
  }
  if (fm.explain.value == "") {
    alert("����ʸ�����Ϥ��Ƥ���������");
    fm.explain.focus();
    return false;
  }
  if (fm.prize_pict.value == "") {
    alert("���ʲ��������Ϥ��Ƥ���������");
    fm.prize_pict.focus();
    return false;
  }
  if (fm.presenter.value == "") {
    alert("�󶡲��̾�����Ϥ��Ƥ���������");
    fm.presenter.focus();
    return false;
  }
  if (fm.keyword_flag[0].checked == false && fm.keyword_flag[1].checked == false) {
    alert("���������������򤷤Ƥ���������");
    return false;
  }
  if (fm.keyword_flag[0].checked == true && fm.keyword1.value == "") {
    alert("������ɤ����Ϥ��Ƥ���������");
    fm.keyword1.focus();
    return false;
  }
  if (fm.prize_1.value == "") {
    alert("�����Ϳ������Ϥ��Ƥ���������");
    fm.prize_1.focus();
    return false;
  }
	if (!num_chk(fm.prize_1.value)){
		alert("�����Ϳ���Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		fm.prize_1.focus();
		return(false);
	}
  if (fm.prize_name1.value == "") {
    alert("����̾�����Ϥ��Ƥ���������");
    fm.prize_name1.focus();
    return false;
  }
	if (!num_chk(fm.prize_2.value)){
		alert("�����Ϳ���Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		fm.prize_2.focus();
		return(false);
	}
	if (!num_chk(fm.prize_3.value)){
		alert("�����Ϳ���Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		fm.prize_3.focus();
		return(false);
	}
	if (!check_date(fm.insert_s_year.value, fm.insert_s_month.value, fm.insert_s_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.insert_s_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.insert_s_year.value, fm.insert_s_month.value - 1, fm.insert_s_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.insert_s_day.focus();
		return(false);
	}
	if (!check_date(fm.insert_e_year.value, fm.insert_e_month.value, fm.insert_e_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.insert_e_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.insert_e_year.value, fm.insert_e_month.value - 1, fm.insert_e_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.insert_e_day.focus();
		return(false);
	}
	if (!check_date(fm.campaign_s_year.value, fm.campaign_s_month.value, fm.campaign_s_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.campaign_s_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.campaign_s_year.value, fm.campaign_s_month.value - 1, fm.campaign_s_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.campaign_s_day.focus();
		return(false);
	}
	if (!check_date(fm.campaign_e_year.value, fm.campaign_e_month.value, fm.campaign_e_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.campaign_e_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.campaign_e_year.value, fm.campaign_e_month.value - 1, fm.campaign_e_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.campaign_e_day.focus();
		return(false);
	}
	if (!check_date(fm.lottery_year.value, fm.lottery_month.value, fm.lottery_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		fm.lottery_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.lottery_year.value, fm.lottery_month.value - 1, fm.lottery_day.value);
	if (dd > dt) {
		alert("�������դϻ���Ǥ��ޤ���");
		fm.lottery_day.focus();
		return(false);
	}
	return confirm("��Ͽ���ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" ENCTYPE="multipart/form-data" action="present3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">���ץ쥼��ȱ���ܺ�����</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="700">
		<tr>
			<td class="m4"><nobr>���祿����</nobr></td>
			<td class="n6" colspan="2">
				<input type="radio" name="present_type" value="A" checked>��������
				<input type="radio" name="present_type" value="B">��¸����
			</td>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>�ץ쥼���̾</nobr></td>
			<td class="n6" colspan="2"><input size=60 name="present_name" maxlength=100></td>
		</tr>
		<tr>
			<td class="m4"><nobr>��󥯸��ƥ�����</nobr></td>
			<td class="n6" colspan="2"><input size=60 name="link_text" maxlength=100></td>
		</tr>
		<tr>
			<td class="m4"><nobr>����ڡ��������ȥ�</nobr></td>
			<td class="n6" colspan="2"><textarea name=page_title cols="70" rows="5"></textarea></td>
		</tr>
		<tr>
			<td class="m4"><nobr>���ʾҲ�ʸ</nobr></td>
			<td class="n6" colspan="2"><textarea name=explain cols="70" rows="8"></textarea></td>
		</tr>
		<tr>
			<td class="m4"><nobr>���ʲ���</nobr></td>
			<td class="n6" colspan="2">
				<img src="prize_pict.php?id=<? pr_id ?>" id="pict" alt="����" style="width:0;height:0"><br>
				<input size="40" name="prize_pict" type="file" >
				<input type="button" value="ɽ��" onclick="OnClick_showpict()">
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>�󶡲��̾</nobr></td>
			<td class="n6" colspan="2"><input size=60 name="presenter" maxlength=100></td>
		</tr>
		<tr>
			<td class="m4"><nobr>�󶡲��URL</nobr></td>
			<td class="n6" colspan="2">
				<input size=60 name="link_url" maxlength=100 value="http://">
				<input type="button" value="��ǧ" onclick="OnClick_info()">
				<font class="note">��Ⱦ�ѱѿ���</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>�����������</nobr></td>
			<td class="n6" colspan="2">
				<input type="radio" name="keyword_flag" value="1" onclick="ShowHide('show', 'yes')">����
				<input type="radio" name="keyword_flag" value="0" onclick="ShowHide('show', 'no')">�ʤ�
			</td>
		</tr>
		<tr ID="show" style="display:none;">
			<td class="m4"><nobr>�������</nobr></td>
			<td class="n6" colspan="2">
				<input size=40 name="keyword1" maxlength=20> <font class="note">�����ѡ�20ʸ�������</font><br>
				<input size=40 name="keyword2" maxlength=20><br>
				<input size=40 name="keyword3" maxlength=20>
			</td>
		</tr>
		<tr>
			<td class="m4" rowspan="3"><nobr>�����Ϳ�<br>��������Ӿ���̾</nobr></td>
			<td class="m6s" align="center" width="7%">����</td>
			<td class="n6">
				<input size=5 name="prize_1" maxlength=9>̾��
				<input type="text" name="prize_name1" size="50" maxlength="100">
			</td>
		</tr>
		<tr>
			<td class="m6s" align="center">����</td>
			<td class="n6">
				<input size=5 name="prize_2" maxlength=9>̾��
				<input type="text" name="prize_name2" size="50" maxlength="100">
			</td>
		</tr>
		<tr>
			<td class="m6s" align="center">����</td>
			<td class="n6">
				<input size=5 name="prize_3" maxlength=9>̾��
				<input type="text" name="prize_name3" size="50" maxlength="100">
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>�������������</nobr></td>
			<td class="n6" colspan="2">
				<select name="insert_s_year"><? select_year(2001, ' ', ''); ?></select>ǯ
				<select name="insert_s_month"><? select_month(' ', ''); ?></select>��
				<select name="insert_s_day"><? select_day(' ', ''); ?></select>����
				<select name="insert_e_year"><? select_year(2001, ' ', ''); ?></select>ǯ
				<select name="insert_e_month"><? select_month(' ', ''); ?></select>��
				<select name="insert_e_day"><? select_day(' ', ''); ?></select>��
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>������մ���</nobr></td>
			<td class="n6" colspan="2">
				<select name="campaign_s_year"><? select_year(2001, ' ', ''); ?></select>ǯ
				<select name="campaign_s_month"><? select_month(' ', ''); ?></select>��
				<select name="campaign_s_day"><? select_day(' ', ''); ?></select>����
				<select name="campaign_e_year"><? select_year(2001, ' ', ''); ?></select>ǯ
				<select name="campaign_e_month"><? select_month(' ', ''); ?></select>��
				<select name="campaign_e_day"><? select_day(' ', ''); ?></select>��
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>������</nobr></td>
			<td class="n6" colspan="2">
				<select name="lottery_year"><? select_year(2001, ' ', ''); ?></select>ǯ
				<select name="lottery_month"><? select_month(' ', ''); ?></select>��
				<select name="lottery_day"><? select_day(' ', ''); ?></select>��						</td>
		</tr>
	</table><br>
	<input type="hidden" name="status" value="0" >
	<input type="submit" value=" ��Ͽ ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
