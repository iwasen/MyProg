<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:広告タイプマスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理｜マスタメンテナンス', '広告タイプマスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT at_adv_id, at_order FROM m_adv_type WHERE at_name='$name' or at_order=$at_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(at_adv_id), 0) + 1) FROM m_adv_type WHERE at_adv_id<>999";
		$sql = "INSERT INTO m_adv_type (at_adv_id, at_name, at_order) VALUES (($sub), '$name', $at_order)";
		db_exec($sql);
		$msg = '広告タイプを登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または広告タイプ名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT at_name FROM m_adv_type WHERE at_name='$name' AND at_adv_id<>$adv_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_adv_type SET" .
					" at_name=" . sql_char($name) .
					", at_order=" . sql_number($at_order) .
					" WHERE at_adv_id=$adv_id";
		db_exec($sql);
		$msg = '広告タイプマスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '広告タイプ名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_adv_type WHERE at_adv_id=$adv_id";
	db_exec($sql);
	$msg = '広告タイプを削除しました。';
	$ret = 'location.href=\'list.php\'';
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
