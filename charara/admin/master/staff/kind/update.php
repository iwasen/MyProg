<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:マスタメンテナンス｜スタッフ関連マスタ｜所属種別マスタ
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");

set_global('master_st', 'マスタ管理｜スタッフ関連マスタ', '所属種別マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT sk_kind_cd FROM m_staff_kind WHERE sk_name='$sk_name' or sk_order=$sk_order";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(sk_kind_cd), 0) + 1) FROM m_staff_kind";
		$sql = "INSERT INTO m_staff_kind (sk_kind_cd, sk_name, sk_order)"
			. " VALUES (($sub), '$sk_name', $sk_order)";
		db_exec($sql);
		$msg = '所属種別名を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力した所属種別名または表示順序は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT sk_kind_cd FROM m_staff_kind WHERE sk_name='$sk_name' AND sk_kind_cd<>$sk_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_staff_kind SET" .
					" sk_name=" . sql_char($sk_name) .
					", sk_order=" . sql_number($sk_order) .
					" WHERE sk_kind_cd=$sk_cd";
		db_exec($sql);
		$msg = '所属種別マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '所属種別名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_staff_kind WHERE sk_kind_cd=$sk_cd";
	db_exec($sql);
	$msg = '所属種別名を削除しました。';
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
