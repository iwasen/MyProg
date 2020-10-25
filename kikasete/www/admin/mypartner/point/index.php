<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・ポイント付与
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

function decode_point_timing($code) {
	switch ($code) {
	case 1:
		return '一括';
	case 2:
		return '月別';
	}
}

function decode_point_rule($code) {
	switch ($code) {
	case 1:
		return '発言日数';
	case 2:
		return '総発言';
	case 3:
		return '月ぎめ';
	}
}

function add_month($ym) {
	sscanf($ym, '%4d%2d', $y, $m);
	if (++$m > 12) {
		$y++;
		$m = 1;
	}
	return sprintf('%04d%02d', $y, $m);
}

function get_default_date($pjt_id, &$calc_date, &$give_date) {
	$sql = "SELECT ppj_room_end FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$room_end = db_fetch1($sql);
	$y = get_datepart('Y', $room_end);
	$md = sprintf('%02d%02d', get_datepart('M', $room_end), get_datepart('D', $room_end));

	$sql = "SELECT ppd_calc_date,ppd_give_date FROM m_mp_point_date WHERE ppd_calc_date>='$md' ORDER BY ppd_calc_date LIMIT 1";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "SELECT ppd_calc_date,ppd_give_date FROM m_mp_point_date WHERE ppd_month=1";
		$result = db_exec($sql);
		$y++;
	}
	$fetch = pg_fetch_object($result, 0);
	$calc_date = $y . $fetch->ppd_calc_date;
	$give_date = $y . $fetch->ppd_give_date;
}

//メイン処理
set_global('mypartner', 'Myパートナー管理', 'Myパートナー　ポイント付与', BACK_TOP);

$sql = "SELECT ppj_room_name,ppj_point_timing,ppj_point_rule,ppj_point_max,ppj_point_unit,ppj_point_bonus"
		. " FROM t_mp_pjt"
		. " WHERE ppj_pjt_id=$pjt_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
$point_timing = $fetch->ppj_point_timing;
$point_bonus = $fetch->ppj_point_bonus;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function date_edit(ym, calc_ymd, give_ymd) {
	window.open("p_date_edit.php?pjt_id=<?=$pjt_id?>&ym=" + ym + "&calc_ymd=" + calc_ymd + "&give_ymd=" + give_ymd, "date_edit", "width=300,height=130");
}
function bonus_edit(ym, monitor_id) {
	window.open("p_bonus_edit.php?pjt_id=<?=$pjt_id?>&ym=" + ym + "&monitor_id=" + monitor_id, "bonus_edit", "width=300,height=130");
}
function kakutei(ym) {
	if (confirm("ポイントを確定します。よろしいですか？"))
		location.href = "kakutei.php?pjt_id=<?=$pjt_id?>&ym=" + ym;
}
function kakutei_cancel(ym) {
	if (confirm("ポイントの確定を取り消します。よろしいですか？"))
		location.href = "kakutei.php?pjt_id=<?=$pjt_id?>&ym=" + ym + "&cancel=1";
}
//-->
</script>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($fetch->ppj_room_name)?></td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='<?=get_return_page()?>'">
		</td>
	</tr>
</table>

<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr class="lc">
		<td class="lt">ポイント付与ルール</td>
		<td class="lb">
			<input type="button" value="　設定　" onclick="location.href='edit.php?pjt_id=<?=$pjt_id?>'">
		</td>
	</tr>
	<tr class="lc">
		<td valign="top">
			■基本設定
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="90%">
			<tr>
				<th class="m1">付与時期</th>
				<td class="n1"><?=decode_point_timing($fetch->ppj_point_timing)?></td>
			</tr>
			<tr>
				<th class="m1">基本ルール</th>
				<td class="n1"><?=decode_point_rule($fetch->ppj_point_rule)?></td>
			</tr>
			<tr>
				<th class="m1">ポイント数</th>
				<td class="n1"><?=number_format($fetch->ppj_point_unit)?>ﾎﾟｲﾝﾄ</td>
			</tr>
			<tr>
				<th class="m1">一人当たり最大ポイント数</th>
				<td class="n1"><?=number_format($fetch->ppj_point_max)?>ﾎﾟｲﾝﾄ</td>
			</tr>
			</table>
		</td>
		<td valign="top">
			■ボーナス設定
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="90%">
			<tr>
				<th class="m1">ボーナス設定</th>
				<td class="n1"><?=$fetch->ppj_point_bonus == 't' ? '有' : '無'?></td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<?
// ポイント付与履歴取得
$sql = "SELECT ppl_year_month,ppl_calc_date,ppl_give_date,ppl_calc_flag,ppl_give_flag,ppl_fix_flag"
		. " FROM t_mp_point_log"
		. " WHERE ppl_pjt_id=$pjt_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$point_tbl[$fetch->ppl_year_month]['calc_date'] = $fetch->ppl_calc_date;
	$point_tbl[$fetch->ppl_year_month]['give_date'] = $fetch->ppl_give_date;
	$point_tbl[$fetch->ppl_year_month]['calc_flag'] = $fetch->ppl_calc_flag;
	$point_tbl[$fetch->ppl_year_month]['give_flag'] = $fetch->ppl_give_flag;
	$point_tbl[$fetch->ppl_year_month]['fix_flag'] = $fetch->ppl_fix_flag;
}

