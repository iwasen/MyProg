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

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	with (document.form1) {
  }
	return confirm("�¹Ԥ��ޤ���������Ǥ�����");
}
//-->
</SCRIPT>

</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form style="margin:0" method="post" name="form1" action="cleaning_detail2.php" name="form1" onSubmit="return OnSubmit_form1();">
<table border=0 cellspacing=0 cellpadding=0 width='70%'>
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=0 width='100%'>
				<tr>
					<td>�����顼���ɥ쥹����</td>
					<td align="right">���������<span id="rec_count"></span>�� </td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=2 width="100%">
<?php
$sql = "SELECT CD_job_name, CD_client_name, CD_status FROM M_CLEANING_DATA WHERE CD_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$job_name = $fetch->cd_job_name;
	$client_name = $fetch->cd_client_name;
	$cd_status = $fetch->cd_status;
?>

				<tr>
					<td class="h8" width="10%">̾��</td>
					<td width="40%"><?= $job_name ?></td>
					<td class="h8" width="10%"><nobr>���̾</nobr></td>
					<td width="35%"><?= $client_name ?></td>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>
<br>
<table border=1 cellspacing=0 cellpadding=2 width='70%'>
	<tr>
		<td class="h1" width="70%" align="center"><nobr>�᡼�륢�ɥ쥹</nobr></td>
		<td class="h1" width="10%" align="center"><nobr>���</nobr></td>
	</tr>
<?
$sql = "SELECT * FROM T_CLEANING_LIST WHERE CL_status='$status' AND CL_seq_no=$seq_no ORDER BY CL_sub_seq";
$result = db_exec($sql);
$nrow = pg_numrows($result);

$line=0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$sub_seq = $fetch->cl_sub_seq;
	$mail_adr = $fetch->cl_mail_adr;
?>
	<tr class="tc<?= $line % 2; ?>">
		<input type="hidden" name="edit_sub_seq[]" value="<?= $sub_seq ?>">
		<td><input type="text" name="edit_mail_adr[]" size="100" maxlength="100" value="<?= $mail_adr ?>"></td>
		<td align="center"><input type="checkbox" name="edit_flag[]" value="<?= $sub_seq ?>"></td>
	</tr>
<?
$line++;
}
?>
</table>
<br>
<input type="hidden" name="seq_no" value="<?= $seq_no ?>">
<? if ($cd_status == '0') { ?>
<input type="submit" value=" �¹� ">
<input type="reset" value="�ꥻ�å�">
<? } ?>
<input type="button" value=" ��� " onclick="location.href='index.php'">
</form>
</center>

<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow ?>;
//-->
</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
