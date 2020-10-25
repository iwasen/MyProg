<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:業種マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理｜マスタメンテナンス', '業種マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT gs_gyoushu_cd, gs_order FROM m_gyoushu WHERE gs_gyoushu_name='$gyoushu_name' or gs_order=$gs_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(gs_gyoushu_cd), 0) + 1) FROM m_gyoushu";
		$sql = "INSERT INTO m_gyoushu (gs_gyoushu_cd, gs_gyoushu_name, gs_order, gs_type_cd) VALUES (($sub) , '$gyoushu_name', $gs_order, $type_cd)";
		db_exec($sql);
		$msg = '業種名を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または業種名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT gs_gyoushu_name FROM m_gyoushu WHERE gs_gyoushu_name='$gyoushu_name' AND gs_gyoushu_cd<>$gyoushu_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_gyoushu SET" .
					" gs_gyoushu_name=" . sql_char($gyoushu_name) .
					", gs_order=" . sql_number($gs_order) .
					", gs_type_cd=" . sql_number($type_cd) .
					" WHERE gs_gyoushu_cd=$gyoushu_cd";
		db_exec($sql);
		$msg = '業種マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '業種名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_gyoushu WHERE gs_gyoushu_cd=$gyoushu_cd";
	db_exec($sql);
	$msg = '業種名を削除しました。';
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
