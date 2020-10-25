<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・リクルート中一覧
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/my_partner.php");
include("$inc/icp_db.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ポイント付与時期
function decode_point_timing($code) {
	switch ($code) {
	case 1:
		return '一括';
	case 2:
		return '月別';
	}
}

// ポイント付与基本ルール
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

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　稼動中会議室一覧', BACK_TOP);

// 戻り先設定
set_return_page('edit.php');
set_return_page('update.php');
set_return_page('index.php');
set_return_page('r_job.php');
set_return_page('pjt_detail.php');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir, index) {
	var f = document.form1;
	f["sort_col[" + index + "]"].value = sort;
	f["sort_dir[" + index + "]"].value = dir;
	f.submit();
}
function OnChange_client_id() {
	document.form1.tantousha_id.value = '';
	document.form1.submit();
}
function onclick_project(project_id) {
	var win;
	win = window.open("project_info.php?project_id=" + project_id, "project_info", "resizable=yes,width=600,height=400");
	win.focus();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<?
// ソート条件
$order_by = order_by2(1, 0, 1, 'ppj_pjt_id', 'ppj_room_name', 'mr_kinmu_name', 'mr_name1_kana,mr_name2_kana', 'ppj_recruit_start', 'ppj_recruit_end', 'remain_days', 'ppj_send_num', 'reply_count');
?>
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■リクルート中一覧</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='<?=get_return_page()?>'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col[1]" <?=value($sort_col[1])?>>
<input type="hidden" name="sort_dir[1]" <?=value($sort_dir[1])?>>
<input type="hidden" name="pset" value=1>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header2(1, 'ID', 1);
sort_header2(2, '会議室名', 1);
sort_header2(3, '企業名', 1);
sort_header2(4, 'ﾏｰｹﾀｰ名', 1);
sort_header2(5, '開始日', 1);
sort_header2(6, '終了日', 1);
sort_header2(7, '終了まで', 1);
sort_header2(8, '最大発信数', 1);
sort_header2(9, '返信数', 1);
sort_header2(0, '該当者数', 1);
sort_header2(0, '会議室', 1);
sort_header2(0, 'ポイント付与', 1);
?>
	</tr>
<?
$sb1 = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=ppj_pjt_id";
$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_kinmu_name,mr_name1,mr_name2,ppj_recruit_start,ppj_recruit_end,EXTRACT(DAY FROM (date_trunc('day',ppj_recruit_end)-current_date::timestamp))+1 AS remain_days,ppj_send_num,($sb1) AS reply_count,ppj_status,ppj_point_timing,mr_marketer_id,ppj_select_sql"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_status=" . MPS_REC_JISSHI
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;

	// 該当者数取得
	if ($fetch->ppj_select_sql != '') {
		$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id AND $fetch->ppj_select_sql";
		$sel_count = db_fetch1($sql);
	} else
		$sel_count = '-';
?>
	<tr class="tc<?=$i % 2?>" align="center">
		<td><?=$pjt_id?></td>
		<td><a href="r_job.php?pjt_id=<?=$pjt_id?>" title="リクルートジョブの内容を表示します"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td><a href="marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="マーケターの情報を表示します" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td><?=format_date($fetch->ppj_recruit_start)?></td>
		<td><?=format_date($fetch->ppj_recruit_end)?></td>
		<td><?=number_format($fetch->remain_days)?>日</td>
		<td><?=number_format($fetch->ppj_send_num)?></td>
		<td><?=number_format($fetch->reply_count)?></td>
		<td><?=$sel_count?></td>
		<td><?=decode_room_status($fetch->ppj_status)?></td>
<?
	if (!$fetch->ppj_point_timing) {
?>
		<td><input type="button" value="登録" onclick="location.href='../point/edit.php?pjt_id=<?=$pjt_id?>'"></td>
<?
	} else {
?>
		<td>設定済み</td>
<?
	}
?>
	</tr>
<?
}
?>
</table>
<br>

