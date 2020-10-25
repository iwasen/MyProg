<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����Ź�᡼�����ɽ������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/format.php");

// ��������ɽ��
function decode_status($status) {
	switch ($status) {
	case 1:
		return '̤����';
	case 2:
		return '������';
	case 3:
		return '������';
	case 4:
		return '������';
	}
	return "����";
}

// ����Ź�᡼��ID����
$dealer_mail_id = $_GET['dealer_mail_id'];

// ����Ź�᡼��������
$sql = "SELECT dml_status,dml_subject,dml_send_date,EXISTS(SELECT * FROM t_dealer_mail_click WHERE dmn_dealer_mail_id=dml_dealer_mail_id) AS click_counter"
		. " FROM t_dealer_mail"
		. " WHERE dml_dealer_mail_id=$dealer_mail_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$status = $fetch->dml_status;
	$subject = $fetch->dml_subject;
	$send_date = $fetch->dml_send_date;
	$search_id = $fetch->dml_search_id;
	$click_counter = $fetch->click_counter;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "delete":
		return confirm("����Ź�᡼��������ޤ���������Ǥ�����");
	case "test":
		return confirm("�ƥ��ȥ᡼����������ޤ���������Ǥ�����");
	case "ready":
		return confirm("��������Ź�᡼��κ�����λ����������ǽ�����ꤷ�ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body>

<? center_header('����Ź�᡼���ۿ�����Ͽ����') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">������Ź�᡼����Ͽ����</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m1" width="20%">��̾(Subject)</td>
		<td class="n1"><?=htmlspecialchars($subject)?></td>
	</tr>
	<tr>
		<td class="m1">������������</td>
		<td class="n1"><?=format_datetime($send_date)?></td>
	</tr>
	<tr>
		<td class="m1">���֥ե饰</td>
		<td class="n1"><?=decode_status($status)?></td>
	</tr>
</table>
<br>
<input type="button" value="�᡼�륳��ƥ��" onclick="location.href='edit.php?dealer_mail_id=<?=$dealer_mail_id?>'">
<input type="button" value="�������" onclick="location.href='search.php?dealer_mail_id=<?=$dealer_mail_id?>'">
<input type="submit" value="�ƥ�������" onclick="document.form1.next_action.value='test'">
<?
if ($status == 1) {
?>
<input type="submit" value="������λ" onclick="document.form1.next_action.value='ready'">
<?
}
if ($click_counter == DBTRUE && ($status == 3 || $status == 4)) {
?>
<input type="button" value="���؎��������ݎ�CSV����" onclick="location.href='cc_csv.php?dealer_mail_id=<?=$dealer_mail_id?>'">
<?
}
?>
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="���������" onclick="location.href='list.php'">
<input type="hidden" name="next_action">
<input type="hidden" name="dealer_mail_id" <?=value($dealer_mail_id)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
