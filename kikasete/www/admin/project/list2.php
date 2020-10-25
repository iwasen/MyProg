<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:プロジェクト進行管理リスト表示
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
include("$inc/format.php");
include("$inc/list.php");

//=== 企業選択 ===
function select_client($selected) {
	echo "<option value=''>- 指定なし -</option>\n";

	$sql = "SELECT cl_client_id,cl_name FROM m_client ORDER BY cl_client_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->cl_client_id, $selected), '>', htmlspecialchars($fetch->cl_name), '</option>', "\n";
	}
}

//=== 企業担当者選択 ===
function select_tantousha($selected, $client_id) {
	echo "<option value=''>- 指定なし -</option>\n";

	if ($client_id != '')
		$where = "WHERE ts_client_id=$client_id";

	$sql = "SELECT ts_tantousha_id,ts_name1,ts_name2 FROM m_tantousha $where ORDER BY ts_tantousha_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ts_tantousha_id, $selected), '>', htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2"), '</option>', "\n";
	}
}

//=== 発信日選択肢 ===
function select_send_date($send_date) {
	echo "<option value=''>- 指定なし -</option>\n";

	$y = (int)Date('Y');
	$m = (int)Date('m');

	do {
		$ym = sprintf('%04d%02d', $y, $m);
		echo '<option ', value_selected($ym, $send_date), ">$y/$m</option>\n";
		$m--;
		if ($m == 0) {
			$m = 12;
			$y--;
		}
	} while ($ym >= "200201");
}

//=== 発信状態表示 ===
function decode_send_status($status) {
	switch ($status) {
	case '0':
		return '未送信';
	case '1':
		return '<nobr>送信<wbr>待機</nobr>';
	case '2':
		return '送信中';
	case '9':
		return '<nobr>送信<wbr>終了</nobr>';
	}
	return '-';
}

//=== 承認状態表示 ===
function decode_enquete_status($status, $job_id) {
	global $job_id;

	switch ($status) {
	case '0':
	case '1':
		return '<font color="red">未</font>';
	case '2':
	case '3':
		return '<font color="green">待</font>';
	default:
		return '<a href="job/status/index.php?job_id=' . $job_id . '" title="未承認状態に変更します">済</a>';
	}
}

//=== メール種類名取得 ===
function decode_mail_type($mail_type) {
	switch ($mail_type) {
	case '1':
		return 'メール';
	case '2':
		return 'Web';
	}
	return '不明';
}

//期間表示
function format_date2($from, $to, $def = '未設定') {
	if ($from && $to)
		return date('Y/m/d', sql_time($from)).'〜'.date('m/d', sql_time($to));
	else
		return $def;
}

//メイン処理
set_global2('project', 'プロジェクト管理', 'プロジェクト進行状況', BACK_TOP);

// セッション登録
get_session_vars($pset, 'pjt_go', 'client_id', 'tantousha_id', 'send_date', 'displine', 'sort_col', 'sort_dir', 'page');
$_SESSION['ss_pjt_list'] = 2;

if ($send_date != '')
	and_join($where, "pj_project_id IN (SELECT DISTINCT jb_project_id FROM t_job WHERE TO_CHAR(jb_start_date,'YYYYMM')='$send_date')");

if ($tantousha_id != '')
	and_join($where, "pj_tantousha_id='$tantousha_id'");

If ($client_id != '') {
	$from .= "LEFT JOIN m_tantousha ON pj_tantousha_id=ts_tantousha_id";
	and_join($where, "ts_client_id=$client_id");
}

if ($where)
	$where = "WHERE $where";

// ソート条件
$order_by = order_by(1, 1, 'pj_project_id,jb_job_id', 'pj_project_name,jb_job_id', 'jb_job_id', 'pj_project_id,COALESCE(jb_cp_job_id,jb_job_id),jb_job_id', 'jb_job_name', 'COALESCE(jb_point_jushin;0)', 'COALESCE(en_point;0)', 'jb_start_date', 'en_end_date', 'COALESCE(jb_send_num;0)', 'jb_real_send', 'jb_enq_count', 'COALESCE(cc_count;0)', 'jb_send_status', 'en_status');
$order_by = str_replace(';', ',', $order_by);

// 表示行数条件
$limit = disp_limit();

$sub1 = "SELECT cc_job_id,SUM(cc_click_num) AS cc_count FROM t_click_counter GROUP BY cc_job_id";

$sql = "SELECT pj_project_id,pj_project_name,jb_job_id,jb_job_name,jb_start_date,jb_end_date,en_end_date,jb_send_num,jb_point_jushin,jb_send_status,jb_real_send,jb_enq_count,jb_cp_job_id,cc_count,en_status,en_point,EXISTS (SELECT * FROM t_job AS jb2 WHERE jb1.jb_job_id=jb2.jb_cp_job_id) AS jb_parent"
		. " FROM t_project"
		. " LEFT JOIN t_job AS jb1 ON pj_project_id=jb_project_id"
		. " LEFT JOIN t_enquete ON jb_enquete_id=en_enquete_id"
		. " LEFT JOIN ($sub1) AS sub1 ON jb_job_id=CC_job_id $from"
		. " $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);
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
</head>
<body>
<? page_header() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■プロジェクト／ジョブ一覧</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>企業<select name="client_id" onchange="OnChange_client_id()"><? select_client($client_id) ?></select>&nbsp</nobr>
			<nobr>担当者<select name="tantousha_id" onchange="submit()"><? select_tantousha($tantousha_id, $client_id) ?></select>&nbsp</nobr>
			<nobr>発信日<select name="send_date" onchange="submit()"><? select_send_date($send_date) ?></select>&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>


