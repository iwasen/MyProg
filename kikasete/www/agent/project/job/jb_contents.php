<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�����������Ͽ
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
include("$inc/decode.php");
include("$inc/search.php");
include("sub.php");

//ȯ�������ߥ�DECODE
function decode_send_timing($timing,$hour,$day) {
	switch($timing) {
	case 0:
		echo "�����塢¨����������";
		break;
	case 1:
		echo "�������֤��� $hour ���ָ�˸�������";
		break;
	case 2:
		echo "���������� $day ����˸�������";
		break;
	default:
		echo "����";
	}
}

//checkbox����
function check_disabled($set) {
	if (!$set) {
		echo " disabled";
	}
}
//ʬ��ȯ������DECODE
function decode_bunkatsu($kaisuu,$kankaku) {
	if (!$kaisuu)
		echo '�ʤ�';
	else
		echo "ʬ������$kaisuu �󡡡�ʬ��ֳ֡�$kankaku ��";
}
//�С�����᡼��̵ͭDECODE
function decode_virgin_mail($code,$day,$hour) {
	switch($code) {
	case 0:
		echo '�ʤ�';
		break;
	case 1:
		echo '������Ͽ�ԤΤ�<br>';
		echo "<font size=-1>�ʿ�������ؤ����������ߥ󥰡� ���������� $day ���� $hour ����</font>";
		break;
	case 2:
		echo '��¸��� + ������Ͽ��<br>';
		echo "<font size=-1>�ʿ�������ؤ����������ߥ󥰡� ���������� $day ���� $hour ����</font>";
		break;
	}
}
//�����ܥ���
function edit_button($check,$set,$status) {
	global $job_id;
	switch($check) {
	case 0:
		$url = "enq_summary";
		break;
	case 1:
		$url = "enq_contents";
		break;
	case 2:
		$url = "enq_condition";
		break;
	case 3:
		$url = "enq_mail";
		break;
	}
	if ((SERVICE == 'agent' && $status < 2) || (SERVICE == 'admin' && $status < 5)) {
		if ($set)
			echo "<input type='button' value='��������' onclick='location.href=\"$url.php?job_id=$job_id\"'>";
		else
			echo "<input type='button' value=' ̤���� ' onclick='location.href=\"$url.php?job_id=$job_id\"'>";
	}
}
//�����ܥ���
function set_button($status, $set_all) {
	switch($status) {
	case 0;
	case 1:
		if ($set_all)
			echo '<input type="submit" value="��������" onclick="document.form1.next_action.value=\'apply\'"> ';
		echo '<input type="submit" value="�������" onclick="document.form1.next_action.value=\'delete\'">';
		break;
	case 2:
		echo '<input type="submit" value="������ä�" onclick="document.form1.next_action.value=\'cancel_apply\'">';
		break;
	case 5:
		echo '<input type="submit" value="̤��ǧ���᤹" onclick="document.form1.next_action.value=\'remake\'"> ';
		echo '<input type="submit" value="������" onclick="document.form1.next_action.value=\'pause\'">';
		break;
	case 6:
		echo '<input type="submit" value="̤��ǧ���᤹" onclick="document.form1.next_action.value=\'unappr\'"> ';
		echo '<input type="submit" value="���Ƴ���" onclick="document.form1.next_action.value=\'restart\'">';
		break;
	case 7:
		echo '<input type="submit" value="̤��ǧ���᤹" onclick="document.form1.next_action.value=\'remake\'">';
		break;
	}
}
//�ᥤ�����

set_global2('project', '�ץ������ȴ���', '�����������Ͽ', BACK_TOP);

