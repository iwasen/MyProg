<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:OneToOneメール・送信メール表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/follow_mail.php");
include("$inc/rel_mail.php");

// 入力パラメータ
$user_id = $_GET['user_id'];
$vol_no = $_GET['vol_no'];
$mail = $_GET['mail'];

if ($mail == 'follow') {
	// 初期フォローメール取得
	$kind = '初期フォローメール';
	get_follow_mail($user_id, $vol_no, '', $subject, $body, $from_name, $from_addr);
} else {
	// 継続リレーションメール取得
	$kind = '継続リレーションメール';
	get_relation_mail($user_id, $vol_no, '', $subject, $body, $from_name, $from_addr);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header("OneToOneメール｜{$kind}表示") ?>

<div align="center">
<form>
<table order=0 cellspacing=1 cellpadding=0 width="100%">
	<tr>
		<td>Vol.<?=$vol_no?>　件名：<font color="#333388"><b><?=htmlspecialchars($subject)?></b></font></td>
		<td align="right"><input type="button" value="　閉じる　" onclick="window.close()"></td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
	<tr>
		<td><pre><?=htmlspecialchars($body)?></pre></td>
	</tr>
</table>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</form>
</div>

<? center_footer() ?>


</body>
</html>
