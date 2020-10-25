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
.matrix th {background-color: #9ddfff;}
.matrix td {background-color: #ffffff;}

</style>
<title>アンケート</title>
<script type="text/javascript">
<!--
function next() {
	var f = document.form1;
	if (f.fa1.value == "") {
		alert("「電話をかける」の欄が入力されていないようです。\n※「ない」場合は「なし」とご記入ください。");
		f.fa1.focus();
		return;
	}
	if (f.fa2.value == "") {
		alert("「住所を調べる」の欄が入力されていないようです。\n※「ない」場合は「なし」とご記入ください。");
		f.fa2.focus();
		return;
	}
	f.submit();
}
//-->
</script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<!-- 設問 -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">■Ｑ<?=$qno?>．<br>
&nbsp;&nbsp;&nbsp;あなたがその他に「電話をかける」「住所を調べる」ところがあれば、ご自由にお書きください。<br>
&nbsp;&nbsp;&nbsp;※「ない」場合は「なし」とご記入ください。<br>
</td>
	</tr>
</TABLE>
<!-- 選択肢 -->
<br>
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">　</td>
		<td class="font-qa" align="left">
			<table border="1" cellspacing="0" cellpadding="2" width="80%" class="matrix">
			<tr>
				<th width="30%">電話をかける</th>
				<td width="70%"><textarea name="fa1" cols="56" rows="5"></textarea></td>
			</tr></table><br>
			<table border="1" cellspacing="0" cellpadding="2" width="80%" class="matrix">
			<tr>
				<th width="30%">住所を調べる</th>
				<td width="70%"><textarea name="fa2" cols="56" rows="5"></textarea></td>
			</tr>
			</table><br><br>
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
