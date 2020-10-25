<?
/******************************************************
' System :�֤���������� �������������ӥ�������Ź���ѥڡ���
' Content:��������̽���
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/greeting.php");
include("$inc/dealer_mail.php");
include("mail_inc.php");

// ����å�ɽ��
function display_thread($comm_id, &$seq_ary, $depth = ' ') {
	global $g_seq_no;
	global $comm_ary;

	if (strlen($depth) > MAX_THREAD_DEPTH)
		return;

	$sql = "SELECT cml_comm_id,cml_date,cml_send_recv,cmc_subject,cmc_comm_type,ups_name_kanji,stf_name"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " JOIN t_user_personal ON ups_user_id=cml_user_id"
			. " LEFT JOIN t_staff ON stf_staff_id=cml_staff_id"
			. " WHERE cml_comm_id=$comm_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$comm_ary[] = $fetch->cml_comm_id;
?>
<tr class="<?=$g_seq_no++ % 2 == 0 ? 'on' : 'off'?>">
	<td align="center"><?=$seq_ary[$fetch->cml_comm_id]?></td>
	<td><?=send_name($fetch->cmc_comm_type, $fetch->cml_send_recv, $fetch->ups_name_kanji, $fetch->stf_name)?></td>
	<td align="center"><?=mail_type($fetch->cmc_comm_type, $fetch->cml_send_recv)?></td>
	<td><?=$depth?><?=htmlspecialchars($fetch->cmc_subject)?></td>
	<td><?=date('Y/m/d H:i:s', sql_time($fetch->cml_date))?></td>
</tr>
<?
	} else
		return;

	$depth = str_replace('��', '��', $depth);

	$sql = "SELECT cml_comm_id FROM t_comm_log WHERE cml_parent_comm_id=$comm_id ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		display_thread($fetch->cml_comm_id, $seq_ary, $depth . '�� ');
	}
}

// ���ϥѥ�᡼��
$comm_id = $_GET['comm_id'];

// �桼��ID����
$sql = "SELECT cml_thread_comm_id,cml_user_id"
		. " FROM t_comm_log"
		. " WHERE cml_comm_id=$comm_id";
$result = db_exec($sql);
if (pg_num_rows($result) != 0) {
	$fetch = pg_fetch_object($result, 0);

	$thread_comm_id = $fetch->cml_thread_comm_id;
	$user_id = $fetch->cml_user_id;
}

// ���������ֹ����
$seq_ary = get_seq_no($user_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<title>����åɰ����ѥڡ���</title>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#f7df3e" align="center" width="100%" class="text12">
<tr>
	<td>

<table border="0" cellspacing="2" cellpadding="3" align="center" width="100%" id="mail" class="text12" bgcolor="#ffffff">
<tr bgcolor="#f7df3e">
	<th width="5%">SEQ</th>
	<th width="20%">������</th>
	<th width="5%">�᡼�����</th>
	<th width="50%">SUBJECT</th>
	<th width="20%">��������</th>
</tr>
<? display_thread($thread_comm_id, $seq_ary, $depth = ' ') ?>
</table>
</td>
</tr>
</table>

<?
if (is_array($comm_ary)) {
	foreach ($comm_ary as $comm_id) {
		$sql = "SELECT cml_comm_id,cml_date,cml_send_recv,cmc_subject,cmc_body,cmc_comm_type,ups_name_kanji,stf_name"
				. " FROM t_comm_log"
				. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
				. " JOIN t_user_personal ON ups_user_id=cml_user_id"
				. " LEFT JOIN t_staff ON stf_staff_id=cml_staff_id"
				. " WHERE cml_comm_id=$comm_id";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			// ����Ź�᡼�뺹���ѿ�����
			if ($fetch->cmc_comm_type == 1 && $fetch->cml_send_recv == 'S')
				$body = set_mail_boby($user_id, $fetch->cmc_body);
			else
				$body = &$fetch->cmc_body;
?>
<br>
<table border="1" cellspacing="0" cellpadding="3" bgcolor="#ffffff" align="center" width="100%" bordercolor="#999999" bordercolorlight="#cccccc" bordercolordark="#333333" class="text12">
<tr>
	<td colspan="3"><?=htmlspecialchars($fetch->cmc_subject)?></td>
</tr>
<tr>
	<td width="25%"><?=mail_type2($fetch->cmc_comm_type, $fetch->cml_send_recv)?></td>
	<td width="50%"><?=send_name($fetch->cmc_comm_type, $fetch->cml_send_recv, $fetch->ups_name_kanji, $fetch->stf_name)?></td>
	<td width="25%" align="right"><?=date('Y/m/d H:i:s', sql_time($fetch->cml_date))?></td>
</tr>
<tr>
	<td colspan="3"><pre><?=htmlspecialchars($body)?></pre></td>
</tr>
</table>
<?
		}
	}
}
?>
<form>
<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
<tr>
	<td align="center"><input type="button" name="" value="��������" onclick="window.print()" class="button"></td>
</tr>
</table>
</form>


</body>

</html>
