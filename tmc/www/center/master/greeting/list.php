<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:時節の挨拶文マスタ一覧リスト表示
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// セッション変数処理
get_session_vars($_GET['pset'], 'master_season_greetings', 'sort_col', 'sort_dir');

// 時節の挨拶文一覧取得
$order_by = order_by(1, 0, 'sgr_from_month;sgr_from_day');
$sql = "SELECT sgr_seq_no,sgr_from_month,sgr_from_day,sgr_to_month,sgr_to_day,sgr_greeting FROM t_season_greetings $order_by";
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

<? center_header('マスタメンテナンス｜時節の挨拶文マスタ') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■登録済み時節の挨拶文一覧</td>
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
sort_header(1,'有効期間');
sort_header(2,'挨拶文');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?seq_no=<?=$fetch->sgr_seq_no?>" title="時節の挨拶文を表示・変更します"><?="{$fetch->sgr_from_month}月{$fetch->sgr_from_day}日〜{$fetch->sgr_to_month}月{$fetch->sgr_to_day}日"?></a></td>
		<td><pre><?=$fetch->sgr_greeting?></pre></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
