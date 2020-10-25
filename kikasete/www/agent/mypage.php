<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:トップメニュー画面
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/list.php");
include("$top/marketer/list/list_func.php");

set_global('きかせて・net エージェントトップページ', BACK_LOGOFF);

$sql = "SELECT ag_agent_id, ag_name1, ag_name2 FROM t_agent WHERE ag_agent_id=$agent_login_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
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
function mr_detail(mr_id, top) {
  var win;
  win = window.open(top + "/sub/mr_detail.php?mr_id=" + mr_id, "mr_detail", "scrollbars=yes,resizable=yes,width=500,height=510");
  win.focus();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellpadding=8 cellspacing=0>
	<tr>
		<td><b><?=$fetch->ag_name1?>&nbsp;<?=$fetch->ag_name2?> さんのMyページ</b></td>
	</tr>
</table>
<table width="200" style="z-index:1;position:absolute;margin-top:10;margin-left:15;font-size:16;border-color:#ff8888;border-style:solid;border-width:1px;background-color:#ffffff;text-align:center">
	<tr>
		<td>事務局からのお知らせ</td>
	</tr>
</table><br>
<table width="500" style="z-index:0;margin-top:23;border-color:#ff8888;border-style:solid;border-width:1px;background-color:#ffeeee">
	<tr>
		<td>
			<table align="center" WIDTH="470" style="font-size=14">
<?
$sql = "SELECT cs_title,cs_message FROM t_center_msg WHERE cs_agent_mypage='t' AND cs_status=0 AND date_trunc('day', timestamp 'now') between cs_start_date and cs_end_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow == 0) {
?>
				<tr>
					<td>
						<p><br>現在は、ありません。</p>
					</td>
				</tr>
<?
}
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr>
					<td style="line-height:130%">
						<p><br><b>☆ <?=$fetch->cs_title?></b><br><?=nl2br($fetch->cs_message)?></p>
					</td>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>
<table align="center" width=100%><br>
	<tr>
		<td>
<? marketer_list($top, $sort_col, $sort_dir); ?>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
