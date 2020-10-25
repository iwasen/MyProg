<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店メールテンプレート更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// レコード更新処理
function rec_update() {
	db_begin_trans();

	// t_dealer_mail更新
	$rec['dmt_header'] = sql_char($_POST["header"]);
	$rec['dmt_footer'] = sql_char($_POST["footer"]);
	db_update('t_dealer_mail_template', $rec, "dmt_template_id=1");

	db_commit_trans();
}

// レコード更新
rec_update();

$msg = '販売店メールテンプレートを更新しました。';
$back = "location.href='../menu.php'";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('販売店メール配信｜更新処理') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? center_footer() ?>

</body>
</html>
