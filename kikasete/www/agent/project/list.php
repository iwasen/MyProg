<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:メッセージ送信・履歴の閲覧・削除
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");
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

//メイン処理
set_global2('project', 'プロジェクト管理', 'プロジェクト一覧', BACK_TOP);

// セッション登録
get_session_vars($pset, 'project', 'client_id', 'tantousha_id', 'displine', 'sort_col', 'sort_dir', 'page');
$_SESSION['ss_pjt_list'] = 1;

if ($tantousha_id != '')
	and_join($where, "ts_tantousha_id='$tantousha_id'");

if ($client_id != '')
	and_join($where, "cl_client_id=$client_id");

if ($where)
	$where = "WHERE $where";

// ソート条件
$order_by = order_by(5, 1, 'cl_client_id,ts_tantousha_id,pj_project_id', 'cl_name,ts_tantousha_id,pj_project_id', 'ts_tantousha_id,pj_project_id', 'ts_name1_kana||ts_name2_kana,pj_project_id', 'pj_project_id', 'pj_project_name');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT cl_client_id,cl_name,ts_tantousha_id,ts_name1,ts_name2,pj_project_id,pj_project_name"
		. " FROM t_project"
		. " JOIN m_tantousha ON ts_tantousha_id=pj_tantousha_id"
		. " JOIN m_client ON cl_client_id=ts_client_id"
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
function onchange_client_id() {
	document.form1.tantousha_id.value = '';
	document.form1.submit();
}
function onclick_client_id(cl_id) {
	var url;
	url = "client/cl_info.php?cl_id=" + cl_id;
	window.open(url, "cl_info", "resizable=yes,scrollbars=yes,width=600,height=320");
}
function onclick_tantousha_id(ts_id) {
	var url;
	url = "client/ts_info.php?ts_id=" + ts_id;
	window.open(url, "ts_info", "resizable=yes,scrollbars=yes,width=600,height=400");
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
		<td>■プロジェクト一覧</td>
		<td class="lb">
			<input type="button" value="新規企業登録" onclick="location.href='client/cl_new.php?back_url=<?=$REQUEST_URI?>'">
			<input type="button" value="新規ﾌﾟﾛｼﾞｪｸﾄ登録" onclick="location.href='sel_client.php'">
<?
if (SERVICE == 'admin') {
?>
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
<?
}
?>
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>企業<select name="client_id" onchange="onchange_client_id()"><? select_client($client_id) ?></select>&nbsp</nobr>
			<nobr>担当者<select name="tantousha_id" onchange="submit()"><? select_tantousha($tantousha_id, $client_id) ?></select>&nbsp</nobr>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, '企業ID');
sort_header(2, '企業名');
sort_header(3, '担当者ID');
sort_header(4, '担当者名');
sort_header(5, 'ﾌﾟﾛｼﾞｪｸﾄID');
sort_header(6, "ﾌﾟﾛｼﾞｪｸﾄ名");
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="javascript:onclick_client_id(<?=$fetch->cl_client_id?>)" title="企業情報を表示します"><?=$fetch->cl_client_id?></a></td>
		<td align="left"><a href="client/ts_new.php?cl_id=<?=$fetch->cl_client_id?>" title="担当者を登録します"><?=htmlspecialchars($fetch->cl_name)?></td>
		<td align="center"><a href="javascript:onclick_tantousha_id('<?=$fetch->ts_tantousha_id?>')" title="企業担当者情報を表示します"><?=$fetch->ts_tantousha_id?></a></td>
		<td align="left"><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></td>
		<td align="center"><a href="pj_edit.php?pjt_id=<?=$fetch->pj_project_id?>" title="プロジェクトの登録内容を更新します"><?=$fetch->pj_project_id?></a></td>
		<td align="left"><a href="job/list.php?pjt_id=<?=$fetch->pj_project_id?>" title="ジョブの一覧を表示します"><?=htmlspecialchars($fetch->pj_project_name)?></a></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
