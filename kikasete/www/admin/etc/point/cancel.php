<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ݥ���������Ѳ���ó�ǧ����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �򴹾��ʥǥ�����
function decode_shouhin($code) {
	switch ($code) {
	case 1:
		return 'VISA���եȷ�';
	case 2:
		return '�޽��';
	case 3:
		return 'WEBMONEY';
	case 4:
		return '��ŷ���';
	}
}

// �ᥤ�����
set_global('etc', '����¾����', '�ݥ��������', BACK_TOP);

$sql = "SELECT si_member_id,si_point,si_req_date,si_item,mn_name1,mn_name2"
	. " FROM t_seisan"
	. " JOIN t_monitor ON mn_monitor_id=si_member_id"
	. " WHERE si_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$monitor_id = $fetch->si_member_id;
	$point = number_format($fetch->si_point);
	$shouhin = decode_shouhin($fetch->si_item);
	$date = substr($fetch->si_req_date, 0, 16);
	$name = "$fetch->mn_name1 $fetch->mn_name2";
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
<p class="msg">
<table border="0" cellspacing="0" cellpadding="0" style="color: darkblue">
	<tr>
		<td>��˥���ID</td>
		<td>��<?=$monitor_id?></td>
	</tr>
	<tr>
		<td>������̾</td>
		<td>��<?=htmlspecialchars($name)?></td>
	</tr>
	<tr>
		<td>�����ݥ����</td>
		<td>��<?=$point?></td>
	</tr>
	<tr>
		<td>������</td>
		<td>��<?=$date?></td>
	</tr>
	<tr>
		<td>�򴹾���</td>
		<td>��<?=htmlspecialchars($shouhin)?></td>
	</tr>
</table>
�εѲ����ä��ޤ������������Ǥ��礦����<br>
<br>
���Ѳ�����ä��ȡ����֤���̤�����פ����ޤ���<br>
�ޤ�����˥����ͣ��ڡ�������������������<br>
����ʬ�Υݥ���Ȥ����ٸ�������ޤ���<br>
</p>
<p>
	<input type="button" value="���Ϥ���" onclick="location.href='cancel2.php?seq_no=<?=$seq_no?>'">
	<input type="button" value="������" onclick="location.href='list.php'">
</p>
</div>

<? page_footer() ?>
</body>
</html>
