<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売チャネルマスタ一覧リスト表示
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// セッション変数処理
get_session_vars($_GET['pset'], 'master_sales_channel', 'sort_col', 'sort_dir');

// 販売チャネル一覧取得
$order_by = order_by(1, 0, 'sch_sales_channel_cd');
$sql = "SELECT sch_sales_channel_cd,sch_sales_channel_name FROM t_sales_channel $order_by";
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

<? center_header('マスタメンテナンス｜販売チャネルマスタ') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■登録済み販売チャネル一覧</td>
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
sort_header(1,'販売チャネル名');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?sales_channel_cd=<?=$fetch->sch_sales_channel_cd?>" title="販売チャネル情報を表示・変更します"><?=htmlspecialchars($fetch->sch_sales_channel_name)?></a></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
