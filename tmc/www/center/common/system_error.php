<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:事務局ページ共通システムエラー画面
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header('システムエラー') ?>
<div align="center">

<p>システムエラーが発生しました。<br>
再度実行してもエラーになる場合はシステム管理者にご連絡ください。
</p>
<input type="button" value="　戻る　" onclick="history.back()">
</div>

<? center_footer() ?>

</body>
</html>
