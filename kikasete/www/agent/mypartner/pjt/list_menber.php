<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・モニター検索結果一覧
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'モニター検索結果一覧', BACK_CLOSE);

// ソート条件
$order_by = order_by(1, 0, 'mn_monitor_id', 'mn_name1_kana,mn_name2_kana', 'mn_sex', 'mn_age', 'mn_jitaku_area');

// リスト抽出
$sb1 = "SELECT pmn_monitor_id FROM t_mp_monitor GROUP BY pmn_monitor_id";
$sql = "SELECT mn_monitor_id,mn_name1,mn_name2,mn_sex,DATE_PART('Y',AGE(mn_birthday)) AS mn_age,ar_area_name"
		. " FROM ($sb1) sb1"
		. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
		. " JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " WHERE mn_name1 LIKE '%" . addslashes($monitor_name) . "%' OR mn_name2 LIKE '%" . addslashes($monitor_name) . "%'"
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

<form method="post" name="form1">
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="monitor_name" <?=value($monitor_name)?>>
</form>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, 'ﾓﾆﾀｰID');
sort_header(2, '名前');
sort_header(3, '性別');
sort_header(4, '年齢');
sort_header(5, '居住地');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$monitor_id = $fetch->mn_monitor_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$monitor_id?></td>
		<td align="center"><a href="monitor_job_list_gaitou.php?monitor_id=<?=$monitor_id?>" target="_blank"><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></a></td>
		<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
		<td align="center"><?=$fetch->mn_age?></td>
		<td align="center"><?=$fetch->ar_area_name?></td>
	</tr>
<?
}
?>
</table>
<br>
<div align="center">
<input type="button" value="　閉じる　" onclick="javascript:window.close()">
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
