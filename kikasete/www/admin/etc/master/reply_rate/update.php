<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アンケート返信率マスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理｜マスタメンテナンス', 'アンケート返信率マスタ', BACK_TOP);

db_begin_trans();

foreach ($reply_rate as $age_cd => $reply_rate2) {
	foreach ($reply_rate2 as $sex_cd => $reply_rate3) {
		$sql = "UPDATE m_reply_rate SET rep_reply_rate=$reply_rate3 WHERE rep_age_cd=$age_cd AND rep_sex_cd=$sex_cd AND rep_reply_rate<>$reply_rate3";
		db_exec($sql);
	}
}

db_commit_trans();

$msg = 'アンケート返信率マスタ情報を更新しました。';
$ret = 'location.href=\'list.php\'';
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
