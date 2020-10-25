<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:マスタメンテナンス・ユーザ情報変更処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/select.php");

// 入力パラメータ
$mail_addr = $_GET['mail_addr'];

// セッション登録
get_session_vars($_GET['pset'], 'user', 'user_id', 'name', 'date', 'status', 'sort_col', 'sort_dir');

// ソート条件
$order_by = order_by(1, 0, 'ups_user_id', 'ups_name_kana', 'umg_customer_cd', 'umg_dealer_name', 'umg_kounyu_kibou_car', 'urg_regist_date', 'ust_status');

$sql = "SELECT ups_user_id,ups_mail_addr,ups_name_kanji,urg_regist_date,ust_status,umg_customer_cd,umg_dealer_name,umg_kounyu_kibou_car"
		. " FROM t_user_personal"
		. " JOIN t_user_status ON ust_user_id=ups_user_id"
		. " JOIN t_user_regist ON urg_user_id=ups_user_id"
		. " JOIN t_user_manage ON umg_user_id=ups_user_id"
		. " WHERE ups_mail_addr=" . sql_char($mail_addr)
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('ユーザ情報表示') ?>

<div align="center">
<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■ユーザ選択　メールアドレス：<?=htmlspecialchars($mail_addr)?></td>
		<td class="lb">
			<input type="button" value=" 戻る " onclick="location.href='../input_id.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="mail_addr" <?=value($mail_addr)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1, 'ユーザID');
sort_header(2, '名前');
sort_header(3, 'お客様CD');
sort_header(4, '販売店');
sort_header(5, '購入希望車名');
sort_header(6, '登録日');
sort_header(7, '状態');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="info.php?user_id=<?=$fetch->ups_user_id?>" title="ユーザ情報を表示・変更します"><?=$fetch->ups_user_id?></a></td>
		<td><?=htmlspecialchars($fetch->ups_name_kanji)?></td>
		<td><?=htmlspecialchars($fetch->umg_customer_cd)?></td>
		<td><?=htmlspecialchars($fetch->umg_dealer_name)?></td>
		<td><?=htmlspecialchars($fetch->umg_kounyu_kibou_car)?></td>
		<td align="center"><?=format_date($fetch->urg_regist_date)?></td>
		<td align="center"><?=decode_user_status($fetch->ust_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
