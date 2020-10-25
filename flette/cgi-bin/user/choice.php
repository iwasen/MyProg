#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");

// ��˾���Խ�
function choice_value(&$reserve, $index) {
	$choice = &$reserve->choice[$index];
	if ($choice->room_id == 0)
		return '';
	else
		return "$choice->room_id,$choice->date,$choice->start_time,$choice->end_time";
}

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('/index.html');

// ����
$now = getdate();
$today = mktime(0, 0, 0, $now['mon'], $now['mday'], $now['year']);

// �⡹��
$date2 = $today + DAY_SEC * 2;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function erase() {
	var f = document.form1;
	for (i = 0; i < 3; i++)
		f["choice[]"][i].value = "";
	set_select();
}
function reserve(room_id, date, start_time, end_time) {
	var f = document.form1;
	var p = room_id + "," + date + "," + start_time + "," + end_time;
	for (i = 0; i < 3; i++) {
		if (f["choice[]"][i].value == p)
			return;
		if (f["choice[]"][i].value == "")
			break;
	}
	if (i < 3) {
		f["choice[]"][i].value = p;
		set_select();
	} else
		alert("ͽ���˾�����ϣ��Ĥޤ�����Ǥ��ޤ���");
}
function set_select() {
	var f = document.form1;
	for (i = 0; i < 3; i++) {
		if (f["choice[]"][i].value != "") {
			a = f["choice[]"][i].value.split(",");
			d = new Date(a[1]);
			w = new Array("��", "��", "��", "��", "��", "��", "��");
			f.select.options[i].text = a[1] + "��" + w[d.getDay()] + "�� " + a[2] + "��" + a[3];
		} else
			f.select.options[i].text = "";
	}
}
function change_ym(year, month) {
	var f = document.form1;
	f.year.value = year;
	f.month.value = month;
	f.next.value = "choice.php";
	f.submit();
}
function appl() {
	var f = document.form1;
	if (f["choice[]"][0].value == "") {
		alert("ͽ����֤����򤷤Ƥ���������");
		return;
	}
	f.next.value = "reserve.php";
	f.submit();
}
//-->
</script>
</head>
<body onload="set_select()">
<?=$reserve->course?>��<?=$reserve->shop?>
<br>
�����򤷤��������������Ϥ���ޤ���<input type="button" value="���ꥢ" onclick="erase()">
<br>
<form method="post" name="form1" action="choice2.php">
<input type="hidden" name="year" <?=value($reserve->year)?>>
<input type="hidden" name="month" <?=value($reserve->month)?>>
<input type="hidden" name="choice[]" <?=value(choice_value($reserve, 0))?>>
<input type="hidden" name="choice[]" <?=value(choice_value($reserve, 1))?>>
<input type="hidden" name="choice[]" <?=value(choice_value($reserve, 2))?>>
<input type="hidden" name="next">
<select name="select" size=3 style="width:300"><option></option><option></option><option></option></select>
<input type="button" value="ͽ�󿽤�����" onclick="appl()">
<br>
<br>
<div align="center">
<?
for ($i = 0, $y = (int)date('Y', $today), $m = (int)date('n', $today); $i < 3; $i++) {
	if ($y == $reserve->year && $m == $reserve->month) {
?>
<b><?=$y?>ǯ<?=$m?>��</b>
<?
	} else {
?>
<a href="javascript:change_ym(<?=$y?>, <?=$m?>)"><?=$y?>ǯ<?=$m?>��</a>
<?
	}
	if (++$m > 12) {
		$m = 1;
		$y++;
	}
}
?>
<br>
<br>
<table border=1 cellspacing=1 frame="box" width="100%">
<?
// �����������
$sql = "SELECT rm_room_id,rm_room_name FROM m_room WHERE rm_shop_id=$reserve->shop_id AND rm_status=1 ORDER BY rm_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$room_ary[$i] = pg_fetch_object($result, $i);
}