// モニター別ポイント取得
$sql = "SELECT pmp_year_month,pmp_monitor_id,pmp_point,pmp_bonus FROM t_mp_monitor_point WHERE pmp_pjt_id=$pjt_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$point_tbl[$fetch->pmp_year_month]['point'][$fetch->pmp_monitor_id] = $fetch->pmp_point;
	$point_tbl[$fetch->pmp_year_month]['bonus'][$fetch->pmp_monitor_id] = $fetch->pmp_bonus;
}

if ($point_timing == 2) {
	// ポイント付与時期マスタ
	$sql = "SELECT * FROM m_mp_point_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$calc_date[$fetch->ppd_month] = $fetch->ppd_calc_date;
		$give_date[$fetch->ppd_month] = $fetch->ppd_give_date;
	}

	// 実施開始終了年月取得
	$sql = "SELECT ppj_room_start,ppj_room_end FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
	$start_ym = sprintf('%04d%02d', get_datepart('Y', $fetch->ppj_room_start), get_datepart('M', $fetch->ppj_room_start));
	$end_ym = sprintf('%04d%02d', get_datepart('Y', $fetch->ppj_room_end), get_datepart('M', $fetch->ppj_room_end));

	// 来月
//	$date = getdate();
//	$next_month = date('Ym', mktime(0, 0, 0, $date['mon'], 1, $date['year']));

//	if ($next_month < $end_ym)
//		$end_ym = $next_month;
?>
<!-- 月別 -->
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
	// ソート条件
	$order_by = order_by(1, 0, 'mn_monitor_id', 'mn_name1,mn_name2', "COALESCE((SELECT SUM(pmp_point+pmp_bonus) FROM t_mp_monitor_point WHERE pmp_pjt_id=$pjt_id AND pmp_monitor_id=pmn_monitor_id);0)");

	sort_header(1, 'ﾓﾆﾀｰID');
	sort_header(2, '名前');

	for ($ym = $start_ym, $column = 3; $ym <= $end_ym || $point_tbl[$ym]['calc_date']; $ym = add_month($ym), $column++) {
		sscanf($ym, '%4d%2d', $y, $m);

		if ($point_tbl[$ym]['calc_date']) {
			sscanf($point_tbl[$ym]['calc_date'], '%d-%d-%d', $calc_y, $calc_m, $calc_d);
		} else {
			$calc_y = $y;
			sscanf($calc_date[$m], '%2d%2d', $calc_m, $calc_d);
		}
		$calc_ymd = sprintf('%04d%02d%02d', $calc_y, $calc_m, $calc_d);
		$calc_md = sprintf('%d/%d', $calc_m, $calc_d);

		if ($point_tbl[$ym]['give_date']) {
			sscanf($point_tbl[$ym]['give_date'], '%d-%d-%d', $give_y, $give_m, $give_d);
		} else {
			$give_y = $y;
			sscanf($give_date[$m], '%2d%2d', $give_m, $give_d);
		}
		$give_ymd = sprintf('%04d%02d%02d', $give_y, $give_m, $give_d);
		$give_md = sprintf('%d/%d', $give_m, $give_d);
?>
		<th>
			<table class="small">
				<tr>
					<td colspan=2><?=$m?>月</td>
				<tr>
				</tr>
					<td><nobr>ポイント計算：<?=$calc_md?></nobr><br><nobr>ポイント付与：<?=$give_md?></nobr></td>
					<td>
<?
		if ($point_tbl[$ym]['fix_flag'] != 't') {
?>
						<input type="button" value="修正" onclick="date_edit('<?=$ym?>','<?=$calc_ymd?>','<?=$give_ymd?>')">
<?
		}
?>
					</td>
				</tr>
			</table>
		</th>
<?
	}
	sort_header(3, '合計');
?>
	</tr>
<?
	$total = 0;
	$sql = "SELECT mn_monitor_id,mn_name1,mn_name2"
			. " FROM t_mp_monitor"
			. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
			. " WHERE pmn_pjt_id=$pjt_id AND (pmn_admin_choice=2 OR pmn_admin_choice=3)"
			. " $order_by";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$monitor_id = $fetch->mn_monitor_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$monitor_id?></td>
		<td align="center"><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
<?
		$sum = 0;
		for ($ym = $start_ym; $ym <= $end_ym || $point_tbl[$ym]['calc_date']; $ym = add_month($ym)) {
			$point = $point_tbl[$ym]['point'][$monitor_id] + $point_tbl[$ym]['bonus'][$monitor_id];
			$sum += $point;
			$total += $point;
?>
		<td align="center"><?=$point_tbl[$ym]['calc_flag'] == 't' ? number_format($point) : '未'?><?=$point_tbl[$ym]['bonus'][$monitor_id] ? '（ボーナス設定済）' : ''?>
<?
			if ($point_bonus == 't' && $point_tbl[$ym]['fix_flag'] != 't') {
?>
			<input type="button" value="ボーナス設定" onclick="bonus_edit('<?=$ym?>','<?=$monitor_id?>')">
<?
			}
?>
		</td>
<?
		}
