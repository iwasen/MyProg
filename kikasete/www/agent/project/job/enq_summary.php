<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:���󥱡��ȼ»ܳ�����Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

//display����
function check_disp($sel1, $sel2) {
	if ($sel1 >  $sel2)
		echo "block";
	else
		echo "none";
}

//ʬ��ȯ������
function value_checked2($value, $checked) {
	if ($value <= $checked)
		return "value='$value' checked";
	else
		return "value='$value'";
}
//�ᥤ�����

set_global2('project', '�ץ������ȴ���', '���󥱡��ȼ»ܳ���', BACK_TOP);

$sql = "SELECT jb_job_type,jb_send_timing,jb_send_timing_h,jb_send_timing_d,jb_send_num,jb_virgin_mail,jb_bunkatsu_kankaku,jb_bunkatsu_kaisuu,jb_enq_web_url,en_title,en_start_date,en_end_date,en_point,EXISTS (SELECT * FROM t_job AS jb2 WHERE jb1.jb_job_id=jb2.jb_cp_job_id) AS jb_parent FROM t_job AS jb1 LEFT JOIN t_enquete ON jb_enquete_id=en_enquete_id WHERE jb_job_id=$job_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function OnChange_job_type(id, type) {
	bunkatsu_set.style.display = "none";
	if (type == 1)
		document.form1.all(id).style.display = "block";
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

function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}