<?
// ソート条件
$order_by = order_by2(1, 0, 2, 'ppj_pjt_id', 'ppj_room_name', 'mr_kinmu_name', 'mr_name1_kana,mr_name2_kana', 'ppj_decision_date', 'ppj_room_start', 'ppj_room_end', 'ppj_status');
?>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■本日決定日ジョブ一覧</td>
	</tr>
</table>
<input type="hidden" name="sort_col[2]" <?=value($sort_col[2])?>>
<input type="hidden" name="sort_dir[2]" <?=value($sort_dir[2])?>>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header2(1, 'ID', 2);
sort_header2(2, '会議室名', 2);
sort_header2(3, '企業名', 2);
sort_header2(4, 'ﾏｰｹﾀｰ名', 2);
sort_header2(5, '決定日', 2);
sort_header2(6, '開始日', 2);
sort_header2(7, '終了日', 2);
sort_header2(8, 'ステータス', 2);
?>
	</tr>
<?
$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_kinmu_name,mr_name1,mr_name2,ppj_decision_date,ppj_room_start,ppj_room_end,ppj_status,mr_marketer_id"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_decision_date=current_date::timestamp"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i % 2?>" align="center">
		<td><?=$pjt_id?></td>
		<td><a href="pjt_detail.php?pjt_id=<?=$pjt_id?>" title="実施ジョブの内容を表示します"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td><a href="marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="マーケターの情報を表示します" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td><?=format_date($fetch->ppj_decision_date)?></td>
		<td><?=format_date($fetch->ppj_room_start)?></td>
		<td><?=format_date($fetch->ppj_room_end)?></td>
		<td><?=decode_mypartner_status($fetch->ppj_status)?></td>
	</tr>
<?
}
?>
</table>
<br>

<?
// ソート条件
$order_by = order_by2(1, 0, 3, 'ppj_pjt_id', 'ppj_room_name', 'mr_kinmu_name', 'mr_name1_kana,mr_name2_kana', 'ppj_room_start', 'ppj_room_end', 'remain_days');
?>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■開催中会議室一覧</td>
	</tr>
</table>
<input type="hidden" name="sort_col[3]" <?=value($sort_col[3])?>>
<input type="hidden" name="sort_dir[3]" <?=value($sort_dir[3])?>>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header2(1, 'ID', 3);
sort_header2(2, '会議室名', 3);
sort_header2(3, '企業名', 3);
sort_header2(4, 'ﾏｰｹﾀｰ名', 3);
sort_header2(5, '開始日', 3);
sort_header2(6, '終了日', 3);
sort_header2(7, '終了まで', 3);
sort_header2(0, '未回答問い合わせ（総問い合わせ数）', 3);
sort_header2(0, 'ポイント付与', 3);
?>
	</tr>
<?
$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_kinmu_name,mr_name1,mr_name2,ppj_room_start,ppj_room_end,EXTRACT(DAY FROM (date_trunc('day',ppj_room_end)-current_date::timestamp))+1 AS remain_days,ppj_point_timing,mr_marketer_id,ppj_icp_job_id"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_status IN (" . MPS_MEM_CHECK . "," . MPS_ROOM_JISSHI . ") OR ppj_decision_date=current_date::timestamp"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;

	if ($fetch->ppj_icp_job_id) {
		// 未回答問い合わせ数取得
		$sql = "SELECT COUNT(*) FROM l_remark WHERE rm_job_id=$fetch->ppj_icp_job_id AND rm_remark_type=5 AND rm_child_flag=false";
		$inquiry_count1 = icp_db_fetch1($sql);

		// 総問い合わせ数取得
		$sql = "SELECT COUNT(*) FROM l_remark WHERE rm_job_id=$fetch->ppj_icp_job_id AND rm_remark_type=5";
		$inquiry_count2 = icp_db_fetch1($sql);
	} else {
		$inquiry_count1 = 0;
		$inquiry_count2 = 0;
	}