<table <?=LIST_TABLE?> width="100%" class="small2">
	<tr class="tch">
<?
sort_header(1, 'ﾌﾟﾛｼﾞｪｸﾄID');
sort_header(2, 'ﾌﾟﾛｼﾞｪｸﾄ名');
sort_header(3, 'ｼﾞｮﾌﾞID');
sort_header(4, '親ｼﾞｮﾌﾞID');
sort_header(5, 'ｼﾞｮﾌﾞ名');
sort_header(6, '送信ﾎﾟｲﾝﾄ');
sort_header(7, '返信ﾎﾟｲﾝﾄ');
sort_header(8, '送信期間');
sort_header(9, '締切り');
sort_header(10, '最大発信数');
sort_header(11, '発信数');
sort_header(12, '返信数');
sort_header(13, 'ｸﾘｯｸ数');
sort_header(14, '<nobr>発信<wbr>状態</nobr>');
sort_header(15, '承認');
?>
	</tr>
<?
$same_project = new join_same_item;
$same_project->prepare($result, 'pj_project_id');

$tcn = 2;

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$job_id = $fetch->jb_job_id;
	$row_count = $same_project->get_row_count();

	// 親ジョブでソート時の色分け
	$cp_job_id = $fetch->jb_cp_job_id;
	if ($sort_col == 4) {
		if (!$cp_job_id) {
			if ($fetch->jb_parent == 't') {
				$tcn = ($tcn == 0) ? 2 : 0;
				$tc = "tc$tcn";
			} else
				$tc = "tc1";
		}
	} else
		$tc = 'tc' . $i % 2;

	// 親ジョブID表示
	if (!$cp_job_id) {
		if ($fetch->jb_parent == 't')
			$cp_job_id = '（親）';
		else
			$cp_job_id = '';
	}
?>
	<tr class="<?=$tc?>">
<?
	$project_id = $fetch->pj_project_id;
	if ($row_count > 0) {
?>
		<td rowspan=<?=$row_count?> class="tc0" align="center"><a href="JavaScript:onclick_project(<?=$project_id?>)" title="プロジェクト情報を表示します"><?=$project_id?></a></td>
		<td rowspan=<?=$row_count?> class="tc0"><a href="job/job_list.php?project_id=<?=$project_id?>" title="プロジェクト内ジョブ一覧を表示します"><?=htmlspecialchars($fetch->pj_project_name)?></a></td>
<?
	}

	if (!$job_id) {
?>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
<?
	} else {
?>
		<td align="center"><a href="job/jb_edit.php?pjt_id=<?=$project_id?>&job_id=<?=$job_id?>" title="ジョブの登録内容を更新します"><?=$job_id?></a></td>
		<td align="center"><?=$cp_job_id?></td>
		<td><a href="job/jb_contents.php?job_id=<?=$job_id?>" title="ジョブ内容を表示・変更します"><?=htmlspecialchars($fetch->jb_job_name)?></a></td>
		<td align="right"><?=number_format($fetch->jb_point_jushin)?></td>
		<td align="right"><?=number_format($fetch->en_point)?></td>
		<td align="center"><?=format_date2($fetch->jb_start_date, $fetch->jb_end_date)?></td>
		<td align="center"><?=format_date($fetch->en_end_date)?></td>
		<td align="right"><?=number_format($fetch->jb_send_num)?></td>

<?	if ($fetch->jb_real_send) { ?>
		<td align="right"><a href="job/send/index.php?job_id=<?=$fetch->jb_job_id?>" title="発信者情報を表示します"><?=number_format($fetch->jb_real_send)?></a></td>
<?	} else { ?>
			<td align="right"><?=number_format($fetch->jb_real_send)?></td>
<?	} ?>

<?	if ($fetch->jb_enq_count > 0) { ?>
		<td align="right"><a href="job/recv/index.php?job_id=<?=$fetch->jb_job_id?>" title="アンケートレスポンスを表示します"><?=number_format($fetch->jb_enq_count)?></a></td>
<?	} else { ?>
		<td align="right"><?=number_format($fetch->jb_enq_count)?></td>
<?	} ?>

<?	if ($fetch->cc_count > 0) { ?>
		<td align="right"><a href="job/click/index.php?job_id=<?=$fetch->jb_job_id?>" title="クリック解析を表示します"><?=number_format($fetch->cc_count)?></a></td>
<?	} else { ?>
		<td align="right"><?=number_format($fetch->cc_count)?></td>
<?	} ?>

		<td align="center"><?=decode_send_status($fetch->jb_send_status)?></td>
		<td align="center"><?=decode_enquete_status($fetch->en_status, $job_id)?></td>
<?
	}
?>
	</tr>
<?
}
?>
</table>

<? page_footer() ?>
</body>
</html>
