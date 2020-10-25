<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・クロス集計結果
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'クロス集計結果', BACK_TOP);

if ($hyousoku == '')
	$msg[] = '表側が選択されていないようです。';

if ($hyoutou == '')
	$msg[] = '表頭が選択されていないようです。';

if ($hyousoku != '' && $hyousoku == $hyoutou)
	$msg[] = '表側と表頭は異なる設問を選択してください。';

if (!$msg) {
	$enquete = new enquete_class;
	$enquete->read_db($enquete_id);

	$q_hyousoku = &$enquete->question[$hyousoku];
	$q_hyoutou = &$enquete->question[$hyoutou];

	// 回答者数取得
	$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$enquete_id";
	$ans_count = db_fetch1($sql, 0);

	$sql = "SELECT s.as_sel_no AS s_sel_no,t.as_sel_no AS t_sel_no,COUNT(*) as sel_count"
			. " FROM t_ans_select s"
			. " JOIN t_ans_select t ON t.as_monitor_id=s.as_monitor_id"
			. " WHERE s.as_enquete_id=$enquete_id AND s.as_question_no=$hyousoku AND t.as_enquete_id=$enquete_id AND t.as_question_no=$hyoutou"
			. " GROUP BY t.as_sel_no,s.as_sel_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$count_tbl[$fetch->s_sel_no][$fetch->t_sel_no] = $fetch->sel_count;
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<?
if (!$msg) {
?>
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=1 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m1" align="center"></td>
<?
foreach ($q_hyoutou->sel_text as $tno => $sel_text) {
?>
					<td class="m1" align="center">Q<?=$hyoutou?>=<?=$tno?></td>
<?
}
?>
				</tr>
<?
foreach ($q_hyousoku->sel_text as $sno => $sel_text) {
?>
				<tr>
					<td class="m1" align="center">Q<?=$hyousoku?>=<?=$sno?></td>
<?
	foreach ($q_hyoutou->sel_text as $tno => $sel_text) {
?>
					<td class="n0" align="center"><?=number_format($count_tbl[$sno][$tno])?></td>
<?
	}
?>
				</tr>
<?
}
?>
			</table>
			<br><br>
			<table border=1 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m1" align="center"></td>
<?
foreach ($q_hyoutou->sel_text as $tno => $sel_text) {
?>
					<td class="m1" align="center">Q<?=$hyoutou?>=<?=$tno?></td>
<?
}
?>
				</tr>
<?
foreach ($q_hyousoku->sel_text as $sno => $sel_text) {
?>
				<tr>
					<td class="m1" align="center">Q<?=$hyousoku?>=<?=$sno?></td>
<?
	foreach ($q_hyoutou->sel_text as $tno => $sel_text) {
?>
					<td class="n0" align="center"><?=format_percent($count_tbl[$sno][$tno], $ans_count, 1)?></td>
<?
	}
?>
				</tr>
<?
}
?>
			</table>
			<br>
			<div align="center">
				<input type="button" value="　戻る　" onclick="history.back()">
				<input type="button" value="　CSV　" onclick="location.href='csv_crs.php?enquete_id=<?=$enquete_id?>&hyousoku=<?=$hyousoku?>&hyoutou=<?=$hyoutou?>'">
			</div>
		</td>
	</tr>
</table>
</form>
<?
} else
	error_msg($msg);
?>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
