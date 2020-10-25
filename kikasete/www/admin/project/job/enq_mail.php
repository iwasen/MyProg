<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�᡼��ʸ��Ͽ
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
include("$inc/enquete.php");
include("$inc/enq_mail.php");

//subjectɽ��
function check_subject($jb_subject, $en_title) {
	if (!$jb_subject) {
		return "�� ^ ^ ) $en_title";
	} else {
		return $jb_subject;
	}
}

//��������
function get_week($date) {
	$week = array('��', '��', '��', '��', '��', '��', '��');
	return $week[date('w', $date)];
}

//�ᥤ�����

set_global2('project', '�ץ������ȴ���', '�᡼��ʸ��Ͽ', BACK_TOP);

$sql = "SELECT jb_job_type,jb_subject,jb_header,jb_body,jb_footer,jb_start_date,jb_end_date,en_end_date,jb_point_jushin,jb_mail_format,jb_search_id,jb_category"
	. ",en_enquete_id,en_enq_type,en_title,en_description,en_start_date,en_end_date,en_point,EXISTS (SELECT * FROM t_job AS jb2 WHERE jb1.jb_job_id=jb2.jb_cp_job_id) AS jb_parent"
	. " FROM t_job AS jb1 LEFT JOIN t_enquete ON en_enquete_id=jb_enquete_id WHERE jb_job_id=$job_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

//�ۿ����������
$start_date_y = get_datepart('Y', $fetch->en_start_date);
$start_date_m = get_datepart('M', $fetch->en_start_date);
$start_date_d = get_datepart('D', $fetch->en_start_date);
$start_date_h = get_datepart('h', $fetch->en_start_date);
if (!$fetch->jb_start_date && $fetch->en_start_date) {
		$fetch->jb_start_date = $fetch->en_start_date;
	if ($fetch->jb_job_type == 0) {
		$fetch->jb_end_date = gmdate ('Y-m-d H',mktime ($start_date_h + 9, 0, 0, $start_date_m, $start_date_d + 1, $start_date_y));
	} else
		$fetch->jb_end_date = $fetch->en_end_date;
}


switch ($fetch->en_enq_type) {
case 1:		// mail
	switch ($fetch->jb_category) {
	case 1:		// ��������
		$template_id = 'jbe_mail';
		break;
	case 2:		// �Ϥ��᡼��
		$template_id = 'hai_mail';
		break;
	}
	break;
case 2:		// web
	switch ($fetch->jb_category) {
	case 1:		// ��������
		$template_id = 'jbe_web';
		break;
	case 2:		// �Ϥ��᡼��
		$template_id = 'hai_mail';
		break;
	}
	break;
default:
	if ($fetch->jb_category == 2)
		$template_id = 'hai_mail';
	else
		$template_id = 'null';
	break;
}

if ($fetch->en_enquete_id) {
	$sql = "SELECT et_header,et_body,et_footer FROM m_enq_template WHERE et_template_id='$template_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch2 = pg_fetch_object($result, 0);
		$et_header = $fetch2->et_header;
		$et_body = $fetch2->et_body;
		$et_footer = $fetch2->et_footer;
	}

	$end_date_y = get_datepart('Y', $fetch->en_end_date);
	$end_date_m = get_datepart('M', $fetch->en_end_date);
	$end_date_d = get_datepart('D', $fetch->en_end_date);
	$end_date_h = get_datepart('h', $fetch->en_end_date);
	if ($end_date_h < 12)
		$end_date_h = "AM$end_date_h";

	if ($fetch->jb_header == '')
		$fetch->jb_header = $et_header;

	$header = &$fetch->jb_header;
	if ($fetch->en_title != '')
		$header = str_replace('%TITLE%', $fetch->en_title, $header);
	if ($fetch->en_end_date != '')
		$header = str_replace('%LIMIT_DATE%', "{$end_date_y}ǯ{$end_date_m}��{$end_date_d}��{$end_date_h}��", $header);
	if ($fetch->en_point != '')
		$header = str_replace('%POINT%', $fetch->en_point, $header);

	if ($fetch->jb_body == '')
		$fetch->jb_body = $et_body;

	$body = &$fetch->jb_body;
	$time = sql_time($fetch->en_end_date);

	$fetch2 = get_system_info('sy_url_monitor');
	$url = "{$fetch2->sy_url_monitor}m_remlenq3.php?id=%ENQUETE_PARAM%";

	if ($fetch->en_title != '')
		$body = str_replace('%TITLE%', $fetch->en_title, $body);
	if ($fetch->en_end_date != '')
		$body = str_replace('%LIMIT_DATE%', mb_convert_kana("{$end_date_m}��{$end_date_d}����" . get_week($time) . "��{$end_date_h}��", 'N'), $body);
	if ($fetch->en_point != '')
		$body = str_replace('%POINT%', mb_convert_kana($fetch->en_point, 'N'), $body);
	$body = str_replace('%ENQUETE_URL%', $url, $body);

	if ($fetch->jb_footer == '') {
		$footer = $et_footer;
		$fetch->jb_footer = $footer;
	}
}

