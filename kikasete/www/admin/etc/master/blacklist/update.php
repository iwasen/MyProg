<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ブラックリスト種別マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理｜マスタメンテナンス', 'ブラックリスト種別マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT bl_blacklist_flg FROM m_blacklist WHERE bl_name='$bl_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(bl_blacklist_flg), 0) + 1) FROM m_blacklist";
		$sql = "INSERT INTO m_blacklist (bl_blacklist_flg, bl_name) VALUES (($sub), '$bl_name')";
		db_exec($sql);
		$msg = 'ブラックリスト種別名を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したブラックリスト種別名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT bl_blacklist_flg FROM m_blacklist WHERE bl_name='$bl_name' AND bl_blacklist_flg<>$bl_flg";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_blacklist SET" .
					" bl_name=" . sql_char($bl_name) .
					" WHERE bl_blacklist_flg=$bl_flg";
		db_exec($sql);
		$msg = 'ブラックリスト種別マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = 'ブラックリスト種別名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_blacklist WHERE bl_blacklist_flg=$bl_flg";
	db_exec($sql);
	$msg = 'ブラックリスト種別名を削除しました。';
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
