<?
// ctl.phpからincludeされたのでなければ終了ページへ飛ばす
if (!isset($enquete)) {
	header("location: end.html");
	exit;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
.font-qa {FONT-SIZE: 12pt; COLOR: black}
</style>
<title>アンケート</title>
<script type="text/javascript"></script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<!-- 設問 -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">お疲れ様でした。[送信]ボタンを押して、回答データを送信して下さい。<br>どうもありがとうございました。<br><br></td>
	</tr>
</table>
<!-- 選択肢 -->
<br>
<br>
<center>
	<input type="submit" value=" 送信 ">
</center>

<input type="hidden" name="question_no" value="<?=$question_no?>">
</form>
</body>
</html>