?>
		<td align="center"><?=number_format($sum)?></td>
	</tr>
<?
	}
?>
	<tr class="tc2">
		<td align="center"></td>
		<td align="center"></td>
<?
	for ($ym = $start_ym; $ym <= $end_ym || $point_tbl[$ym]['calc_date']; $ym = add_month($ym)) {
?>
		<td align="center">
<?
		if ($point_tbl[$ym]['give_flag'] == 't') {
?>
			付与済
<?
		} elseif ($point_tbl[$ym]['fix_flag'] == 't') {
?>
			<a href="javascript:kakutei_cancel(<?=$ym?>)">確定済み</a>
<?
		} elseif ($point_tbl[$ym]['calc_flag'] == 't') {
?>
			<input type="button" value="確定" onclick="kakutei(<?=$ym?>)">
<?
		} else {
?>
			<br>
<?
		}
?>
		</td>

<?
	}
?>
		<td align="center"><?=number_format($total)?></td>
	</tr>
</table>
<?
} elseif ($point_timing == 1) {
	$ym = '999999';

	if ($point_tbl[$ym]['calc_date']) {
		sscanf($point_tbl[$ym]['calc_date'], '%d-%d-%d', $calc_y, $calc_m, $calc_d);
		$calc_ymd = sprintf('%04d%02d%02d', $calc_y, $calc_m, $calc_d);
		$calc_md = sprintf('%d/%d', $calc_m, $calc_d);

		sscanf($point_tbl[$ym]['give_date'], '%d-%d-%d', $give_y, $give_m, $give_d);
		$give_ymd = sprintf('%04d%02d%02d', $give_y, $give_m, $give_d);
		$give_md = sprintf('%d/%d', $give_m, $give_d);
	} else {
		get_default_date($pjt_id, $calc_ymd, $give_ymd);

		sscanf($calc_ymd, '%4d%2d%2d', $y, $m, $d);
		$calc_md = sprintf('%2d/%2d', $m, $d);

		sscanf($give_ymd, '%4d%2d%2d', $y, $m, $d);
		$give_md = sprintf('%2d/%2d', $m, $d);
	}

	// ソート条件
	$order_by = order_by(1, 0, 'mn_monitor_id', 'mn_name1,mn_name2', "COALESCE((SELECT SUM(pmp_point+pmp_bonus) FROM t_mp_monitor_point WHERE pmp_pjt_id=$pjt_id AND pmp_year_month='$ym' AND pmp_monitor_id=pmn_monitor_id);0)");
?>
<!-- 一括 -->
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
	sort_header(1, 'ﾓﾆﾀｰID');
	sort_header(2, '名前');
?>
		<th>
			<table class="small">
				<tr>
					<td colspan=2>一括</td>
				<tr>
				</tr>
				<td><nobr>ポイント計算：<?=$calc_md?></nobr><br><nobr>ポイント付与：<?=$give_md?></nobr></td>
					<td>
<?
	if ($point_tbl[$ym]['fix_flag'] != 't') {
?>
					<input type="button" value="修正" onclick="date_edit('<?=$ym?>','<?=$calc_ymd?>','<?=$give_ymd?>')">
<?
	}
?>
					</td>
				</tr>
			</table>
		</th>
	</tr>
<?
	$total = 0;
	$sql = "SELECT mn_monitor_id,mn_name1,mn_name2"
			. " FROM t_mp_monitor"
			. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
			. " WHERE pmn_pjt_id=$pjt_id AND (pmn_admin_choice=2 OR pmn_admin_choice=3)"
			. " $order_by";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$monitor_id = $fetch->mn_monitor_id;
		$point = $point_tbl[$ym]['point'][$monitor_id] + $point_tbl[$ym]['bonus'][$monitor_id];
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$monitor_id?></td>
		<td align="center"><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<td align="center"><?=$point_tbl[$ym]['calc_flag'] == 't' ? number_format($point) : '未'?><?=$point_tbl[$ym]['bonus'][$monitor_id] ? '（ボーナス設定済）' : ''?>
<?
			if ($point_bonus == 't' && $point_tbl[$ym]['fix_flag'] != 't') {
?>
			<input type="button" value="ボーナス設定" onclick="bonus_edit('<?=$ym?>','<?=$monitor_id?>')">
<?
			}
?>
		</td>
	</tr>
<?
	}
?>
	<tr class="tc2">
		<td align="center"></td>
		<td align="center"></td>
		<td align="center">
<?
		if ($point_tbl[$ym]['give_flag'] == 't') {
?>
			付与済
<?
		} elseif ($point_tbl[$ym]['fix_flag'] == 't') {
?>
			<a href="javascript:kakutei_cancel(<?=$ym?>)">確定済み</a>
<?
		} elseif ($point_tbl[$ym]['calc_flag'] == 't') {
?>
			<input type="button" value="確定" onclick="kakutei(<?=$ym?>)">
<?
		} else {
?>
			<br>
<?
		}
?>
		</td>
	</tr>
</table>
<?
}
?>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
</form>
<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
