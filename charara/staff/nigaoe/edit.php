<?
/******************************************************
' System :�֤�����Factory�׺�襹���å��ѥڡ���
' Content:���鳨�����߼̿�ɽ��
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/status.php");
include("inc.php");

// �������᡼��ɽ��
function disp_mail($nigaoe_id) {
	$sql = "SELECT lm_send_recv,lm_date,lm_from_addr,lm_to_addr,lm_subject,lm_body FROM l_mail WHERE lm_nigaoe_id=$nigaoe_id ORDER BY lm_mail_log_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i != 0)
			echo "<hr>";

		echo $fetch->lm_send_recv == 'R' ? "<font color='blue'>�ڼ�����</font>\n" : "<font color='red'>��������</font>\n";
		echo 'Subject: ', htmlspecialchars($fetch->lm_subject), "\n";
		echo 'From: ', htmlspecialchars($fetch->lm_from_addr), "\n";
		echo 'To: ', htmlspecialchars($fetch->lm_to_addr), "\n";
		echo 'Date: ', format_datetime($fetch->lm_date), "\n";
		echo "\n";
		echo htmlspecialchars($fetch->lm_body), "\n";
	}
}

set_global('charara', '', '������Factory ���鳨�����߼̿�����', BACK_TOP);

// �̿�����URL����
$url_photo = get_system_info('url_photo');

// ���鳨����URL����
$url_nigaoe = get_system_info('url_nigaoe');

$sql = "SELECT nd_nigaoe_id,nd_random_id,nd_status,nd_completion_date,nd_nouki_date,nd_sex_code,nd_age,nd_photo_file,nd_nigaoe_file"
		. " FROM t_nigaoe_data"
		. " WHERE nd_nigaoe_id=$nigaoe_id";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "make":
		return confirm("���μ̿��λ��鳨��������ޤ�����");
	case "upload":
		if (f.nigaoe_file.value == "") {
			alert("���鳨�ե���������Ϥ��Ƥ���������");
			f.nigaoe_file.focus();
			return false;
		}
		return confirm("���鳨�ե�����򥢥åץ��ɤ��ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body>

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�����鳨��������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��̿�</td>
		<td class="n1">
			<a href="<?=$url_photo?><?=$fetch->nd_photo_file?>" target="_blank"><img class="photo" src="<?=$url_photo?><?=$fetch->nd_photo_file?>" alt="��̿�"></a>
		</td>
	</tr>
<?
if ($fetch->nd_nigaoe_file) {
?>
	<tr>
		<td class="m1" width="20%">���鳨����</td>
		<td class="n1">
			<img class="nigaoe" src="<?=$url_nigaoe?><?=$fetch->nd_nigaoe_file?>" alt="���鳨����">
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=disp_sex($fetch->nd_sex_code)?></td>
	</tr>
	<tr>
		<td class="m1">ǯ��</td>
		<td class="n1"><?=disp_age($fetch->nd_age)?></td>
	</tr>
	<tr>
		<td class="m1">���ơ�����</td>
		<td class="n1"><?=disp_status($fetch->nd_status)?></td>
	</tr>
	<tr>
		<td class="m1">�������᡼��</td>
		<td class="n1"><pre><? disp_mail($nigaoe_id)?></pre></td>
	</tr>
	<tr>
		<td class="m1">���鳨����Ǽ��</td>
		<td class="n1"><?=format_datetime($fetch->nd_nouki_date)?></td>
	</tr>
<?
if ($fetch->nd_completion_date) {
?>
	<tr>
		<td class="m1">���鳨��������</td>
		<td class="n1"><?=format_datetime($fetch->nd_completion_date)?></td>
	</tr>
<?
}
?>
<?
if ($fetch->nd_status == STATUS_MAKING) {
?>
	<tr>
		<td class="m1">���鳨���åץ��ɥե�����</td>
		<td class="n1">
			<input type="file" name="nigaoe_file" size=70>
			<br><span class="note">�ʺ����������鳨�����ե�����̾�����Ϥ��Ƥ���������</span>
		</td>
	</tr>
<?
}
?>
</table>
<br>
<?
switch ($fetch->nd_status) {
case STATUS_PHOTO_RECEIVED:
case STATUS_STAFF_ASSIGNED:
?>
<input type="button" value="�᡼����ֿ�����" onclick="location.href='mail.php?nigaoe_id=<?=$nigaoe_id?>'">
<input type="submit" value="���μ̿��λ��鳨���������" onclick="document.form1.next_action.value='make'">
<?
	break;
case STATUS_MAKING:
?>
<input type="submit" value="���鳨�򥢥åץ��ɤ���" onclick="document.form1.next_action.value='upload'">
<?
	break;
}
?>
<input type="button" value="����롡" onclick="location.href='list.php'">
<input type="hidden" name="next_action">
<input type="hidden" name="nigaoe_id" <?=value($nigaoe_id)?>>

</div>
</form>

<? page_footer() ?>

</body>
</html>
