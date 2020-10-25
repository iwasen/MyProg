<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:初期フォローメール件名一覧表示
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
get_session_vars($_GET['pset'], 'master_follow_subject', 'sort_col', 'sort_dir');

// 件名一覧取得
$order_by = order_by(1, 0, 'crm_car_model_name', 'fls_subject');
$sql = "SELECT crm_car_model_cd,crm_car_model_name,fls_subject"
		. " FROM t_car_model"
		. " LEFT JOIN t_follow_subject ON fls_vol_no=$vol_no AND fls_car_model_cd=crm_car_model_cd"
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

<? center_header('マスタメンテナンス｜初期フォローメール｜件名') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■初期フォローメール VOL.<?=$vol_no?>　件名</td>
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
sort_header(1,'車種名');
sort_header(2,'件名');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="subject_edit.php?vol_no=<?=$vol_no?>&car_model_cd=<?=$fetch->crm_car_model_cd?>" title="指定した車種の件名を登録します。"><?=htmlspecialchars($fetch->crm_car_model_name)?></a></td>
		<td><?=$fetch->fls_subject != '' ? htmlspecialchars($fetch->fls_subject) : '<font color="red">未登録</font>'?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
