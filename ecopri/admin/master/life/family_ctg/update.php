<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:家族カテゴリマスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '家族カテゴリマスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT fc_text FROM m_family_ctg WHERE fc_text='$name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(fc_family_ctg_cd), 0) + 1) FROM m_family_ctg WHERE fc_family_ctg_cd<>999";
		$sql = "INSERT INTO m_family_ctg (fc_family_ctg_cd, fc_adult_num, fc_child_num, fc_text) VALUES (($sub), '$adult_num', '$child_num', '$name')";
		db_exec($sql);
		$msg = '家族カテゴリを登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した家族カテゴリ名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT fc_family_ctg_cd FROM m_family_ctg WHERE fc_text='$name' AND fc_family_ctg_cd<>$fc_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_family_ctg SET" .
					" fc_adult_num=" . sql_number($adult_num) .
					", fc_child_num=" . sql_number($child_num) .
					", fc_text=" . sql_char($name) .
					" WHERE fc_family_ctg_cd=$fc_cd";
		db_exec($sql);
		$msg = '家族カテゴリマスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '家族カテゴリ名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_family_ctg WHERE fc_family_ctg_cd=$fc_cd";
	db_exec($sql);
	$msg = '家族カテゴリを削除しました。';
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
