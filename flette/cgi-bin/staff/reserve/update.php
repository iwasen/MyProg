#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:予約可能時間帯更新
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

db_begin_trans();

$sql = "SELECT wk_room_id,wk_course_id,wk_start_time,wk_end_time,wk_sex_cd"
		. " FROM t_waku"
		. " JOIN m_room ON rm_room_id=wk_room_id"
		. " WHERE rm_shop_id=$shop_id AND rm_status=1 AND wk_date=" . sql_date($date)
		. " ORDER BY wk_date,wk_start_time";
$result = db_exec($sql);
$nrow = pg_numrows($result);
$flag_ary = array();
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$flag_ary[] = "$fetch->wk_room_id,$fetch->wk_course_id,$fetch->wk_start_time,$fetch->wk_end_time,$fetch->wk_sex_cd";
}

foreach ($start_hour as $room_id => $value1) {
	foreach (array_keys($value1) as $waku_no) {
		$start_time[$room_id][$waku_no] = "{$start_hour[$room_id][$waku_no]}:{$start_minute[$room_id][$waku_no]}:00";
		$end_time[$room_id][$waku_no] = "{$end_hour[$room_id][$waku_no]}:{$end_minute[$room_id][$waku_no]}:00";
	}
}

$delete_flag = $flag_ary;
$insert_flag = array();

if (is_array($flag)) {
	foreach ($flag as $room_id => $value1) {
		foreach ($value1 as $waku_no => $value2) {
			foreach ($value2 as $course_id => $value3) {
				foreach ($value3 as $sex_cd => $value4) {
					if ($value4 == 1 && $start_hour[$room_id][$waku_no] != '') {
						$flag = "$room_id,$course_id,{$start_time[$room_id][$waku_no]},{$end_time[$room_id][$waku_no]},$sex_cd";
						$index = array_search($flag, $flag_ary);
						if ($index === false)
							$insert_flag[] = $flag;
						else
							unset($delete_flag[$index]);
					}
				}
			}
		}
	}
}

foreach ($delete_flag as $data) {
	$ary = explode(',', $data);
	db_delete('t_waku',
			sprintf("wk_room_id=%s AND wk_course_id=%s AND wk_date=%s AND wk_start_time=%s AND wk_end_time=%s AND wk_sex_cd=%s",
			sql_number($ary[0]),
			sql_number($ary[1]),
			sql_date($date),
			sql_date($ary[2]),
			sql_date($ary[3]),
			sql_number($ary[4])));
}

foreach ($insert_flag as $data) {
	$ary = explode(',', $data);
	$rec['wk_room_id'] = sql_number($ary[0]);
	$rec['wk_course_id'] = sql_number($ary[1]);
	$rec['wk_date'] = sql_date($date);
	$rec['wk_start_time'] = sql_date($ary[2]);
	$rec['wk_end_time'] = sql_date($ary[3]);
	$rec['wk_sex_cd'] = sql_number($ary[4]);
	db_insert('t_waku', $rec);
}

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header('予約可能時間帯設定完了', true, true) ?>

<div align="center">
<p class="msg">予約可能時間帯を登録しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='monthly.php?shop_id=<?=$shop_id?>'"></p>
</div>

<? page_footer() ?>

</body>
</html>
