<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:地域区分マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜地域マスタ', '地域区分マスタ', BACK_TOP);

switch ($next_action) {
case 'update':
	$sql = "SELECT aa_area2_cd FROM m_area2 WHERE aa_area2_name='$aa_name' AND aa_area2_cd<>$aa_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_area2 SET" .
					" aa_area2_name=" . sql_char($aa_name) .
					" WHERE aa_area2_cd=$aa_cd";
		db_exec($sql);
		$msg = '地域区分マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '地域区分名は既に登録されています。';
		$ret = 'history.back()';
	}
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
