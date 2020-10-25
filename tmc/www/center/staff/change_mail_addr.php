<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:メールアドレス変更処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");

// メールアドレス更新
$rec['stf_mail_addr'] = sql_char($_POST['mail_addr']);
db_update('t_staff', $rec, "stf_staff_id=$g_staff_id");

$_SESSION['s_mail_addr'] = $_POST['mail_addr'];

$msg = 'メールアドレスを変更しました。';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header(decode_privilege($g_privilege) . '情報変更｜メールアドレス変更') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='change.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
