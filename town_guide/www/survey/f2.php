<?
// ctl.phpからincludeされたのでなければ終了ページへ飛ばす
if (!isset($enquete)) {
	header("location: end.html");
	exit;
}

$qno = mb_convert_kana($question_no, 'N');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
.font-qa {FONT-SIZE: 12pt; COLOR: black}
</style>
<title>アンケート</title>
<script type="text/javascript">
<!--
function next() {
	var f = document.form1;
	var chk = false;
	for (var i = 0; i < f.sa1.length; i++) {
		if (f.sa1[i].checked) {
			chk = true;
			break;
		}
	}
	if (!chk) {
		alert("Ｑ<?=$qno?>の中からひとつお選びください。");
		f.sa1[0].focus();
		return;
	}
	f.submit();
}
//-->
</script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name=form1 method=post action="ctl.php">
<!-- 設問 -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">■Ｑ<?=$qno?>．<br>
&nbsp;&nbsp;&nbsp;<a href="images/tg-160.JPG" target="_blank">こちら</a>をクリックして、画像をご覧下さい。なお、ご覧頂いている画像は【タウンガイド】の表紙イメージです。<br>
&nbsp;&nbsp;&nbsp;お住まいの地域により、表紙が異なる場合もありますので、ご注意ください。<br><br>
&nbsp;&nbsp;&nbsp;あなたは、タウンガイドをどのくらいの頻度で使用しますか。<br>
&nbsp;&nbsp;&nbsp;あてはまるものをひとつだけお選びください。<br></td>
	</tr>
</TABLE>
<!-- 選択肢 -->
<br>

<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">　</td>
		<td class="font-qa" align="left">
			<nobr><input type="radio" name="sa1" value=1>週１回以上</nobr><br>
			<nobr><input type="radio" name="sa1" value=2>２週間に１回以上</nobr><br>
			<nobr><input type="radio" name="sa1" value=3>１ヶ月に１回以上</nobr><br>
			<nobr><input type="radio" name="sa1" value=4>３ヶ月に１回以上</nobr><br>
			<nobr><input type="radio" name="sa1" value=5>半年に１回以上</nobr><br>
			<nobr><input type="radio" name="sa1" value=6>１年に１回以上</nobr><br>
			<nobr><input type="radio" name="sa1" value=7>１年に１回未満</nobr><br>
			<nobr><input type="radio" name="sa1" value=8>タウンガイドを知らない</nobr><br>
		</td>
	</tr>
</table>

<center>
	<input type="button" value="次の質問" onclick="next()">
</center>

<input type="hidden" name="question_no" value="<?=$question_no?>">
</form>
</body>
</html>
