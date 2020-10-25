<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:督促テンプレート
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', 'その他管理｜マスタメンテナンス', '督促メールテンプレート', BACK_TOP);

$sql = "UPDATE m_enq_template SET" .
			" et_header=" . sql_char($header) .
			",et_body=" . sql_char($body) .
			",et_footer=" . sql_char($footer) .
			" WHERE et_template_id='$template_id'";
db_exec($sql);
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
<p class="msg">督促メールテンプレートを更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='../menu.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
