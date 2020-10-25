<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・参加履歴
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　参加履歴', BACK_CLOSE);

// メンバーの名前取得
$sql = "SELECT COALESCE(mn_name1,'')||COALESCE(mn_name2,'') FROM t_monitor WHERE mn_monitor_id=$monitor_id";
$monitor_name = db_fetch1($sql);

// ソート条件
$order_by = order_by(1, 0, 'ppj_pjt_id', 'ppj_room_name', 'mr_kinmu_name', 'mr_name1_kana,mr_name2_kana', 'sanka_flag');

// リスト抽出
$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_kinmu_name,mr_name1,mr_name2,CASE WHEN pmn_admin_choice=1 OR pmn_admin_choice=2 THEN true ELSE false END AS sanka_flag,mr_marketer_id"
		. " FROM t_mp_monitor"
		. " JOIN t_mp_pjt ON ppj_pjt_id=pmn_pjt_id"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE pmn_monitor_id=$monitor_id"
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
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($monitor_name)?>さんの参加履歴</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="monitor_id" <?=value($monitor_id)?>>
</form>


<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, '会議室ID');
sort_header(2, '会議室名');
sort_header(3, '企業名');
sort_header(4, 'ﾏｰｹﾀｰ名');
sort_header(5, '参加・不参加');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$pjt_id?></td>
		<td align="center"><a href="memberlist_gaitou.php?pjt_id=<?=$pjt_id?>" target="_blank"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td align="center"><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td align="center"><a href="marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="center"><?=$fetch->sanka_flag == 't' ? '参加' : '不参加'?></td>
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
