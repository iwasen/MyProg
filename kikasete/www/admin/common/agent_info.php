<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����������Ⱦ���ɽ��
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// ����������ȼ���
function decode_agent_disc($code) {
	switch ($code) {
	case 1:
		return '�������������Ұ�';
	case 2:
		return '�Х�����';
	case 3:
		return '���ϲ�ҼҰ�';
	}
}

// ����������Ⱦ���
function decode_agent_status($code) {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 9:
		return '̵��';
	}
}

// ����������ȼ���
function decode_agent_type($code) {
	switch ($code) {
	case 1:
		return '�����������';
	case 2:
		return '���֥����������';
	}
}

// ɽ������
function decode_disp_flag($code) {
	switch ($code) {
	case 't':
		return 'ɽ��';
	case 'f':
		return '��ɽ��';
	}
}

// �ᥤ�����
set_global('agent', '����������ȴ���', '����������Ⱦ���ɽ��', BACK_CLOSE);

if ($agent_id != '')
	$where = "ag_agent_id=$agent_id";
elseif ($mail_addr != '')
	$where = 'ag_mail_addr_low=' . sql_char(strtolower($mail_addr)) . ' AND ag_status<>9';

if ($where) {
	$sql = "SELECT * FROM t_agent WHERE $where";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<?
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
?>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">�����ܾ���</td>
		<td align="right"><input type="button" value="���Ĥ��롡" onclick="window.close()"></td>
	</tr>
	<tr>
		<td class="m1" width="20%">̾���ʴ�����</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ag_name1 $fetch->ag_name2")?></td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ag_name1_kana $fetch->ag_name2_kana")?></td>
	</tr>
	<tr>
		<td class="m1">��ǯ����</td>
		<td class="n1"><?=format_date($fetch->ag_birthday)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_sex($fetch->ag_sex)?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m1">����������ȼ���</td>
		<td class="n1"><?=decode_agent_disc($fetch->ag_agent_disc)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����̳�����</td>
	</tr>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_kinmu_name)?></td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_kinmu_unit)?></td>
	</tr>
	<tr>
		<td class="m1">��</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_kinmu_post)?></td>
	</tr>
	<tr>
		<td class="m1">����������Ƚ�°����</td>
		<td class="n1"><?=htmlspecialchars($fetch->ag_agent_unit)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���������</td>
	</tr>
	<tr>
		<td class="m1">���ʾҲ�ʸ</td>
		<td class="n1"><textarea cols=60 rows=5 readonly><?=htmlspecialchars($fetch->ag_self_introduction)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">ɽ������</td>
		<td class="n1"><?=decode_disp_flag($fetch->ag_disp_flag)?></td>
	</tr>
	<tr>
		<td class="m1">�£ã�����</td>
		<td class="n1"><textarea cols=60 rows=3 readonly><?=htmlspecialchars($fetch->ag_bcc_addr)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">��̾</td>
		<td class="n1"><textarea cols=60 rows=5 readonly><?=htmlspecialchars($fetch->ag_signature)?></textarea></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_agent_type($fetch->ag_type)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_agent_status($fetch->ag_status)?></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->ag_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǿ���������</td>
		<td class="n1"><?=format_datetime($fetch->ag_update_date)?></td>
	</tr>
</table>
<?
} else {
?>
<p class="msg">�������륨��������Ȥ���Ͽ����Ƥ��ޤ���</p>
<?
}
?>
<br>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</div>

<? page_footer() ?>
</body>
</html>
