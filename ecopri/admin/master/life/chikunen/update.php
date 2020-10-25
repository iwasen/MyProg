<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:建設年次マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '建設年次マスタ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT cn_chikunen_cd, cn_chikunen_text, cn_chikunen_from, cn_chikunen_to FROM m_chikunen WHERE cn_chikunen_text='$name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sub = "SELECT (coalesce(MAX(cn_chikunen_cd), 0) + 1) FROM m_chikunen WHERE cn_chikunen_cd<>999";
		$sql = "INSERT INTO m_chikunen (cn_chikunen_cd, cn_chikunen_text, cn_chikunen_from, cn_chikunen_to) VALUES (($sub), '$name', $from, $to)";
		db_exec($sql);
		$msg = '建設年次を登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '建設年次名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT cn_chikunen_text FROM m_chikunen WHERE cn_chikunen_text='$name' AND cn_chikunen_cd<>$cn_cd";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_chikunen SET" .
					" cn_chikunen_text=" . sql_char($name) .
					", cn_chikunen_from=" . sql_number($from) .
					", cn_chikunen_to=" . sql_number($to) .
					" WHERE cn_chikunen_cd=$cn_cd";
		db_exec($sql);
		$msg = '建設年次マスタ情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '建設年次名は既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_chikunen WHERE cn_chikunen_cd=$cn_cd";
	db_exec($sql);
	$msg = '建設年次を削除しました。';
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
