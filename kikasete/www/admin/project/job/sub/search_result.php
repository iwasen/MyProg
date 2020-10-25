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
include("$inc/cross_search.php");

//メイン処理

set_global2('project', 'プロジェクト管理', '人数検索', BACK_TOP);

$search = new search_class;

if ($ps_search_id) {
	$search_id = $ps_search_id;
	$search->read_db($ps_search_id);
} else
	$search->get_form();

$sql = 'SELECT COUNT(*) FROM t_monitor WHERE mn_status=0 AND mn_blacklist_flg IS NULL';
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$all = $fetch[0];
}

if ($job_type == 1 || $job_type == 2) {
	// 現送信済み数取得
	$sql = "SELECT jb_real_send FROM t_job WHERE jb_job_id=$job_id";
	$rea_send = db_fetch1($sql);

	$where = $search->make_sql3($job_type, $parent_job_id, $teikei_qno, $teikei_andor, $teikei_ans, $cc_ct_id);
} else
	$where = $search->make_sql();
$sql = "SELECT COUNT(*) FROM t_monitor WHERE $where";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_row($result, 0);
	$find = $fetch[0];
}
$condition = $search->get_condition();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<center>
<table border=0 cellspacing=2 cellpadding=3>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table>
			<table class="small" border=0 cellspacing=2 cellpadding=2 width="100%">
				<tr>
					<td class="m0" width=700 colspan=2 align="left">■検索結果</td>
				</tr>
				<tr>
					<td class="m2" width="25%">検索されたモニター数</td>
					<td class="n2"><?=number_format($find)?>
<?
if ($job_type == 1 || $job_type == 2) {
?>
						（現発信者数：<?=number_format($rea_send)?>　残り発信可能人数：<?=number_format($find - $rea_send)?>）
<?
}
?>
					</td>
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
					<td class="n2"><font size="-1"><?=nl2br($condition)?></font></td>
				</tr>
			</table><br>
<?
if ($cross1_flag || $cross2_flag) {
?>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0">■クロスサーチ</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 bordercolorlight="#f0f0f0" frame="box" class="small">
			<? cross_search($cross1_flag ? $cross1 : '', $cross2_flag ? $cross2 : '', $where) ?>
						</table>
					</td>
				</tr>
			</table><br>
<?
}
?>
			<div align="center">
				<input type="submit" value="　閉じる　" onclick="window.close()">
			</div>
		</td>
	</tr>
</table>
</center>
</body>
</html>
