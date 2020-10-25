<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�᡼�륢�ɥ쥹�����꡼�˥󥰡������ӥ�
'******************************************************/

$title_text = $TT_mail_cleaning;
$title_color = $TC_OTHER;

$sql = "SELECT CD_job_name "
		. ", CD_client_name"
		. ", CD_subject"
		. ", CD_from"
		. ", CD_reply_to"
		. ", CD_body"
		. ", TO_CHAR(CD_start_date, 'YYYY') AS start_year"
		. ", TO_CHAR(CD_start_date, 'MM') AS start_month"
		. ", TO_CHAR(CD_start_date, 'DD') AS start_day"
		. ", TO_CHAR(CD_start_date, 'HH24') AS start_hour"
		. ", TO_CHAR(CD_end_date, 'YYYY') AS end_year"
		. ", TO_CHAR(CD_end_date, 'MM') AS end_month"
		. ", TO_CHAR(CD_end_date, 'DD') AS end_day"
		. ", TO_CHAR(CD_end_date, 'HH24') AS end_hour"
		. ", CD_status"
		. " FROM M_CLEANING_DATA WHERE CD_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$job_name = $fetch->cd_job_name;
	$client_name = $fetch->cd_client_name;
	$subject = $fetch->cd_subject;
	$from = $fetch->cd_from;
	$reply_to = $fetch->cd_reply_to;
	$body = $fetch->cd_body;
	$start_year = $fetch->start_year;
	$start_month = $fetch->start_month;
	$start_day = $fetch->start_day;
	$start_hour = $fetch->start_hour;
	$end_year = $fetch->end_year;
	$end_month = $fetch->end_month;
	$end_day = $fetch->end_day;
	$end_hour = $fetch->end_hour;
	$status = $fetch->cd_status;
}
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
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function OnSubmit_form1() {
	with (document.form1) {
    if (job_name.value == "") {
      alert("̾�Τ����Ϥ��Ƥ���������");
      job_name.focus();
      return false;
    }
    if (client_name.value == "") {
      alert("���饤����ȴ��̾�����Ϥ��Ƥ���������");
      client_name.focus();
      return false;
    }
    if (subject.value == "") {
      alert("��̾�����Ϥ��Ƥ���������");
      subject.focus();
      return false;
    }
    if (from.value == "") {
      alert("From���ɥ쥹�����Ϥ��Ƥ���������");
      from.focus();
      return false;
    }
		if (!isA(from.value)) {
			alert("From���ɥ쥹��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			from.focus();
			return false;
		}
		if (reply_to.value != "") {
			if (!isA(reply_to.value)) {
				alert("Reply_To���ɥ쥹��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
				reply_to.focus();
				return false;
			}
		}
		if (body.value == "") {
			alert("��ʸ�����Ϥ��Ƥ���������");
			body.focus();
			return false;
		}
		if (!check_date(start_year.value, start_month.value, start_day.value)) {
			alert("���դλ��꤬����������ޤ���");
			start_day.focus();
			return(false);
		}
    if (start_hour.value == "") {
      alert("���ϻ��֤����򤷤Ƥ���������");
      start_hour.focus();
      return false;
    }
		if (!check_date(end_year.value, end_month.value, end_day.value)) {
			alert("���դλ��꤬����������ޤ���");
			end_day.focus();
			return(false);
		}
    if (end_hour.value == "") {
      alert("��λ���֤����򤷤Ƥ���������");
      end_hour.focus();
      return false;
    }
  }
	return confirm("�������ޤ���������Ǥ�����");
}
function OnClick_sakujo() {
	if (confirm("������ޤ���������Ǥ�����")) {
		location.href = "cleaning5.php?seq_no=<?= $seq_no ?>";
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" ENCTYPE="multipart/form-data" action="cleaning4.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">���᡼�롦���꡼�˥󥰡������ӥ�����</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="750">
		<tr>
			<td class="m4"><nobr>SEQ</nobr></td>
			<td class="n6"><?= $seq_no ?></td>
		</tr>
		<tr>
			<td class="m4"><nobr>�᡼�륢�ɥ쥹�ꥹ�ȥե�����</nobr></td>
			<td class="n6"><input size=60 name="data_file" type="file" ></td>
		</tr>
		<tr>
			<td class="m4"><nobr>̾��</nobr></td>
			<td class="n6"><input size=60 name="job_name" maxlength=50 value="<?= htmlspecialchars($job_name) ?>"></td>
		</tr>
		<tr>
			<td class="m4"><nobr>���饤����ȴ��̾</nobr></td>
			<td class="n6"><input size=60 name="client_name" maxlength=50 value="<?= htmlspecialchars($client_name) ?>"></td>
		</tr>
		<tr>
			<td class="m4"><nobr>��̾</nobr></td>
			<td class="n6"><input size=60 name="subject" maxlength=100 value="<?= htmlspecialchars($subject) ?>"></td>
		</tr>
		<tr>
			<td class="m4"><nobr>From���ɥ쥹</nobr></td>
			<td class="n6"><input size=60 name="from" maxlength=50 value="<?= htmlspecialchars($from) ?>"><font class="note">��Ⱦ�ѱѿ���</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>Reply-To���ɥ쥹</nobr></td>
			<td class="n6"><input size=60 name="reply_to" maxlength=50 value="<?= htmlspecialchars($reply_to) ?>"><font class="note">��Ⱦ�ѱѿ���</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>��ʸ</nobr></td>
			<td class="n6"><textarea name="body" cols=72 rows=20><?= htmlspecialchars($body) ?></textarea></td>
		</tr>
		<tr>
			<td class="m4"><nobr>��������</nobr></td>
			<td class="n6">
				<select name="start_year"><? select_year(2000, '', date($start_year)); ?></select>ǯ
				<select name="start_month"><? select_month('', date($start_month)); ?></select>��
				<select name="start_day"><? select_day('', date($start_day)); ?></select>��
				<select name="start_hour"><? select_hour('', $start_hour); ?></select>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>��λ����</nobr></td>
			<td class="n6">
				<select name="end_year"><? select_year(2000, '', date($end_year)); ?></select>ǯ
				<select name="end_month"><? select_month('', date($end_month)); ?></select>��
				<select name="end_day"><? select_day('', date($end_day)); ?></select>��
				<select name="end_hour"><? select_hour('', $end_hour); ?></select>
			</td>
		</tr>
	</table>
	<br>
	<input type="hidden" name="seq_no" value="<?= $seq_no ?>" >
<? if ($status == '0') { ?>
	<input type="submit" value=" ���� ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value="���" onclick="OnClick_sakujo()">
<? } ?>
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