$sql = "SELECT jb_job_name,jb_job_type,jb_mail_format,jb_parent_job_id,jb_send_timing,jb_send_timing_h,jb_send_timing_d,jb_enquete_id,jb_search_id"
	. ",jb_enq_web_url,jb_mail_format,jb_category,pj_project_id,pj_project_name,en_enq_type,en_status"
	. " FROM t_job LEFT JOIN t_project ON jb_project_id=pj_project_id LEFT JOIN t_enquete ON jb_enquete_id=en_enquete_id"
	. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$en_status = $fetch->en_status;
	$en_enq_type = $fetch->en_enq_type;
	$en_enquete_id = $fetch->jb_enquete_id;
	$search_id = $fetch->jb_search_id;
	$enq_web_url = $fetch->jb_enq_web_url;
	$mail_format = $fetch->jb_mail_format;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onload() {
	var f = document.form1;
	if (document.all('edit1')) {
		show_hide('edit1', f.check1.checked);
	}
	show_hide('edit3', f.check3.checked);
	show_hide('edit4', f.check4.checked);
	if (document.all('edit5')) {
		show_hide('edit5', f.check5.checked);
	}
}
function show_hide(id, show) {
	if (document.all)
		ida = document.all(id);
	else if (document.getElementById)
		ida = document.getElementById(id);
	else
		return;
	for (i = 0; i < ida.length; i++) {
		ida[i].style.display = show ? "" : "none";
	}
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "apply":
		return confirm("����֤������ޤ���������Ǥ�����");
	case "cancel_apply":
		return confirm("����֤ο�������ä��ޤ���������Ǥ�����");
	case "delete":
<?
if ($fetch->jb_parent == 't') {
?>
		alert("���Υ���֤ˤϻҥ���֤�¸�ߤ��뤿�ᡢ����Ǥ��ޤ���");
<?
} else {
?>
		if (confirm("����֤������ޤ���������Ǥ�����"))
			return confirm("����֤Υǡ��������ƾõ��ޤ����ޤ���������Ǥ�����");
<?
}
?>
		break;
	case "pause":
		return confirm("����֤�����ߤ��ޤ���������Ǥ�����");
	case "restart":
		return confirm("����֤�Ƴ����ޤ���������Ǥ�����");
	case "remake":
		return confirm("����֤�̤��ǧ���֤��ᤷ�ƺ������ޤ���������Ǥ�����");
	case 'end_date':
		return confirm("���������򹹿����ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="onload()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="jb_check.php" onsubmit="return onSubmit_form1(this)">
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
					<td class="m0" colspan=2 align="left">���ץ������Ⱦ���</td>
				</tr>
				<tr>
					<td class="m1" width=140>�ץ�������ID</td>
					<td class="n1"><?=$fetch->pj_project_id?></td>
				</tr>
				<tr>
					<td class="m1">�ץ�������̾</td>
					<td class="n1"><?=htmlspecialchars($fetch->pj_project_name)?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m0" colspan=2 align="left">������־���</td>
				</tr>
				<tr>
					<td class="m1" width=140>�����ID</td>
					<td class="n1"><?=htmlspecialchars($job_id)?></td>
				</tr>
				<tr>
					<td class="m1" width=140>�����̾</td>
					<td class="n1"><?=htmlspecialchars($fetch->jb_job_name)?></td>
				</tr>
				<tr>
					<td class="m1">�ۿ����ƥ���</td>
					<td class="n1"><?=decode_job_category($fetch->jb_category)?></td>
				</tr>
				<tr>
					<td class="m1">�᡼�����</td>
					<td class="n1"><?=decode_mail_format($fetch->jb_mail_format)?></td>
				</tr>
				<tr>
					<td class="m1">���󥱡��ȼ���</td>
					<td class="n1"><?=decode_enq_type($en_enq_type)?></td>
				</tr>
				<tr>
					<td class="m1">����ּ���</td>
					<td class="n1"><?=decode_job_type($fetch->jb_job_type)?></td>
				</tr>
<?
if ($fetch->jb_parent_job_id) {
?>
				<tr>
					<td class="m1">��JOB</td>
					<td class="n1"><?=htmlspecialchars(parent_job_name_decode($fetch->jb_parent_job_id))?></td>
				</tr>
<?
}
?>
				<tr>
					<td class="m1">����</td>
					<td class="n1"><?=decode_enq_status($en_status)?></td>
				</tr>
			</table>
			<table border=0 cellspacing=1 cellpadding=1 width=700>
				<tr>
					<td class="m0" colspan=2 align="left">���ܺپ���</td>
				</tr>
			</table>
<?
$set_all = true;
if ($en_enq_type == 1 || $en_enq_type == 2) {
	$sql = "SELECT en_title,en_start_date,en_end_date,en_point,jb_job_type,jb_send_num,jb_bunkatsu_kaisuu,jb_bunkatsu_kankaku,jb_send_timing,jb_send_timing_h,jb_send_timing_d"
		. " FROM t_enquete LEFT JOIN t_job ON en_enquete_id=jb_enquete_id WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$set = ($fetch->en_start_date != '');
	}
	$set_all = $set;
?>
			<table border=0 cellspacing=1 cellpadding=1 width=700>
				<tr>
					<td class="m1" colspan=2>
						<table border=0 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td><input type="checkbox" name="check1" onclick="show_hide('edit1', checked)" <?=check_disabled($set)?>>���󥱡��ȼ»ܳ���</td>
								<td align="right"><?=edit_button(0,$set,$en_status)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td id="edit1">
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr id="edit1">
								<td class="n1" width=150>���󥱡��ȥ����ȥ�</td>
								<td><?=htmlspecialchars($fetch->en_title)?></td>
							</tr>
							<tr id="edit1">
								<input type="hidden" name="en_start_date" <?=value($fetch->en_start_date)?>>
								<td class="n1" width=150>���󥱡��ȳ�������</td>
								<td>
									<?=get_datepart('Y',$fetch->en_start_date)?>ǯ
									<?=get_datepart('M',$fetch->en_start_date)?>��
									<?=get_datepart('D',$fetch->en_start_date)?>��
									<?=get_datepart('h',$fetch->en_start_date)?>��
								</td>
							</tr>
							<tr>
								<td class="n1">���󥱡��Ƚ�λ����</td>
								<td>
									<?=get_datepart('Y',$fetch->en_end_date)?>ǯ
									<?=get_datepart('M',$fetch->en_end_date)?>��
									<?=get_datepart('D',$fetch->en_end_date)?>��
									<?=get_datepart('h',$fetch->en_end_date)?>��
								</td>
							</tr>
							<tr>
								<td class="n1">���󥱡��ȥݥ���ȿ�</td>
								<td><?=$fetch->en_point?>�ݥ����</td>
							</tr>
							<tr>
								<td class="n1">����ȯ����</td>
								<td><?=number_format($fetch->jb_send_num)?>��</td>
							</tr>
							<tr>
								<td class="n1">ʬ��ȯ������</td>
								<td><?=decode_bunkatsu($fetch->jb_bunkatsu_kaisuu,$fetch->jb_bunkatsu_kankaku)?></td>
							</tr>
<?
if ($fetch->jb_job_type != 0) {
?>
							<tr>
								<td class="n1">ȯ�������ߥ�</td>
								<td><?=decode_send_timing($fetch->jb_send_timing,$fetch->jb_send_timing_h,$fetch->jb_send_timing_d)?></td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
			</table>
<?
}
if ($search_id) {
	$search = new search_class;
	$search->read_db($search_id);
	$condition = $search->get_condition();
}
$sql = "SELECT jb_job_type,jb_teikei_qno,jb_teikei_andor,jb_teikei_ans,jb_send_num,jb_virgin_mail,jb_virgin_day,jb_virgin_hour,jb_ct_id FROM t_job WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
}
$set = ($search_id != '');
$set_all = ($set_all && $set);
?>
			<table border=0 cellspacing=1 cellpadding=1 width=700>
				<tr>
					<td class="m1" colspan=2>
						<table border=0 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td><input type="checkbox" name="check3" onclick="show_hide('edit3', checked)" <?=check_disabled($set)?>>�оݾ��</td>
								<td align="right"><?=edit_button(2,$set,$en_status)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td id="edit3">
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr id="edit3">
								<td class="n1">����ȯ����</td>
								<td><?=number_format($fetch->jb_send_num)?>��</td>
							</tr>
							<tr>
								<td class="n1">�С�����᡼��</td>
								<td><?=decode_virgin_mail($fetch->jb_virgin_mail,$fetch->jb_virgin_day,$fetch->jb_virgin_hour)?></td>
							</tr>
<?
if ($fetch->jb_job_type == 1) { //���󥱡��ȥ쥹�ݥ󥹤Τ�
?>
							<tr>
								<td class="n1" width=150>�귿����</td>
								<td>
									<table border=0 cellspacing=2 cellpadding=0>
<?
	$sql = "SELECT tk_teikei_qno,tk_teikei_andor,tk_teikei_ans FROM t_teikei WHERE tk_job_id=$job_id ORDER BY tk_teikei_qno";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
										<tr>
											<td><font size="-1">�����ֹ桧<?=$fetch->tk_teikei_qno?></font></td>
											<td><font size="-1">����<?=decode_andor($fetch->tk_teikei_andor)?></font></td>
											<td><font size="-1">���оݡ�<?=$fetch->tk_teikei_ans?></font></td>
										</tr>
<?
	}
?>
									</table>
								</td>
							</tr>
<?
}
if ($fetch->jb_job_type == 2) { //����å��쥹�ݥ󥹤Τ�
?>
							<tr>
								<td class="n1" width=150>����å�������</td>
								<td><font size="-1"><?=$fetch->jb_ct_id?></font></td>
							</tr>
<?
}
?>
							<tr>
								<td class="n1" width=150>�оݾ��</td>
								<td><font size="-1"><?=str_replace("\n", '<br>', $condition)?></font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
