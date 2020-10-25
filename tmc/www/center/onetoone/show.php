<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦�䤤��碌����ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");

// ���ϥѥ�᡼��
$comm_id = $_GET['comm_id'];
$url = $_GET['url'];
$del = $_GET['del'];

// �䤤��碌���Ƽ���
$sql = "SELECT cml_send_recv,cml_answer_flag,cmc_comm_type,cmc_body,ups_name_kanji,ups_user_id,ups_mail_addr"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_personal ON ups_user_id=cml_user_id"
		. " WHERE cml_comm_id=$comm_id";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onclick_delete() {
	var f = document.form1;
	if (confirm('���Υ᡼����˴����ޤ���������Ǥ�����')) {
		f.action = "delete.php";
		f.submit();
	}
}
function onclick_taiou() {
	var f = document.form1;
	if (confirm('���Υ᡼����б��Ѥ߰����Ȥ��ޤ���������Ǥ�����')) {
		f.action = "taiou.php";
		f.submit();
	}
}
//-->
</script>
</head>
<body>

<? center_header('OneToOne�᡼�������ɽ��') ?>

<div align="center">
<form method="get" name="form1" action="answer.php">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>���䤤��碌������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�桼��ID</td>
		<td class="n1"><?=$fetch->ups_user_id?></td>
	</tr>
	<tr>
		<td class="m1">�桼��̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_name_kanji)?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1">�䤤��碌��ʬ</td>
		<td class="n1"><?=decode_comm_type($fetch->cmc_comm_type)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><textarea class="kanji" cols=78 rows=30 readonly><?=$fetch->cmc_body?></textarea></td>
	</tr>
	<tr>
		<td class="m1">ź�եե�����</td>
		<td class="n1">
<?
$sql = "SELECT mat_file_no,mat_file_size,mat_file_name FROM t_mail_attachment WHERE mat_comm_id=$comm_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
if ($nrow != 0) {
?>
			<table border=0 cellspacing=0 cellpadding=2>
<?
	for ($i = 0; $i < $nrow; $i++) {
		$fetch2 = pg_fetch_object($result, $i);
?>
				<tr>
					<td><a href="<?=$top?>/common/show_attach.php?comm_id=<?=$comm_id?>&file_no=<?=urlencode($fetch2->mat_file_no)?>" target="_blank"><?=htmlspecialchars($fetch2->mat_file_name)?></a></td>
					<td align="right"><?=number_format(round($fetch2->mat_file_size / 1024)) . 'KB'?></td>
				</tr>
<?
	}
?>
			</table>
<?
} else {
?>
			̵��
<?
}
?>
		</td>
	</tr>
</table>
<br>
<? if ($del) {?>
<input type="button" value="�˴�����" onclick="onclick_delete()">
<? } ?>
<? if ($url && $fetch->cml_send_recv == 'R') { ?>
<? if ($fetch->cml_answer_flag == DBTRUE) { ?>
<input type="button" value="�б�����" onclick="onclick_taiou()">
<? } ?>
<input type="submit" value="�ֿ�����">
<? } ?>
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="comm_id" value="<?=$comm_id?>">
<input type="hidden" name="url" value="<?=$url?>">
</form>
</div>

<? center_footer() ?>

</body>
</html>
