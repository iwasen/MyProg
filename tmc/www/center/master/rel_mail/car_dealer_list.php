<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:継続リレーションメール車種情報／販売店情報一覧表示
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
get_session_vars($_GET['pset'], 'master_relation_car_dealer', 'sort_col', 'sort_dir');

// 車種情報／販売店情報一覧取得
$order_by = order_by(1, 0, 'dlr_dealer_cd', 'rlc_contents');
$sql = "SELECT dlr_dealer_cd,dlr_dealer_name,rlc_contents"
		. " FROM t_dealer"
		. " LEFT JOIN t_relation_car_dealer ON rlc_vol_no=$vol_no AND rlc_dealer_cd=dlr_dealer_cd"
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

<? center_header('マスタメンテナンス｜継続リレーションメール｜車種情報／販売店情報') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■継続リレーションメール VOL.<?=$vol_no?>　車種情報／販売店情報</td>
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
sort_header(1,'販売店');
sort_header(2,'内容');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="car_dealer_edit.php?vol_no=<?=$vol_no?>&dealer_cd=<?=$fetch->dlr_dealer_cd?>" title="指定した車種カテゴリの車種情報／販売店情報を登録します。"><?=$fetch->dlr_dealer_name?> (<?=$fetch->dlr_dealer_cd?>)</a></td>
		<td><?=$fetch->rlc_contents != '' ? '登録済み' : '<font color="red">未登録</font>'?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