?>
	<tr class="tc<?=$i % 2?>" align="center">
		<td><?=$pjt_id?></td>
		<td><a href="pjt_job.php?pjt_id=<?=$pjt_id?>" title="実施ジョブの内容を表示します"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td><a href="marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="マーケターの情報を表示します" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td><?=format_date($fetch->ppj_room_start)?></td>
		<td><?=format_date($fetch->ppj_room_end)?></td>
		<td><?=number_format($fetch->remain_days)?>日</td>
		<td><?=$inquiry_count1?>(<?=$inquiry_count2?>)</td>
<?
	if (!$fetch->ppj_point_timing) {
?>
		<td><input type="button" value="登録" onclick="location.href='../point/edit.php?pjt_id=<?=$pjt_id?>'"></td>
<?
	} else {
?>
		<td>設定済み</td>
<?
	}
?>
	</tr>
<?
}
?>
</table>
<br>

<?
// ソート条件
$order_by = order_by2(1, 0, 4, 'ppj_pjt_id', 'ppj_room_name', 'mr_kinmu_name', 'mr_name1_kana,mr_name2_kana', 'point_sum', 'point_date', 'ppj_point_timing', 'ppj_point_rule', 'ppj_point_bonus');
?>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■ポイント付与予定一覧</td>
	</tr>
</table>
<input type="hidden" name="sort_col[4]" <?=value($sort_col[4])?>>
<input type="hidden" name="sort_dir[4]" <?=value($sort_dir[4])?>>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header2(1, 'ID', 4, 'rowspan=2');
sort_header2(2, '会議室名', 4, 'rowspan=2');
sort_header2(3, '企業名', 4, 'rowspan=2');
sort_header2(4, 'ﾏｰｹﾀｰ名', 4, 'rowspan=2');
sort_header2(5, '付与ポイント合計', 4, 'rowspan=2');
sort_header2(6, '付与日', 4, 'rowspan=2');
sort_header2(0, '条件', 4, 'colspan=3');
?>
	</tr>
	<tr class="tch">
<?
sort_header2(7, '時期', 4);
sort_header2(8, '基準', 4);
sort_header2(9, 'ボーナス', 4);
?>
	</tr>
<?
$sb1 = "SELECT sum(pmp_point+pmp_bonus) FROM t_mp_monitor_point WHERE pmp_pjt_id=ppj_pjt_id AND pmp_year_month=ppl_year_month";
$sb2 = "SELECT ppl_pjt_id AS sb2_pjt_id,min(CASE WHEN ppl_year_month='999999' THEN '000000' ELSE ppl_year_month END) AS sb2_year_month FROM t_mp_point_log WHERE ppl_give_flag=false GROUP BY ppl_pjt_id";
$sb3 = "SELECT ppl_pjt_id,ppl_give_date AS point_date,ppl_year_month FROM t_mp_point_log JOIN ($sb2) sb2 ON ppl_pjt_id=sb2_pjt_id AND ppl_year_month=CASE WHEN sb2_year_month='000000' THEN '999999' ELSE sb2_year_month END";
$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_kinmu_name,mr_name1,mr_name2,($sb1) AS point_sum,point_date,ppj_point_timing,ppj_point_rule,ppj_point_bonus,mr_marketer_id"
		. " FROM ($sb3) point_log"
		. " JOIN t_mp_pjt ON ppj_pjt_id=ppl_pjt_id"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_status>=" . MPS_ROOM_JISSHI . " AND (ppj_point_timing=2 OR ppl_year_month='999999')"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i % 2?>" align="center">
		<td><?=$pjt_id?></td>
		<td><a href="../point/index.php?pjt_id=<?=$pjt_id?>" title="ポイント付与の内容を表示します"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td><a href="marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="マーケターの情報を表示します" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td><?=number_format($fetch->point_sum)?></td>
		<td><?=format_date($fetch->point_date)?></td>
		<td><?=decode_point_timing($fetch->ppj_point_timing)?></td>
		<td><?=decode_point_rule($fetch->ppj_point_rule)?></td>
		<td><?=$fetch->ppj_point_bonus == DBTRUE ? '有り' : '無し'?></td>
	</tr>
<?
}
?>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
