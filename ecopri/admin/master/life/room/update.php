<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:住居間取りマスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '住居間取りマスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT rm_room_cd, rm_order FROM m_room WHERE rm_room_name='$name' or rm_order=$rm_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(rm_room_cd), 0) + 1) FROM m_room WHERE rm_room_cd<>999";
		$sql = "INSERT INTO m_room (rm_room_cd, rm_room_name, rm_order) VALUES (($sub), '$name', $rm_order)";
		db_exec($sql);
		$msg = '住居間取りを登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または住居間取り名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT rm_room_name FROM m_room WHERE rm_room_name='$name' AND rm_room_cd<>$rm_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_room SET" .
					" rm_room_name=" . sql_char($name) .
					", rm_order=" . sql_number($rm_order) .
					" WHERE rm_room_cd=$rm_cd";
		db_exec($sql);
		$msg = '住居間取りマスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '住居間取り名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_room WHERE rm_room_cd=$rm_cd";
	db_exec($sql);
	$msg = '住居間取りを削除しました。';
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