<?
$sql = "SELECT * FROM t_job WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$set = ($fetch->jb_start_date != '');
}
$set_all = ($set_all && $set);
?>
			<table border=0 cellspacing=1 cellpadding=1 width=700>
				<tr>
					<td class="m1" colspan=2>
						<table border=0 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td><input type="checkbox" name="check4" onclick="show_hide('edit4', checked)" <?=check_disabled($set)?>>�᡼��ʸ</td>
								<td align="right"><?=edit_button(3,$set,$en_status)?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td id="edit4">
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr id="edit4">
								<td class="n1" width=150>�ۿ���������</td>
								<input type="hidden" name="jb_start_date" <?=value($fetch->jb_start_date)?>>
								<td>
									<?=get_datepart('Y',$fetch->jb_start_date)?>ǯ
									<?=get_datepart('M',$fetch->jb_start_date)?>��
									<?=get_datepart('D',$fetch->jb_start_date)?>��
									<?=get_datepart('h',$fetch->jb_start_date)?>��
								</td>
							</tr>
							<tr>
								<td class="n1">�ۿ���λ����</td>
								<td>
									<?=get_datepart('Y',$fetch->jb_end_date)?>ǯ
									<?=get_datepart('M',$fetch->jb_end_date)?>��
									<?=get_datepart('D',$fetch->jb_end_date)?>��
									<?=get_datepart('h',$fetch->jb_end_date)?>��
								</td>
							</tr>
							<tr>
								<td class="n1">�����ݥ����</td>
								<td><?=$fetch->jb_point_jushin?><font size=-1>�ݥ����</font></td>
							</tr>
						</table>
						<table class="small" border=1 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td class="n1" width=150>�����ȥ�</td>
								<td><?=htmlspecialchars(trim($fetch->jb_subject))?></td>
							</tr>
