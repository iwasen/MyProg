<?
// ctl.phpからincludeされたのでなければ終了ページへ飛ばす
if (!isset($enquete)) {
	header("location: end.html");
	exit;
}

$question_no = $enquete->get_question_no();
$gyoshu_name = $enquete->get_gyoshu_name();
$gyoshu_ary = $enquete->get_gyoshu_ary();

$category_qno = mb_convert_kana($question_no, 'N');
$gyoshu_qno = mb_convert_kana($question_no + 1, 'N');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
.font-qa {font-size: 12pt; color: black}
.matrix th {background-color: #9ddfff;}
</style>
<title>アンケート</title>
<script type="text/javascript">
<!--
function next() {
	var f = document.form1;
	var tel = f["category_ans[1]"];
	var addr = f["category_ans[2]"];
	var none = f["category_ans[3]"];
	if (!tel.checked && !addr.checked && !none.checked) {
		alert("Ｑ<?=$category_qno?>がひとつも選択されていないようです。");
		tel.focus();
		return;
	}
	var count_tel = count_check(f["gyoshu_tel[]"]);
	var count_addr = count_check(f["gyoshu_addr[]"]);
	if (tel.checked && count_tel == 0) {
		alert("Ｑ<?=$category_qno?>で「電話をかける」を選択された場合は、\nＱ<?=$gyoshu_qno?>の中から「電話をかける」を１つ以上選択してください。");
		f["gyoshu_tel[]"][0].focus();
		return;
	}
	if (addr.checked && count_addr == 0) {
		alert("Ｑ<?=$category_qno?>で「住所を調べる」を選択された場合は、\nＱ<?=$gyoshu_qno?>の中から「住所を調べる」を１つ以上選択してください。");
		f["gyoshu_addr[]"][0].focus();
		return;
	}
	if (none.checked && (count_tel != 0 || count_addr != 0)) {
		alert("Ｑ<?=$category_qno?>で「この中にはない」を選択された場合は、\nＱ<?=$gyoshu_qno?>を選択することはできません。");
		return;
	}
	if (!tel.checked && count_tel != 0) {
		alert("Ｑ<?=$category_qno?>で「電話をかける」を選択されていない場合は、\nＱ<?=$gyoshu_qno?>の「電話をかける」を選択することはできません。");
		return;
	}
	if (!addr.checked && count_addr != 0) {
		alert("Ｑ<?=$category_qno?>で「住所を調べる」を選択されていない場合は、\nＱ<?=$gyoshu_qno?>の「住所を調べる」を選択することはできません。");
		return;
	}
	f.submit();
}
function count_check(c) {
	var count = 0;
	for (var i = 0; i < c.length; i++) {
		if (c[i].checked)
			count++;
	}
	return count;
}
function onclick_tel_addr(c) {
	var f = document.form1;
	if (c)
		f["category_ans[3]"].checked = false;
}
function onclick_none(c) {
	var f = document.form1;
	if (c) {
		f["category_ans[1]"].checked = false;
		f["category_ans[2]"].checked = false;
	}
}
//-->
</script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<!-- 設問 -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">■Ｑ<?=$category_qno?>．<br>
			&nbsp;&nbsp;&nbsp;あなたが【<b><font color="#B22222"><?=htmlspecialchars($gyoshu_name)?></font>等</b>】のいずれかについて、<br>
		&nbsp;&nbsp;&nbsp;電話をかけたり、住所を調べたりしますか。あてはまるものをいくつでもお選びください。<br></td>
	</tr>
</table>
<!-- 選択肢 -->
<br>

<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">　</td>
		<td class="font-qa" align="left">
			<nobr><input type="checkbox" name="category_ans[1]" value="1" onclick="onclick_tel_addr(checked)">電話をかける</nobr><br>
			<nobr><input type="checkbox" name="category_ans[2]" value="2" onclick="onclick_tel_addr(checked)">住所を調べる</nobr><br>
			<nobr><input type="checkbox" name="category_ans[3]" value="3" onclick="onclick_none(checked)">この中にはない</nobr><br>
		</td>
	</tr>
</table>
<br><br>
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">
			■Ｑ<?=$gyoshu_qno?>．<br>
			&nbsp;&nbsp;&nbsp;上の質問で『電話をかける』『住所を調べる』にチェックをされた方のみお答えください。<br>
			&nbsp;&nbsp;&nbsp;あなたが下記のカテゴリーについて、電話をかけたり、住所を調べるものについて、<br>
			&nbsp;&nbsp;&nbsp;あてはまるものをそれぞれひとつずつお選びください。<br>
		</td>
	</tr>
</table>
<!-- 選択肢 -->
<br>
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">　</td>
		<td class="font-qa" align="left">
			<table border="1" cellspacing="0" cellpadding="2" width="80%" class="matrix">
				<tr>
					<th width="50%">&nbsp;</th>
					<th width="25%">電話をかける</th>
					<th width="25%">住所を調べる</th>
				</tr>
<?
foreach ($gyoshu_ary as $gyoshu_no => $gyoshu_name) {
?>
				<tr align="center" bgcolor="<?=$gyoshu_no % 2 ? '#E0FFFF': '#FFFFFF'?>">
					<td><b><?=htmlspecialchars($gyoshu_name)?></b></th>
					<td><input type="checkbox" name="gyoshu_tel[]" value="<?=$gyoshu_no?>"></td>
					<td><input type="checkbox" name="gyoshu_addr[]" value="<?=$gyoshu_no?>"></td>
				</tr>
<?
}
?>
			</table><br><br>
		</td>
	</tr>
</table>

<center>
<input type="button" value="次の質問" onclick="next()">
</center>
<input type="hidden" name="question_no" value="<?=$question_no?>">
<input type="hidden" name="category_id" value="<?=$enquete->get_category_id()?>">
</form>
</body>
</html>
