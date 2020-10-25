<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�䤤��碌����ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/greeting.php");
include("$inc/dealer_mail.php");

// ���ϥѥ�᡼��
$comm_id = $_GET['comm_id'];

// �ᥤ�����
$sql = "SELECT cml_user_id,cml_date,cml_send_recv,cmc_comm_type,cmc_subject,cmc_body,cml_err_mail_flag,ups_name_kanji,ups_mail_addr"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_personal ON ups_user_id=cml_user_id"
		. " WHERE cml_comm_id=$comm_id $order_by";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);

// ����Ź�᡼�뺹���ѿ�����
if ($fetch->cmc_comm_type == 1 && $fetch->cml_send_recv == 'S')
	$body = set_mail_boby($fetch->cml_user_id, $fetch->cmc_body);
else
	$body = &$fetch->cmc_body;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header('�桼������ɽ���å᡼������������å᡼������') ?>

<div align="center">
<form method="post" name="form1" action="answer.php">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">���᡼�������</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="history.back()">
			<input type="button" value="���Ĥ��롡" onclick="window.close()">
		</td>
	</tr>
	<tr>
		<td class="m1">̾��</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_name_kanji)?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->ups_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=format_datetime($fetch->cml_date)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_comm_type($fetch->cmc_comm_type)?><?=decode_send_recv($fetch->cml_send_recv)?></td>
	</tr>
	<tr>
		<td class="m1">��̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->cmc_subject)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<textarea class="kanji" cols=78 rows=30 readonly><?=$body?></textarea>
		</td>
	</tr>
</table>
<br>
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? center_footer() ?>

</body>
</html>