// �Ⱦ������
$sql = "SELECT wk_room_id,to_char(wk_date,'DD') AS wk_day,wk_start_time,wk_end_time,sum(wk_sex_cd) AS wk_sex_cd2"
		. " FROM t_waku"
		. " JOIN m_room ON rm_room_id=wk_room_id"
		. " WHERE rm_shop_id=$reserve->shop_id AND rm_status=1 AND wk_course_id=$reserve->course_id AND to_char(wk_date,'YYYYMM')=" . sql_char(sprintf('%04d%02d', $reserve->year, $reserve->month))
		. " GROUP BY wk_room_id,wk_date,wk_start_time,wk_end_time"
		. " ORDER BY wk_date,wk_start_time";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$start_time = substr($fetch->wk_start_time, 0, 5);
	$end_time = substr($fetch->wk_end_time, 0, 5);

	if ($fetch->wk_sex_cd2 == 1)
		$sex = '���ˡ�';
	elseif ($fetch->wk_sex_cd2 == 2)
		$sex = '�ʽ���';
	else
		$sex = '���˽���';

	$waku_ary[(int)$fetch->wk_day][$fetch->wk_room_id][$i]->start_time = $start_time;
	$waku_ary[(int)$fetch->wk_day][$fetch->wk_room_id][$i]->end_time = $end_time;
	$waku_ary[(int)$fetch->wk_day][$fetch->wk_room_id][$i]->sex = $sex;
}

$d = strtotime("$reserve->year/$reserve->month/1");
$st = $d - date('w', $d) * DAY_SEC;
$ed = strtotime("$reserve->year/" . ($reserve->month + 1) . "/1");

for ($i = 0, $dw = $st; $dw < $ed; $i++, $dw += DAY_SEC * 7) {
	if ($i % 2 == 0) {
?>
	<tr class="tch">
		<th width="10%"></th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%">��</th>
		<th width="10%">��</th>
	</tr>
<?
	}
?>
	<tr bgcolor="#aaffcc">
		<td></td>
<?
	for ($j = 0, $dd = $dw; $j < 7; $j++, $dd += DAY_SEC) {
		if (date('m', $dd) == $reserve->month) {
			if ($dd == $today) {
?>
		<td align='center' bgcolor="#ffff99"><font size='+1'><b><?=(int)date('d', $dd)?></b></font></td>
<?
			} else {
?>
		<td align='center'><font size='+1'><b><?=(int)date('d', $dd)?></b></font></td>
<?
			}
		} else {
?>
		<td></td>
<?
		}
	}
?>
	</tr>
<?
	for ($j = 0; $j < count($room_ary); $j++) {
		$room_id = $room_ary[$j]->rm_room_id;
?>
	<tr>
		<td align="center"><?=htmlspecialchars($room_ary[$j]->rm_room_name)?></td>
<?
		for ($k = 0, $dd = $dw; $k < 7; $k++, $dd += DAY_SEC) {
			if (date('m', $dd) == $reserve->month) {
				if ($dd == $today) {
?>
		<td bgcolor="#ffff99"><br><br></td>
<?
				} elseif ($dd < $date2) {
?>
		<td bgcolor="#cccccc"><br><br></td>
<?
				} else {
?>
		<td>
<?
					$day = (int)date('d', $dd);
					if (is_array($waku_ary[$day][$room_id])) {
						foreach ($waku_ary[$day][$room_id] as $waku) {
?>
			<a href="javascript:void(0)" onclick="reserve(<?=$room_id?>,'<?=date('Y/m/d', $dd)?>','<?=$waku->start_time?>','<?=$waku->end_time?>')"><font size="-1"><?=$waku->start_time?>��<?=$waku->end_time?><?=$waku->sex?></font></a><br>
<?
						}
					}
?>
		</td>
<?
				}
			} else {
?>
		<td></td>
<?
			}
	}
?>
	</tr>
<?
	}
}
?>
</table>
</div>
</form>
</body>
</html>
