<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:ユーザ情報表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('ユーザ情報表示｜エラーメッセージ') ?>

<div align="center">
<p class="msg">指定したユーザが見つかりません。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='input_id.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
