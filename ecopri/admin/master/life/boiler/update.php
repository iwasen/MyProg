<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:給湯器タイプマスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '給湯器タイプマスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT bt_boiler_type_cd, bt_order FROM m_boiler_type WHERE bt_boiler_name='$name' or bt_order=$bt_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(bt_boiler_type_cd), 0) + 1) FROM m_boiler_type WHERE bt_boiler_type_cd<>999";
		$sql = "INSERT INTO m_boiler_type (bt_boiler_type_cd, bt_boiler_name, bt_order) VALUES (($sub), '$name', $bt_order)";
		db_exec($sql);
		$msg = '給湯器タイプを登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または給湯器タイプ名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT bt_boiler_name FROM m_boiler_type WHERE bt_boiler_name='$name' AND bt_boiler_type_cd<>$bt_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_boiler_type SET" .
					" bt_boiler_name=" . sql_char($name) .
					", bt_order=" . sql_number($bt_order) .
					" WHERE bt_boiler_type_cd=$bt_cd";
		db_exec($sql);
		$msg = '給湯器タイプマスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '給湯器タイプ名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_boiler_type WHERE bt_boiler_type_cd=$bt_cd";
	db_exec($sql);
	$msg = '給湯器タイプを削除しました。';
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
