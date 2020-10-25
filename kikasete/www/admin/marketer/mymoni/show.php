<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ���˥���ɽ������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/my_monitor.php");

set_global('marketer', '�ޡ������������ãͣ���˥�������', '�ͣ���˥���ɽ��', BACK_TOP);

// �롼�����
function decode_moni_status($code, $def='����') {
	switch ($code) {
	case 0:
		return '������';
	case 1:
		return '��̳�ɥ����å���';
	case 2:
		return '��ǧ�Ѥ�';
	case 3:
		return '�»���';
	case 4:
		return '������';
	case 5:
		return '��λ';
	case 9:
		return '����Ѥ�';
	}
	return $def;
}

// ��������̳�ɥ����å�����ѹ�
if ($check == '1' && $enquete->status == 0) {
	$sql = "UPDATE t_my_monitor SET mm_status=1 WHERE mm_room_id=$room_id";
	db_exec($sql);
}

$mymoni = new my_monitor_class;
$mymoni->read_db($room_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	return confirm("���Σͣ���˥����롼���ǧ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�롼��̾</td>
		<td class="n1" width="80%"><?=htmlspecialchars($mymoni->room_name)?></td>
	</tr>
	<tr>
		<td class="m1">������</td>
		<td class="n1"><?=$mymoni->start_date_y?>ǯ<?=$mymoni->start_date_m?>��<?=$mymoni->start_date_d?>��</td>
	</tr>
	<tr>
		<td class="m1">�»ܴ���</td>
		<td class="n1"><?=$mymoni->period?>����ʽ�λ�� <?=$mymoni->end_date_y?>ǯ<?=$mymoni->end_date_m?>��<?=$mymoni->end_date_d?>����</td>
	</tr>
	<tr>
		<td class="m1">���С����</td>
		<td class="n1"><?=nl2br(htmlspecialchars($mymoni->condition))?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_moni_status($mymoni->status)?></td>
	</tr>
	<tr>
		<td class="m1">�������Υ᡼��</td>
		<td class="n1"><?=$mymoni->start_mail_flag == 't' ? '�����Ѥ�' : '̤����'?></td>
	</tr>
	<tr>
		<td class="m1">��λ���Υ᡼��</td>
		<td class="n1"><?=$mymoni->end_mail_flag == 't' ? '�����Ѥ�' : '̤����'?></td>
	</tr>
	<tr>
		<td class="m1">���С�</td>
		<td class="n1">
<?
$sql = "SELECT mn_monitor_id,mn_name1,mn_name2,mn_mail_addr"
		. " FROM t_my_monitor_list JOIN t_monitor ON mn_monitor_id=rl_monitor_id"
		. " WHERE rl_room_id=$room_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
?>
			<table <?=LIST_TABLE?> width="100%">
<?
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
				<tr>
					<td align="center"><?=$fetch->mn_monitor_id?></td>
					<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
				</tr>
<?
	}
?>
			</table>
<?
} else
	echo '���С�����Ͽ����Ƥ��ޤ���';
?>
		</td>
	</tr>
</table>
<br>
<input type="button" value="���С���Ͽ" onclick="location.href='member_list.php?room_id=<?=$room_id?>'">
<input type="button" value="���ѹ���" onclick="location.href='edit.php?room_id=<?=$room_id?>'">
<? if ($enquete->status == 0 || $enquete->status == 1) { ?>
<input type="submit" value="����ǧ��" onclick="document.form1.next_action.value='app'">
<? } ?>
<input type="button" value="����롡" onclick="location.href='list.php'">
<input type="hidden" name="next_action">
<input type="hidden" name="room_id" value="<?=$room_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
