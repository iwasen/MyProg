<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:媒体マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理｜マスタメンテナンス', '媒体マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT md_media_id, md_order FROM m_media WHERE md_name='$name' or md_order=$md_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(md_media_id), 0) + 1) FROM m_media WHERE md_media_id<>999";
		$sql = "INSERT INTO m_media (md_media_id, md_name, md_order) VALUES (($sub), '$name', $md_order)";
		db_exec($sql);
		$msg = '媒体を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した表示順序または媒体名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT md_name FROM m_media WHERE md_name='$name' AND md_media_id<>$media_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_media SET" .
					" md_name=" . sql_char($name) .
					", md_order=" . sql_number($md_order) .
					" WHERE md_media_id=$media_id";
		db_exec($sql);
		$msg = '媒体マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '媒体名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_media WHERE md_media_id=$media_id";
	db_exec($sql);
	$msg = '媒体を削除しました。';
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
