<?
// ctl.phpからincludeされたのでなければ終了ページへ飛ばす
if (!isset($enquete)) {
	header("location: end.html");
	exit;
}

// セッション削除
session_destroy();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
.font-qa {FONT-SIZE: 12pt; COLOR: black}
</style>
<title>アンケート</title>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<br>
<br>
<center>
	<input type="button" VALUE=" 終了 " onClick="javascript:parent.window.close()">
<br><br>
</center>
<!-- メッセージ表示 -->
<table border=0 cellpadding=3 cellspacing=2 width="100%">
	<tr>
		<td align=center>
			<font color=red>回答は送信されました。　ご協力ありがとうございます。</font>
		</td>
	</tr>
</table>

</form>
</body>
</html>
