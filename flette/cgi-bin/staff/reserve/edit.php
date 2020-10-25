#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:ͽ���ǽ����������
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���ռ���
$t = getdate(strtotime($date));

// ǯ����������ʬ��
$year = $t['year'];
$month = $t['mon'];
$day = $t['mday'];
$week = $t['wday'];

// �����������
$sql = "SELECT rm_room_id,rm_room_name FROM m_room WHERE rm_shop_id=$shop_id AND rm_status=1 ORDER BY rm_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$room_ary[] = pg_fetch_object($result, $i);
}
$room_num = count($room_ary);

// �������������
$sql = "SELECT co_course_id,co_course_name FROM m_course ORDER BY co_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$course_ary[] = pg_fetch_object($result, $i);
}
$course_num = count($course_ary);

// ���ɲý���
$init = true;
if ($waku_add != '') {
	$init = false;
	$start_hour[$waku_add][] = '';
	$start_minute[$waku_add][] = '';
	$end_hour[$waku_add][] = '';
	$end_minute[$waku_add][] = '';
}

// �ǥե����ȿ�ǽ���
if ($waku_default != '') {
	$init = false;
	$sql = "SELECT dw_start_time,dw_end_time FROM m_default_waku WHERE dw_room_id=$waku_default";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		sscanf($fetch->dw_start_time, "%2s:%2s", &$start_hour_tmp[$i], &$start_minute_tmp[$i]);
		sscanf($fetch->dw_end_time, "%2s:%2s", &$end_hour_tmp[$i], &$end_minute_tmp[$i]);
	}
	$start_hour[$waku_default] = $start_hour_tmp;
	$start_minute[$waku_default] = $start_minute_tmp;
	$end_hour[$waku_default] = $end_hour_tmp;
	$end_minute[$waku_default] = $end_minute_tmp;

	unset($flag[$waku_default]);
}

