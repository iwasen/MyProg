<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店マスタ一覧リスト表示
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// セッション変数処理
get_session_vars($_GET['pset'], 'master_dealer', 'sort_col', 'sort_dir');

// 販売店一覧取得
$order_by = order_by(1, 0, 'dlr_dealer_cd', 'dlr_dealer_name', 'dlr_short_name', 'sch_sales_channel_cd', 'tts_tantousha_name');
$sql = "SELECT dlr_dealer_cd,dlr_dealer_name,dlr_short_name,sch_sales_channel_name,tts_tantousha_name"
		. " FROM t_dealer"
		. " JEFT JOIN t_sales_channel ON sch_sales_channel_cd=dlr_sales_channel_cd"
		. " LEFT JOIN t_tantousha ON tts_tantousha_id=dlr_tantousha_id"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('マスタメンテナンス｜販売店マスタ') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■登録済み販売店一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1,'販売店コード');
sort_header(2,'販売店名');
sort_header(3,'略称');
sort_header(4,'販売チャネル');
sort_header(5,'担当者');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?dealer_cd=<?=$fetch->dlr_dealer_cd?>" title="販売店情報を表示・変更します"><?=$fetch->dlr_dealer_cd?></a></td>
		<td><?=htmlspecialchars($fetch->dlr_dealer_name)?></td>
		<td><?=htmlspecialchars($fetch->dlr_short_name)?></td>
		<td><?=htmlspecialchars($fetch->sch_sales_channel_name)?></td>
		<td><?=htmlspecialchars($fetch->tts_tantousha_name)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
