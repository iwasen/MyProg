<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ɲ�ȯ��Ƚ��
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '�ɲ�ȯ��Ƚ��', BACK_TOP);

// ��ļ��������
$sql = "SELECT ppj_room_name,ppj_real_send,ppj_member_num,ppj_select_sql,ppj_recruit_start,ppj_recruit_end"
		. " FROM t_mp_pjt"
		. " WHERE ppj_pjt_id=$pjt_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$room_name = $fetch->ppj_room_name;
	$send_num = (int)$fetch->ppj_real_send;
	$member_num = (int)$fetch->ppj_member_num;
	$select_sql = $fetch->ppj_select_sql;

	$hour_a = (int)((time() - sql_time($fetch->ppj_recruit_start)) / (60 * 60));
	if ($hour_a < 0)
		$hour_a = 0;

	$hour_b = (int)((sql_time($fetch->ppj_recruit_end) - time()) / (60 * 60));
	if ($hour_b < 0)
		$hour_b = 0;

	// �ֿ�������
	$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id";
	$reply_num = db_fetch1($sql);

	// �����Կ�����
	if ($select_sql != '') {
		$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id AND $select_sql";
		$sel_num = db_fetch1($sql);
	} else
		$sel_num = $reply_num;

	// ����������
	$sql = "SELECT mas_factor FROM m_addition_send WHERE mas_hour<=$hour_a ORDER BY mas_hour DESC LIMIT 1";
	$factor_a = db_fetch1($sql) / 100;

	// �����¼���
	$sql = "SELECT mas_factor FROM m_addition_send WHERE mas_hour<=$hour_b ORDER BY mas_hour DESC LIMIT 1";
	$factor_b = db_fetch1($sql) / 100;

	// ͽ¬�ǽ��ֿ���
	$final_reply_num = round($reply_num / $factor_a);
	if ($final_reply_num > $send_num)
		$final_reply_num = $send_num;

	// ͽ¬�ǽ������Կ�
	$final_sel_num = round($sel_num / $factor_a);
	if ($final_sel_num > $final_reply_num)
		$final_sel_num = $final_reply_num;

	// ��­��
	$deficit_num = $member_num - $final_sel_num;

	if ($final_sel_num > 0) {
		// �ɲ�ȯ����
		$add_send_num = round($deficit_num / ($final_sel_num / $send_num) / $factor_b);
		if ($add_send_num < 10)
			$add_send_num = 10;

		// �ɲúǽ�ȯ����
		$add_final_send_num = $send_num + $add_send_num;

		// �ɲúǽ��ֿ���
		$add_final_reply_num = round($final_reply_num + $add_send_num * ($final_reply_num / $send_num) * $factor_b);

		// �ɲúǽ������Կ�
		$add_final_sel_num = round($final_sel_num + $add_send_num * ($final_sel_num / $send_num) * $factor_b);
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��<?=htmlspecialchars($room_name)?></td>
		<td class="lb">
			<input type="button" value="����롡" onclick="history.back()">
		</td>
	</tr>
</table>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
		<th width="20%">&nbsp;</th>
		<th width="10%">ȯ����</th>
		<th width="10%">�ֿ�����Ψ��</th>
		<th width="10%">��������Ψ��</th>
		<th width="10%">��ɸ��</th>
		<th width="10%">��­��</th>
		<th width="30%">����</th>
	</tr>
	<tr class="tc0" align="center">
		<th class="tch">���ߤξ���</th>
		<td><?=number_format($send_num)?></td>
		<td><?=number_format($reply_num)?>(<?=format_percent($reply_num, $send_num)?>)</td>
		<td><?=number_format($sel_num)?>(<?=format_percent($sel_num, $send_num)?>)</td>
		<td><?=number_format($member_num)?></td>
		<td><?=number_format($member_num - $sel_num)?></td>
		<td>&nbsp;</td>
	</tr>
	<tr class="tc1" align="center">
		<th class="tch">�ǽ�ͽ¬</th>
		<td><?=number_format($send_num)?></td>
		<td><?=number_format($final_reply_num)?>(<?=format_percent($final_reply_num, $send_num)?>)</td>
		<td><strong><?=number_format($final_sel_num)?>(<?=format_percent($final_sel_num, $send_num)?>)</strong></td>
		<td><?=number_format($member_num)?></td>
		<td bgcolor="#ffff00"><strong><?=number_format($member_num - $final_sel_num)?></strong></td>
		<td>ȯ��������<?=$factor_a?></td>
	</tr>
<?
if ($deficit_num > 0 && $final_sel_num > 0) {
?>
	<tr class="tc2" align="center">
		<th class="tch">�ɲ�ȯ��ͽ¬</th>
		<td><strong><?=number_format($add_final_send_num)?></strong></td>
		<td><?=number_format($add_final_reply_num)?>(<?=format_percent($add_final_reply_num, $add_final_send_num)?>)</td>
		<td><strong><?=number_format($add_final_sel_num)?>(<?=format_percent($add_final_sel_num, $add_final_send_num)?>)</strong></td>
		<td><?=number_format($member_num)?></td>
		<td><?=number_format($member_num - ($add_final_sel_num))?></td>
		<td>�ɲ÷�����<?=$factor_b?></td>
	</tr>
<?
}
?>
</table>
<br>
<?
if ($deficit_num <= 0) {
?>
<div align="center">��ɸ�����Ф��Ƴ����Կ�����­�Ϥ���ޤ���</div>
<br>
<input type="button" value="����롡" onclick="history.back()">
<?
} elseif ($final_sel_num == 0) {
?>
<div align="center">�ޤ������Ԥ����ʤ������ɲ�ȯ������ͽ¬���Ǥ��ޤ���</div>
<br>
<input type="button" value="����롡" onclick="history.back()">
<?
} else {
?>
<div align="center">��ɸ����<strong><?=number_format($member_num)?></strong>���ͤޤǤˤϤ��ȡ�<strong><?=number_format($add_send_num)?></strong>���ͤ��ɲ�ȯ������ɬ�פ�����ޤ���</div>
<div align="right"><small>�����ο����Ϥ����ޤǻ��ͤǤ���</small></div>
<br>
<input type="button" value="�ɲ�ȯ���򤹤�" onclick="location.href='r_job_edit.php?pjt_id=<?=$pjt_id?>&df=1&add=<?=$add_final_send_num?>'">
<input type="button" value="�ɲ�ȯ���Ϥ��ʤ�" onclick="history.back()">
<?
}
?>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
