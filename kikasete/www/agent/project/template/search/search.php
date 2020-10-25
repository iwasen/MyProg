<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:人数検索
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/search.php");
include("$inc/decode.php");

//メイン処理

set_global2('project', 'プロジェクト管理｜テンプレート編集', '人数検索', BACK_TOP);

$search = new search_class;

if ($ps_search_id) {
	$search_id = $ps_search_id;
	$search->read_db($ps_search_id);
} else
	$search->get_form();

$sql = 'SELECT COUNT(*) FROM t_monitor WHERE mn_status=0';
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$all = $fetch[0];
}

$sql = 'SELECT COUNT(*) FROM t_monitor WHERE ' . $search->make_sql();
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$find = $fetch[0];
}
$condition = $search->get_condition();

//$search->read_db($search_id);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<SCRIPT LANGUAGE=javascript>
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function OnClick_job_id(job_id) {
	opener.CopyJob(job_id);
	close();
}
//-->
</SCRIPT>
</head>
<body>
	<center>
		<table border=0 cellspacing=2 cellpadding=3>
			<tr>
				<td>
					<table border=0 cellspacing=2 cellpadding=3 width=700>
						<tr>
							<td class="m4" align="center"><?=$g_title?></td>
						</tr>
					</table>
					<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
						<tr>
							<td class="m0" colspan=2 align="left">■検索結果</td>
						</tr>
						<tr>
							<td class="m2" width="25%">検索されたモニター数</td>
							<td class="n2"><?=number_format($find)?></td>
						</tr>
						<tr>
							<td class="m2">全有効モニター数</td>
							<td class="n2"><?=number_format($all)?></td>
						</tr>
						<tr>
							<td class="m2">検索された割合</td>
							<td class="n2"><?=number_format($find / $all * 100, 1)?>%</td>
						</tr>
						<tr>
							<td class="m2">検索条件</td>
							<td class="n2"><font size="-1"><?=str_replace("\n", '<br>', $condition)?></font></td>
						</tr>
					</table><br>
					<table class="small" border=0 cellspacing=2 cellpadding=2 width=700>
					<div align="center">
					<input type="hidden" name="search_id" <?=value($search_id)?>>
					<input type="hidden" name="next_action">
					<input type="hidden" name="job_id" <?=value($job_id)?>>
					<input type="submit" value="　閉じる　" onclick="window.close()">
					</div>
					</table>
				</td>
			</tr>
		</table>
	</center>
</body>
</html>