if ($fetch->jb_search_id) {
	$sql = "SELECT sr_html_mail FROM t_search WHERE sr_search_id={$fetch->jb_search_id}";
	$html_mail = db_fetch1($sql);
}

$enable_html = $fetch->en_enq_type == 0 && $html_mail == DBTRUE;

$fetch2 = get_system_info(sy_url_top);
$top_url = $fetch2->sy_url_top;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onclick_contents() {
	var f = document.form1;
	f.target = "_blank";
	f.action = "mail_contents.php";
	f.submit();
}
function onclick_counter() {
	var f = document.form1;
	f.target = "_blank";
	f.action = "enq_mail_click.php";
	f.submit();
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
function onclick_save() {
	var f = document.form1;
	var now = new Date();
	if (!check_date(f.jb_start_year.value, f.jb_start_month.value, f.jb_start_day.value)) {
		alert("�ۿ����������λ��꤬����������ޤ���");
		f.jb_start_day.focus();
		return(false);
	}
	var start_date = new Date(f.jb_start_year.value, f.jb_start_month.value - 1, f.jb_start_day.value, f.jb_start_hour.value);
	if (start_date < now) {
		if (!confirm("�ۿ����������˲������դ����Ϥ���Ƥ��ޤ�����������Ǥ�����")) {
			f.jb_start_year.focus();
			return false;
		}
	}
	if (!check_date(f.jb_end_year.value, f.jb_end_month.value, f.jb_end_day.value)) {
		alert("�ۿ���λ�����λ��꤬����������ޤ���");
		f.jb_end_day.focus();
		return(false);
	}
	var end_date = new Date(f.jb_end_year.value, f.jb_end_month.value - 1, f.jb_end_day.value, f.jb_end_hour.value);
	if (start_date > end_date) {
		alert("�ۿ���λ�������ۿ�����������������դ����Ϥ��Ƥ���������");
		f.jb_end_year.focus();
		return false;
	}
	if (f.point_jushin.value == ""){
		alert("�����ݥ���Ȥ����Ϥ��Ƥ���������");
		f.point_jushin.focus();
		return(false);
	}
	if (!num_chk(f.point_jushin.value)){
		alert("�����ݥ���Ȥ�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		f.point_jushin.focus();
		return(false);
	}
	if (f.mail_format[0].checked && f.jb_subject.value == ""){
		alert("��̾�����Ϥ��Ƥ���������");
		f.jb_subject.focus();
		return(false);
	}
	if (f.mail_format[1].checked && f.html_subject.value == ""){
		alert("��̾�����Ϥ��Ƥ���������");
		f.html_subject.focus();
		return(false);
	}
	if (f.mail_format[0].checked && f.jb_body.value == ""){
		alert("��ʸ�����Ϥ��Ƥ���������");
		f.jb_body.focus();
		return(false);
	}
	if (f.mail_format[1].checked && f.html_body.value == ""){
		alert("HTML�����������Ϥ��Ƥ���������");
		f.html_body.focus();
		return(false);
	}
	if (confirm("�᡼��ʸ����Ͽ���ޤ���������Ǥ�����")) {
		f.target = "";
		f.next_action.value='mail';
		f.action = "enq_save.php";
		f.submit();
	}
}
function onclick_enq_check() {
	var f = document.form1;
	f.target = "enq_check";
	f.action = "enq_check.php";
	f.submit();
}
function onclick_mail_format() {
	var f = document.form1;
<?
if ($fetch->jb_category == 2) {
?>
	if (f.mail_format[1].checked) {
		f.target = "";
		f.next_action.value='hai_html';
		f.action = "enq_save.php";
		f.submit();
		return;
	}
<?
}
?>
	set_text_html();
}
function set_text_html() {
	var f = document.form1;
	document.getElementById("id_text_mail").style.display = f.mail_format[0].checked ? "" : "none";
	document.getElementById("id_text_button").style.display = f.mail_format[0].checked ? "" : "none";
	document.getElementById("id_html_mail").style.display = f.mail_format[1].checked ? "" : "none";
	document.getElementById("id_html_button").style.display = f.mail_format[1].checked ? "" : "none";
}
function onclick_css() {
	var f = document.form1;
	f.target = "";
	f.next_action.value='css';
	f.action = "enq_save.php";
	f.submit();
}
function onclick_image() {
	var f = document.form1;
	f.target = "";
	f.next_action.value='image';
	f.action = "enq_save.php";
	f.submit();
}
function onclick_preview() {
	var f = document.form1;
	f.target = "preview";
	f.action = "preview_html.php";
	f.submit();
}
function onclick_test_mail() {
	if (confirm("�ƥ��ȥ᡼����������ޤ���������Ǥ�����")) {
		var f = document.form1;
		f.target = "";
		f.next_action.value='test_mail';
		f.action = "enq_save.php";
		f.submit();
	}
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="set_text_html()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=800>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=800>
				<tr>
					<td class="m0" colspan=2 align="left">���᡼�����Ƥ����Ϥ��Ʋ�������</td>
				</tr>
				<tr>
					<td class="m1">�ۿ���������</td>
					<td class="n1">
						<select name="jb_start_year"><? select_year(date(Y),'',get_datepart('Y', $fetch->jb_start_date))?></select>ǯ
						<select name="jb_start_month"><? select_month(' ',get_datepart('M', $fetch->jb_start_date))?></select>��
						<select name="jb_start_day"><? select_day(' ',get_datepart('D', $fetch->jb_start_date))?></select>��
						<select name="jb_start_hour"><? select_hour(' ',get_datepart('h', $fetch->jb_start_date))?></select>��
					</td>
				</tr>
				<tr>
					<td class="m1">�ۿ���λ����</td>
					<td class="n1">
						<select name="jb_end_year"><? select_year(date(Y),'',get_datepart('Y', $fetch->jb_end_date))?></select>ǯ
						<select name="jb_end_month"><? select_month(' ',get_datepart('M', $fetch->jb_end_date))?></select>��
						<select name="jb_end_day"><? select_day(' ',get_datepart('D', $fetch->jb_end_date))?></select>��
						<select name="jb_end_hour"><? select_hour(' ',get_datepart('h', $fetch->jb_end_date))?></select>��
					</td>
				</tr>
				<tr>
					<td class="m1" width=140>�����ݥ����</td>
					<td class="n1"><input class="number" type="text" name="point_jushin" size=6 maxlength=4 <?=value($fetch->jb_point_jushin)?>><font size=-1>�ݥ����</font>
					<span class="note">��Ⱦ�ѿ�����</span></td>
				</tr>
				<tr>
					<td class="m1" width=140>�ۿ�����</td>
					<td class="n1">
						<input type="radio" name="mail_format" <?=value_checked(0, $fetch->jb_mail_format)?> onclick="onclick_mail_format()">�ƥ����ȥ᡼��
						<input type="radio" name="mail_format" <?=value_checked(1, $fetch->jb_mail_format)?> onclick="onclick_mail_format()"<?=disabled(!$enable_html)?>><font color="<?=$enable_html ? '#000000' : '#999999'?>">HTML�᡼��</font>
					</td>
				</tr>
			</table><br>
			<table border=0 cellspacing=2 cellpadding=3 width=800 id="id_text_mail">
				<tr>
					<td class="m1">��̾</td>
					<td class="n1"><input class="kanji" type="text" name="jb_subject" size=80 maxlength=100 <?=value(check_subject($fetch->jb_subject,$fetch->en_title))?>><span class="note">�����ѣ�����ʸ���ޤǡ�</span></td>
				</tr>
				<tr>
					<td class="m1">�إå�</td>
					<td class="n1"><textarea name="jb_header" cols=72 rows=10><?=$fetch->jb_header?></textarea></td>
				</tr>
				<tr>
					<td class="m1" width=140>��ʸ</td>
					<td class="n1">
						<span class="note">��������ʸ�����Ȥ��ޤ����ʥ�˥�����̾= %MONITOR_NAME% ��</span>
					<textarea name="jb_body" cols=72 rows=25><?=$fetch->jb_body?></textarea></td>
				</tr>
				<tr>
					<td class="m1">�եå�</td>
					<td class="n1"><textarea name="jb_footer" cols=72 rows=8><?=$fetch->jb_footer?></textarea></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=800 id="id_html_mail">
				<tr>
					<td class="m1">��̾</td>
					<td class="n1"><input class="kanji" type="text" name="html_subject" size=80 maxlength=100 <?=value(check_subject($fetch->jb_subject,$fetch->en_title))?>><span class="note">�����ѣ�����ʸ���ޤǡ�</span></td>
				</tr>
				<tr>
					<td class="m1">CSS�λ���</td>
					<td class="n1">
						<table border=0 cellspacing=2 cellpadding=3 width="100%">
							<tr>
								<td bgcolor="#cccccc" width="30%">�ե�����̾</td>
								<td bgcolor="#cccccc" width="65%">css�ե������URL</td>
								<td width="10%"><input type="button" value="����" onclick="onclick_css()"></td>
							</tr>
<?
if ($fetch->jb_category == 2)
	$sql = "SELECT htc_css_id,htc_file_name FROM t_html_css WHERE htc_job_id IN (0,$job_id) ORDER BY htc_css_id";
else
	$sql = "SELECT htc_css_id,htc_file_name FROM t_html_css WHERE htc_job_id=$job_id ORDER BY htc_css_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch2 = pg_fetch_object($result, $i);
?>
							<tr>
								<td bgcolor="#ffcc00"><?=htmlspecialchars($fetch2->htc_file_name)?></td>
								<td bgcolor="#ffcc00"><?=htmlspecialchars("{$top_url}css.php?id={$fetch2->htc_css_id}")?></td>
								<td></tr>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m1">�����λ���</td>
					<td class="n1">
						<table border=0 cellspacing=2 cellpadding=3 width="100%">
							<tr>
								<td bgcolor="#cccccc" width="30%">�ե�����̾</td>
								<td bgcolor="#cccccc" width="65%">������URL</td>
								<td width="10%"><input type="button" value="����" onclick="onclick_image()"></td>
							</tr>
<?
if ($fetch->jb_category == 2)
	$sql = "SELECT hti_image_id,hti_file_name FROM t_html_image WHERE hti_job_id IN (0,$job_id) ORDER BY hti_image_id";
else
	$sql = "SELECT hti_image_id,hti_file_name FROM t_html_image WHERE hti_job_id=$job_id ORDER BY hti_image_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch2 = pg_fetch_object($result, $i);
?>
							<tr>
								<td bgcolor="#ffcc00"><?=htmlspecialchars($fetch2->hti_file_name)?></td>
								<td bgcolor="#ffcc00"><?=htmlspecialchars("{$top_url}img.php?id={$fetch2->hti_image_id}")?></td>
								<td></tr>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m1" width=140>HTML������</td>
					<td class="n1">
					<textarea name="html_body" cols=80 rows=30><?=$fetch->jb_body?></textarea></td>
				</tr>
			</table>
			<br>
<?
if ($fetch->jb_parent == 't') {
?>
			<div align="center" class="small"><input type="checkbox" name="cpy_child">����ȯ�����ʳ�����Ͽ���Ƥ�ҥ���֤ذ��ǹ�������</div><br>
<?
}
?>
			<div align="center">
				<input type="hidden" name="job_id" <?=value($job_id)?>>
				<input type="hidden" name="en_id" <?=value($fetch->en_enquete_id)?>>
				<input type="hidden" name="enq_type" <?=value($fetch->en_enq_type)?>>
				<input type="hidden" name="next_action">
<span id="id_text_button">
				<input type="button" value="�嵭���Ƥ���Ͽ" onclick="onclick_save()">
<?
if ($fetch->en_enq_type == 1) {
?>
				<input type="button" value="���ݎ����Ď�������" onclick="onclick_enq_check()">
<?
}
?>
				<input type="button" value="���؎��������ݎ�����"onclick="onclick_counter()">
				<input type="button" value="ȯ�����ݎÎݎ�ɽ��" onclick="onclick_contents()">
				<input type="reset" value="�ꥻ�å�">
</span>
<span id="id_html_button">
				<input type="button" value="���؎������ݎ�����"onclick="onclick_counter()">
				<input type="button" value="�ץ�ӥ塼" onclick="onclick_preview()">
				<input type="button" value="�ƥ��ȥ᡼������" onclick="onclick_test_mail()">
				<input type="button" value="����Ͽ��" onclick="onclick_save()">
</span>
				<input type="button" value="����롡" onclick="location.href='jb_contents.php?job_id=<?=$job_id?>'">
			</div>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