if ($init) {
	// �����Ӿ������
	$sql = "SELECT wk_room_id,wk_start_time,wk_end_time"
			. " FROM t_waku"
			. " JOIN m_room ON rm_room_id=wk_room_id"
			. " WHERE rm_shop_id=$shop_id AND rm_status=1 AND wk_date=" . sql_date($date)
			. " GROUP BY wk_room_id,wk_date,wk_start_time,wk_end_time"
			. " ORDER BY wk_date,wk_start_time";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$room_id = $fetch->wk_room_id;
		$start_time = $fetch->wk_start_time;
		$end_time = $fetch->wk_end_time;
		$waku_no = (int)$waku_no_ary[$room_id]++;

		sscanf($start_time, "%2s:%2s", &$start_hour[$room_id][$waku_no], &$start_minute[$room_id][$waku_no]);
		sscanf($end_time, "%2s:%2s", &$end_hour[$room_id][$waku_no], &$end_minute[$room_id][$waku_no]);

		$waku_ary[$room_id][$start_time][$end_time] = $waku_no;
	}

	// �����Ⱦ������
	$sql = "SELECT wk_room_id,wk_course_id,wk_start_time,wk_end_time,wk_sex_cd"
			. " FROM t_waku"
			. " JOIN m_room ON rm_room_id=wk_room_id"
			. " WHERE rm_shop_id=$shop_id AND rm_status=1 AND wk_date=" . sql_date($date)
			. " ORDER BY wk_date,wk_start_time";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$room_id = $fetch->wk_room_id;
		$waku_no = $waku_ary[$room_id][$fetch->wk_start_time][$fetch->wk_end_time];
		$flag[$room_id][$waku_no][$fetch->wk_course_id][$fetch->wk_sex_cd] = 1;
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onclick_waku_add(room_id) {
	var f = document.form1;
	f.waku_add.value = room_id;
	f.action = "edit.php";
}
function onclick_waku_default(room_id) {
	var f = document.form1;
	f.waku_default.value = room_id;
	f.action = "edit.php";
}
function onsubmit_form1() {
	var f = document.form1;
	for (i = 0; i < f.elements.length; i++) {
		e = f.elements[i];
		if (e.name && e.name.substr(0, 10) == "start_hour") {
			idx = e.name.substr(10);
			start_hour = f["start_hour" + idx];
			start_minute = f["start_minute" + idx];
			end_hour = f["end_hour" + idx];
			end_minute = f["end_minute" + idx];
			if (start_hour.value != "" || start_minute.value != "" || end_hour.value != "" || end_minute.value != "") {
				if (start_hour.value == "") {
					alert("���ϻ��֤����򤵤�Ƥ��ޤ���");
					start_hour.focus();
					return false;
				}
				if (start_minute.value == "") {
					alert("���ϻ��֤����򤵤�Ƥ��ޤ���");
					start_minute.focus();
					return false;
				}
				if (end_hour.value == "") {
					alert("��λ���֤����򤵤�Ƥ��ޤ���");
					end_hour.focus();
					return false;
				}
				if (end_minute.value == "") {
					alert("��λ���֤����򤵤�Ƥ��ޤ���");
					end_minute.focus();
					return false;
				}
				if (start_hour.value + start_minute.value > end_hour.value + end_minute.value) {
					alert("��λ����ϳ��ϻ�����̤��λ��֤���ꤷ�Ƥ���������");
					end_hour.focus();
					return false;
				}
			}
		}
	}
	f.action = "update.php";
	return confirm("ͽ���ǽ�����Ӥ���Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? page_header('ͽ���ǽ����������', true, true) ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td>��<?=decode_shop($shop_id)?>������<b><?=$year?>ǯ<?=$month?>��<?=$day?>�� ��<?=decode_week($week)?>������</b></td>
	</tr>
</table>
<br>

<table border=0 cellpadding=0 cellspacing=1 width="600">
<?
for ($i = 0; $i < $room_num; $i++) {
	$room_id = $room_ary[$i]->rm_room_id;
?>
	<tr>
		<td>��<?=htmlspecialchars($room_ary[$i]->rm_room_name)?></td>
	</tr>
	<tr>
		<td>
			<table <?=LIST_TABLE?> width="100%">
				<tr>
					<td rowspan=2 align="center">
						<input type="submit" value="���ɲ�" onclick="onclick_waku_add(<?=$room_id?>)">
						<input type="submit" value="�ǥե���Ȥ�ȿ��" onclick="onclick_waku_default(<?=$room_id?>)">
					</td>
<?
	for ($j = 0; $j < $course_num; $j++) {
?>
					<td colspan=2 align="center"><?=htmlspecialchars($course_ary[$j]->co_course_name)?></td>
<?
	}
?>
				</tr>
				<tr>
<?
	for ($j = 0; $j < $course_num; $j++) {
?>
					<td align="center" width=45>��</td>
					<td align="center" width=45>��</td>
<?
	}
?>
				</tr>
<?
	$waku_num = count($start_hour[$room_id]);
	for ($waku_no = 0; $waku_no < $waku_num || $waku_no < 6; $waku_no++) {
?>
				<tr>
					<td align="center">
						<select name="start_hour[<?=$room_id?>][<?=$waku_no?>]" id="start_hour"><? select_hour($start_hour[$room_id][$waku_no]) ?></select>
						��
						<select name="start_minute[<?=$room_id?>][<?=$waku_no?>]" id="start_minute"><? select_minute($start_minute[$room_id][$waku_no]) ?></select>
						��
						<select name="end_hour[<?=$room_id?>][<?=$waku_no?>]" id="end_hour"><? select_hour($end_hour[$room_id][$waku_no]) ?></select>
						��
						<select name="end_minute[<?=$room_id?>][<?=$waku_no?>]" id="end_minute"><? select_minute($end_minute[$room_id][$waku_no]) ?></select>
					</td>
<?
		for ($j = 0; $j < $course_num; $j++) {
			$course_id = $course_ary[$j]->co_course_id;
?>
					<td align="center"><input type="checkbox" name="flag[<?=$room_id?>][<?=$waku_no?>][<?=$course_id?>][1]" value="1" <?=checked($flag[$room_id][$waku_no][$course_id][1])?>></td>
					<td align="center"><input type="checkbox" name="flag[<?=$room_id?>][<?=$waku_no?>][<?=$course_id?>][2]" value="1" <?=checked($flag[$room_id][$waku_no][$course_id][2])?>></td>
<?
		}
?>
				</tr>
<?
	}
?>
			</table>
			<br>
		</td>
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="����Ͽ��" onclick="return onsubmit_form1()">
<input type="button" value="����롡" onclick="location.href='monthly.php?shop_id=<?=$shop_id?>'">
<input type="hidden" name="shop_id" <?=value($shop_id)?>>
<input type="hidden" name="date" <?=value($date)?>>
<input type="hidden" name="waku_add">
<input type="hidden" name="waku_default">
</form>
</div>

<? page_footer() ?>

</body>
</html>
