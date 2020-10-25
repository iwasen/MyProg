<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:メール購読解除理由マスタ一覧リスト表示
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// セッション変数処理
get_session_vars($_GET['pset'], 'master_cancel_result', 'sort_col', 'sort_dir');

// メール購読解除理由一覧取得
$order_by = order_by(1, 0, 'mcr_cancel_cd', 'mcr_cancel_text');
$sql = "SELECT mcr_cancel_cd,mcr_cancel_text FROM t_mail_cancel_result $order_by";
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

<? center_header('マスタメンテナンス｜メール購読解除理由マスタ') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■登録済みメール購読解除理由一覧</td>
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
sort_header(1,'コード');
sort_header(2,'メール購読解除理由');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?cancel_cd=<?=$fetch->mcr_cancel_cd?>" title="メール購読解除理由を表示・変更します"><?=$fetch->mcr_cancel_cd?></a></td>
		<td><?=htmlspecialchars($fetch->mcr_cancel_text)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
