<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ジョブ一覧
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/list.php");

//表示カラー
function value_color($id) {
	global $agent_id;
	if ($id == $agent_id)
		echo 2;
	else
		echo 1;
}
//期間表示
function format_date2($from,$to,$def = '未設定') {
	if ($from && $to)
		return date('Y/m/d', sql_time($from)).'〜'.date('m/d', sql_time($to));
	else
		return $def;
}

//=== 発信日選択肢 ===
function select_send_date($send_date) {
	echo "<option value=''>- 指定なし -</option>\n";

	$y = (int)Date('Y');
	$m = (int)Date('m');

	do {
		$m2 = sprintf('%02d', $m);
		$ym = "$y$m";
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
function decode_enquete_status($status) {
	global $job_id;

	switch ($status) {
	case '0':
		return '<font color="red">未</font>';
	Case '1':
		return '<font color="green">待</font>';
	Case '2':
		return '<a href="status.asp?job_id=$job_id" title="未承認状態に変更します">済</a>';
	}
}

//メイン処理
set_global2('project', 'プロジェクト管理', 'ジョブ一覧', BACK_TOP);

// セッション登録
get_session_vars($pset, 'job', 'displine', 'sort_col', 'sort_dir', 'page');

// 戻り先チェック
switch ($_SESSION['ss_pjt_list']) {
case 2:
	redirect('../list2.php');
case 3:
	redirect('../approval/list.php');
}

// プロジェクトID取得
if ($pjt_id == '') {
	$pjt_id = $_SESSION['ss_pjt_id'];
	if ($pjt_id == '')
		redirect('../list.php');
} else
	$_SESSION['ss_pjt_id'] = $pjt_id;

// プロジェクト情報取得
$sql = "SELECT pj_project_name FROM t_project WHERE pj_project_id=$pjt_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$project_name = $fetch->pj_project_name;
}

// ソート条件
$order_by = order_by(1, 0, 'jb_job_id', 'COALESCE(jb_cp_job_id;jb_job_id),jb_job_id', 'jb_job_name', 'en_enq_type', 'COALESCE(jb_point_jushin;0)', 'COALESCE(en_point;0)', 'jb_start_date', 'en_end_date', 'COALESCE(jb_send_num;0)', 'jb_real_send', 'jb_enq_count', 'cc_count', 'jb_send_status', 'en_status');
$order_by = str_replace(';', ',', $order_by);

$sub1 = "SELECT COALESCE(SUM(cc_click_num),0) FROM t_click_counter WHERE cc_job_id=jb_job_id";

$sql = "SELECT jb_job_id,jb_cp_job_id,jb_job_name,en_enq_type,jb_point_jushin,en_point,jb_start_date,jb_end_date,en_end_date,jb_send_num,jb_real_send,jb_enq_count,($sub1) as cc_count,jb_send_status,en_status,EXISTS (SELECT * FROM t_job AS jb2 WHERE jb1.jb_job_id=jb2.jb_cp_job_id) AS jb_parent"
		. " FROM t_job AS jb1 LEFT JOIN t_enquete ON jb_enquete_id=en_enquete_id"
		. " WHERE jb_project_id=$pjt_id"
		. " $order_by";
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
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td>■ジョブ一覧</td>
		<td class="lb">
			<input type="button" value="ﾒﾝﾊﾞｰﾘｽﾄ作成" onclick="location.href='member_list/list.php'">
			<input type="button" value="内容・差分表示" onclick="location.href='job_list.php?project_id=<?=$pjt_id?>'">
			<input type="button" value="新規ｼﾞｮﾌﾞ登録" onclick="location.href='jb_new.php?pjt_id=<?=$pjt_id?>'">
			<input type="button" value="　戻る　" onclick="location.href='../list.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			プロジェクトID：<?=$pjt_id?>　プロジェクト名：<?=htmlspecialchars($project_name)?>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="id" <?=value($id)?>>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width=100% class="small">
	<tr class="tch">
<?
sort_header(1, 'ｼﾞｮﾌﾞID');
sort_header(2, '親ｼﾞｮﾌﾞID');
sort_header(3, 'ジョブ名');
sort_header(4, '種類');
sort_header(5, "受信<br>ﾎﾟｲﾝﾄ");
sort_header(6, "ｱﾝｹｰﾄ<br>ﾎﾟｲﾝﾄ");
sort_header(7, '送信期間');
sort_header(8, '締切り');
sort_header(9, '発信上限');
sort_header(10, '発信数');
sort_header(11, '返信数');
sort_header(12, 'ｸﾘｯｸ数');
sort_header(13, '発信状態');
sort_header(14, '承認');
?>
	</tr>
<?
$tcn = 2;

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$job_id = $fetch->jb_job_id;

	// 親ジョブでソート時の色分け
	$cp_job_id = $fetch->jb_cp_job_id;
	if ($sort_col == 2) {
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
		<td align="center"><a href="jb_edit.php?pjt_id=<?=$pjt_id?>&job_id=<?=$job_id?>" title="ジョブの登録内容を更新します"><?=$job_id?></a></td>
		<td align="center"><?=$cp_job_id?></td>
		<td align="left"><a href="jb_contents.php?job_id=<?=$job_id?>" title="ジョブの詳細情報を登録します"><?=htmlspecialchars($fetch->jb_job_name)?></a></td>
		<td align="center"><?=decode_enq_type2($fetch->en_enq_type)?></td>
		<td align="right"><?=number_format($fetch->jb_point_jushin)?></td>
		<td align="right"><?=number_format($fetch->en_point)?></td>
		<td align="center"><?=format_date2($fetch->jb_start_date,$fetch->jb_end_date)?></td>
		<td align="center"><?=format_date($fetch->en_end_date,'未設定')?></td>
		<td align="right"><?=number_format($fetch->jb_send_num)?></td>
<?
	if ($fetch->jb_real_send) {
?>
		<td align="right"><a href="send/index.php?job_id=<?=$job_id?>" title="発信者情報を表示します"><?=number_format($fetch->jb_real_send)?></a></td>
<?
} else {
?>
		<td align="right"><?=number_format($fetch->jb_real_send)?></td>
<?
}
if ($fetch->jb_enq_count) {
?>
		<td align="right"><a href="recv/index.php?job_id=<?=$job_id?>" title="アンケートレスポンスを表示します"><?=number_format($fetch->jb_enq_count)?></a></td>
<?
} else {
?>
		<td align="right"><?=number_format($fetch->jb_enq_count)?></td>
<?
}
if ($fetch->cc_count) {
?>
		<td align="right"><a href="click/index.php?job_id=<?=$job_id?>" title="クリック解析を表示します"><?=number_format($fetch->cc_count)?></a></td>
<?
} else {
?>
		<td align="right"><?=number_format($fetch->cc_count)?></td>
<?
}
?>
		<td align="center"><?=decode_send_status($fetch->jb_send_status)?></td>
		<td align="center"><?=decode_enq_status($fetch->en_status)?></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
