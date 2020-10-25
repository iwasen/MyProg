<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:メール送受信履歴
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

// 入力パラメータ
$user_id = $_GET['user_id'];

// セッション変数処理
get_session_vars($_GET['pset'], 'user_mail_log', 'sort_col', 'sort_dir', 'page');

// ユーザ名取得
$sql = "SELECT ups_name_kanji FROM t_user_personal WHERE ups_user_id=$user_id";
$result = db_exec($sql);
if (pg_num_rows($result))
	$name_kanji = pg_fetch_result($result, 0, 0);

// 送受信履歴取得
$order_by = order_by(1, 0, 'cml_date', 'cmc_comm_type', 'cmc_subject', 'cml_err_mail_flag');
$sql = "SELECT cml_comm_id,cml_date,cml_send_recv,cmc_comm_type,cmc_subject,cml_err_mail_flag"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE cml_user_id=$user_id $order_by";
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

<? center_header('ユーザ情報表示｜メール送受信履歴') ?>

<form name="form1" method="get">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=$name_kanji?>さんの送受信履歴</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="history.back()">
			<input type="button" value="　閉じる　" onclick="window.close()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="user_id" <?=value($user_id)?>>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '日時');
		sort_header(2, '種類');
		sort_header(3, '件名');
		sort_header(4, '配信状況');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->cml_date)?></td>
		<td><?=decode_comm_type($fetch->cmc_comm_type)?><?=decode_send_recv($fetch->cml_send_recv)?></td>
		<td><a href="show.php?comm_id=<?=$fetch->cml_comm_id?>" title="メール内容を表示します"><?=$fetch->cmc_subject != '' ? htmlspecialchars($fetch->cmc_subject) : '（件名無し）'?></a></td>
		<td align="center"><?=$fetch->cml_err_mail_flag == DBTRUE ? '<font color="red">不達</font>' : '正常'?></td>
	</tr>
<?
}
?>
</table>

<? center_footer() ?>

</body>
</html>