<?
if ($mail_format == 0) {
?>
							<tr>
								<td class="n1">�إå�</td>
								<td><pre><?=htmlspecialchars($fetch->jb_header)?></pre></td>
							</tr>
							<tr>
								<td class="n1">��ʸ</td>
								<td><pre><?=htmlspecialchars($fetch->jb_body)?></pre></td>
							</tr>
							<tr>
								<td class="n1">�եå�</td>
								<td><pre><?=htmlspecialchars($fetch->jb_footer)?></pre></td>
							</tr>
<?
} else {
?>
							<tr>
								<td class="n1">HTML������</td>
								<td><pre><?=htmlspecialchars($fetch->jb_body)?></pre></td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
			</table>
<?
if ($en_enq_type == 0) {
	$sql = "SELECT en_end_date"
		. " FROM t_enquete LEFT JOIN t_job ON en_enquete_id=jb_enquete_id WHERE jb_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$set = ($fetch->en_end_date != '');
	}
?>
			<table border=0 cellspacing=1 cellpadding=1 width=700>
				<tr>
					<td class="m1" colspan=2>
						<table border=0 cellspacing=0 cellpadding=3 width=100%>
							<tr>
								<td><input type="checkbox" name="check5" onclick="show_hide('edit5', checked)" <?=check_disabled($set)?>>��������</td>
								<td align="right" id="edit5">
									<select name="en_end_year"><? select_year(date(Y),'',get_datepart('Y', $fetch->en_end_date))?></select>ǯ
									<select name="en_end_month"><? select_month(' ',get_datepart('M', $fetch->en_end_date))?></select>��
									<select name="en_end_day"><? select_day(' ',get_datepart('D', $fetch->en_end_date))?></select>��
									<select name="en_end_hour"><? select_hour(' ',get_datepart('h', $fetch->en_end_date))?></select>��
								</td>
								<td align="right" width="100" id="edit5"><input type="submit" value="��������" onclick="document.form1.next_action.value='end_date'"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
<?
}
?>
			<br>
			<div align="center">
				<input type="hidden" name="pj_no" <?=value($pj_no)?>>
				<input type="hidden" name="job_id" <?=value($job_id)?>>
				<input type="hidden" name="en_id" <?=value($en_enquete_id)?>>
				<input type="hidden" name="next_action">
<?
set_button($en_status, $set_all);
?>
				<input type="button" value="����롡" onclick='location.href="list.php"'>
			</div>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