function onSubmit_form1(f) {
  var f = document.form1;
	var now = new Date();
	if (f.title.value == "") {
		alert("���󥱡��ȥ����ȥ�����Ϥ��Ƥ���������");
		f.title.focus();
		return false;
	}
	if (!check_date(f.en_start_year.value, f.en_start_month.value, f.en_start_day.value)) {
		alert("���󥱡��ȳ��������λ��꤬����������ޤ���");
		f.en_start_day.focus();
		return(false);
	}
	var start_date = new Date(f.en_start_year.value, f.en_start_month.value - 1, f.en_start_day.value, f.en_start_hour.value);
	if (start_date < now) {
		if (!confirm("���󥱡��ȳ��������˲������դ����Ϥ���Ƥ��ޤ�����������Ǥ�����")) {
			f.en_start_year.focus();
			return false;
		}
	}
	if (!check_date(f.en_end_year.value, f.en_end_month.value, f.en_end_day.value)) {
		alert("���󥱡��Ƚ�λ�����λ��꤬����������ޤ���");
		f.en_end_day.focus();
		return(false);
	}
	var end_date = new Date(f.en_end_year.value, f.en_end_month.value - 1, f.en_end_day.value, f.en_end_hour.value);
	if (start_date > end_date) {
		alert("���󥱡��Ƚ�λ�����ϳ���������������դ����Ϥ��Ƥ���������");
		f.en_end_year.focus();
		return false;
	}
	if (f.en_point.value == ""){
		alert("���󥱡��ȥݥ���Ȥ����Ϥ��Ƥ���������");
		f.en_point.focus();
		return(false);
	}
	if (!num_chk(f.en_point.value)){
		alert("���󥱡��Ȳû��ݥ���Ȥ�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.en_point.focus();
		return(false);
	}
	if (f.send_num.value == ""){
		alert("����ȯ���������Ϥ��Ƥ���������");
		f.send_num.focus();
		return(false);
	}
	if (!num_chk(f.send_num.value)){
		alert("����ȯ������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.send_num.focus();
		return(false);
	}
	if (f.bunkatsu[0].checked == false && f.bunkatsu[1].checked == false) {
    alert("ʬ��ȯ����������򤷤Ƥ���������");
    return false;
	}
	if (f.bunkatsu[1].checked == true && f.bunkatsu_kaisuu.value == "") {
    alert("ʬ���������Ϥ��Ƥ���������");
		f.bunkatsu_kaisuu.focus();
		return(false);
	}
	if (f.bunkatsu[1].checked == true && !num_chk(f.bunkatsu_kaisuu.value)){
		alert("ʬ������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.bunkatsu_kaisuu.focus();
		return(false);
	}
	if (f.bunkatsu[1].checked == true && f.bunkatsu_kankaku.value == "") {
    alert("ʬ��ֳ֤����Ϥ��Ƥ���������");
		f.bunkatsu_kankaku.focus();
		return(false);
	}
	if (f.bunkatsu[1].checked == true && !num_chk(f.bunkatsu_kankaku.value)){
		alert("ʬ��ֳ֤�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.bunkatsu_kankaku.focus();
		return(false);
	}
	if (f.jb_send_timing) {
		if (f.jb_send_timing[1].checked == true && f.jb_send_timing_h.value == "") {
	    alert("���֤���ꤷ�Ƥ���������");
			f.jb_send_timing_h.focus();
			return(false);
		}
		if (f.jb_send_timing[1].checked == true && !num_chk(f.jb_send_timing_h.value)){
			alert("���֤�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			f.jb_send_timing_h.focus();
			return(false);
		}
		if (f.jb_send_timing[2].checked == true && f.jb_send_timing_d.value == "") {
	    alert("��������ꤷ�Ƥ���������");
			f.jb_send_timing_d.focus();
			return(false);
		}
		if (f.jb_send_timing[2].checked == true && !num_chk(f.jb_send_timing_d.value)){
			alert("������Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			f.jb_send_timing_d.focus();
			return(false);
		}
	}
	return confirm("����־������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="enq_save.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m0" colspan=2 align="left">�����󥱡��ȼ»ܳ��פ����ꤷ�Ʋ�������</td>
				</tr>
				<tr>
					<td class="m1">���󥱡��ȥ����ȥ�</td>
					<td class="n1">
						<input type="text" name="title" size=60 <?=value($fetch->en_title)?>>
					</td>
				</tr>
<?
if ($fetch->en_enq_type == 2) {
?>
				<tr>
					<td class="m1">���󥱡���URL</td>
					<td class="n1">
						<input type="text" name="enq_web_url" size=60 <?=value($fetch->jb_enq_web_url)?>>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td class="m1">���󥱡��ȳ�������</td>
					<td class="n1">
						<select name="en_start_year"><? select_year(date(Y),'',get_datepart('Y', $fetch->en_start_date))?></select>ǯ
						<select name="en_start_month"><? select_month(' ',get_datepart('M', $fetch->en_start_date))?></select>��
						<select name="en_start_day"><? select_day(' ',get_datepart('D', $fetch->en_start_date))?></select>��
						<select name="en_start_hour"><? select_hour(' ',get_datepart('h', $fetch->en_start_date))?></select>��
					</td>
				</tr>
				<tr>
					<td class="m1">���󥱡��Ƚ�λ����</td>
					<td class="n1">
						<select name="en_end_year"><? select_year(date(Y),'',get_datepart('Y', $fetch->en_end_date))?></select>ǯ
						<select name="en_end_month"><? select_month(' ',get_datepart('M', $fetch->en_end_date))?></select>��
						<select name="en_end_day"><? select_day(' ',get_datepart('D', $fetch->en_end_date))?></select>��
						<select name="en_end_hour"><? select_hour(' ',get_datepart('h', $fetch->en_end_date))?></select>��
					</td>
				</tr>
				<tr>
					<td class="m1" width=140>���󥱡��ȥݥ����</td>
					<td class="n1"><input class="number" type="text" name="en_point" size=6 maxlength=4 <?=value($fetch->en_point)?>><font size=-1>�ݥ����</font>
					<span class="note">��Ⱦ�ѿ�����</span></td>
				</tr>
				<tr>
					<td class="m1">����ȯ����</td>
					<td class="n1"><input class="number" type="text" name="send_num" size=6 maxlength=6 <?=value($fetch->jb_send_num)?>><font size=-1>��</font>
					<span class="note">��Ⱦ�ѿ�����</span></td>
				</tr>
				<tr>
					<td class="m1">ʬ��ȯ������</td>
					<td class="n1">
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
								<td width=47><input type="radio" name="bunkatsu" onclick="OnChange_job_type('', 0)" <?=value_checked2(0,$fetch->jb_bunkatsu_kaisuu)?>><font size=-1>�ʤ�</font></td>
								<td width=47><input type="radio" name="bunkatsu" onclick="OnChange_job_type('bunkatsu_set', 1)" <?=value_checked2(1,$fetch->jb_bunkatsu_kaisuu)?>><font size=-1>����</font></td>
								<td>
									<span ID="bunkatsu_set" style="display:<?=check_disp($fetch->jb_bunkatsu_kaisuu,0)?>;">
									<font size=-1 class="note">ʬ������</font><input type="text" class="number" size=4 maxlength=4 name="bunkatsu_kaisuu" <?=value($fetch->jb_bunkatsu_kaisuu)?>><font size=-1>��</font>
									<font size=-1 class="note">ʬ��ֳ֡�</font><input type="text" class="number" size=4 maxlength=4 name="bunkatsu_kankaku" <?=value($fetch->jb_bunkatsu_kankaku)?>><font size=-1>��</font>
									</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
<?
if ($fetch->jb_job_type) { //���󥱡��ȥ쥹�ݥ󥹡����ϥ���å��쥹�ݥ󥹤Τ�
?>
				<tr>
					<td class="m1">ȯ�������ߥ�</td>
					<td class="n1">
						<input type="radio" name="jb_send_timing" <?=value_checked('',$fetch->jb_send_timing)?>><font size=-1>�����塢¨����������</font><br>
						<input type="radio" name="jb_send_timing" <?=value_checked(1,$fetch->jb_send_timing)?>><font size=-1>�������֤��� </font><input type="text" class="number" name="jb_send_timing_h" size=3 maxlength=2 <?=value($fetch->jb_send_timing_h)?>><font size=-1> ���ָ�˸������� </font><br>
						<input type="radio" name="jb_send_timing" <?=value_checked(2,$fetch->jb_send_timing)?>><font size=-1>���������� </font><input type="text" class="number" name="jb_send_timing_d" size=3 maxlength=2 <?=value($fetch->jb_send_timing_d)?>><font size=-1> ����˸������� </font><br>
					</td>
				</tr>
<?
}
?>
			</table><br>
<?
if ($fetch->jb_parent == 't') {
?>
			<div align="center" class="small"><input type="checkbox" name="cpy_child">����ȯ�����ʳ�����Ͽ���Ƥ�ҥ���֤ذ��ǹ�������</div><br>
<?
}
?>
			<table width=700>
				<div align="center">
				<input type="hidden" name="job_id" <?=value($job_id)?>>
				<input type="hidden" name="jb_type" <?=value($fetch->jb_job_type)?>>
				<input type="hidden" name="next_action">
				<input type="submit" value="�嵭���Ƥ���Ͽ" onclick="document.form1.next_action.value='summary'">
				<input type="reset" value="�ꥻ�å�">
				<input type="button" value="����롡" onclick='history.back()'>
				</div>
			</table>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
