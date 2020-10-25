<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:初期フォローメール挨拶文一覧表示
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// 入力パラメータ
$vol_no = $_GET['vol_no'];

// セッション変数処理
get_session_vars($_GET['pset'], 'master_follow_greeting', 'sort_col', 'sort_dir');

// 登録済み挨拶文取得
$order_by = order_by(1, 0, 'sch_sales_channel_cd', 'flg_contents');
$sql = "SELECT sch_sales_channel_cd,sch_sales_channel_name,flg_contents"
		. " FROM t_sales_channel"
		. " LEFT JOIN t_follow_greeting ON flg_vol_no=$vol_no AND flg_sales_channel_cd=sch_sales_channel_cd"
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

<? center_header('マスタメンテナンス｜初期フォローメール｜挨拶文') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■初期フォローメール VOL.<?=$vol_no?>　挨拶文</td>
		<td class="lb">
			<input type="button" value=" 戻る " onclick="location.href='list.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="vol_no" <?=value($vol_no)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1,'販売チャネル');
sort_header(2,'内容');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="greeting_edit.php?vol_no=<?=$vol_no?>&sales_channel_cd=<?=$fetch->sch_sales_channel_cd?>" title="指定した販売チャネルの挨拶文を登録します。"><?=htmlspecialchars($fetch->sch_sales_channel_name)?></a></td>
		<td><?=$fetch->flg_contents != '' ? '登録済み' : '<font color="red">未登録</font>'?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
