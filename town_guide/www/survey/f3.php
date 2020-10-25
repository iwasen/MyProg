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
<script type="text/javascript">
<!--
function next() {
	var f = document.form1;
	if (f.f1.selectedIndex == 0) {
		alert("お住まいの都道府県が選択されていないようです。");
		f.f1.focus();
		return;
	}
	if (!sel_chk(f.f2)) {
		alert("性別が選択されていないようです。");
		f.f2[0].focus();
		return;
	}
	if (f.f3.value == "") {
		alert("年齢が入力されていないようです。");
		f.f3.focus();
		return;
	}
	if (!num_chk(f.f3.value)) {
		alert("年齢は半角数字で入力してください。");
		f.f3.focus();
		return;
	}
	if (!f.f4[0].checked && !f.f4[1].checked) {
		alert("未既婚が選択されていないようです。");
		f.f4[0].focus();
		return;
	}
	if (!sel_chk(f.f5)) {
		alert("職業が選択されていないようです。");
		f.f5[0].focus();
		return;
	}
	if (f.f6.value == "") {
		alert("ご登録メールアドレスが入力されていないようです。");
		f.f6.focus();
		return;
	}
	if (!mail_chk(f.f6.value)) {
		alert("ご登録メールアドレスが正しくないようです。");
		f.f6.focus();
		return;
	}
	if (f.f7.value == "") {
		alert("回答用ＩＤが入力されていないようです。");
		f.f7.focus();
		return;
	}
	f.submit();
}
function num_chk(v) {
	for (var i = 0; i < v.length; i++) {
		if (v.charAt(i) < "0" || v.charAt(i) > "9")
			return false;
	}
	return true;
}
function sel_chk(c) {
	for (var i = 0; i < c.length; i++) {
		if (c[i].checked)
			return true;
	}
	return false;
}
function mail_chk(m) {
	return m.match("^[a-zA-Z0-9._/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$") != null;
}
//-->
</script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<!-- 設問 -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">【ここから、あなた自身のことについてお伺いします】<br></td>
	</tr>
</TABLE>
<!-- 選択肢 -->
<br>
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">　</td>
		<td class="font-qa" align="left">
			<table border=0 cellpadding=3 cellspacing=2 width="90%">
				<tr>
					<td valign=top>
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">■Ｆ１．<br>お住まいの都道府県をお選びください。</td></tr>
								<tr><td class="font-qa">
									<select name="f1">
									<option value=0>−選択肢よりお選びください−</option>
									<option value=1>北海道</option>
									<option value=2>青森県</option>
									<option value=3>岩手県</option>
									<option value=4>宮城県</option>
									<option value=5>秋田県</option>
									<option value=6>山形県</option>
									<option value=7>福島県</option>
									<option value=8>茨城県</option>
									<option value=9>栃木県</option>
									<option value=10>群馬県</option>
									<option value=11>埼玉県</option>
									<option value=12>千葉県</option>
									<option value=13>東京都</option>
									<option value=14>神奈川県</option>
									<option value=15>新潟県</option>
									<option value=16>富山県</option>
									<option value=17>石川県</option>
									<option value=18>福井県</option>
									<option value=19>山梨県</option>
									<option value=20>長野県</option>
									<option value=21>岐阜県</option>
									<option value=22>静岡県</option>
									<option value=23>愛知県</option>
									<option value=24>三重県</option>
									<option value=25>滋賀県</option>
									<option value=26>京都府</option>
									<option value=27>大阪府</option>
									<option value=28>兵庫県</option>
									<option value=29>奈良県</option>
									<option value=30>和歌山県</option>
									<option value=31>鳥取県</option>
									<option value=32>島根県</option>
									<option value=33>岡山県</option>
									<option value=34>広島県</option>
									<option value=35>山口県</option>
									<option value=36>徳島県</option>
									<option value=37>香川県</option>
									<option value=38>愛媛県</option>
									<option value=39>高知県</option>
									<option value=40>福岡県</option>
									<option value=41>佐賀県</option>
									<option value=42>長崎県</option>
									<option value=43>熊本県</option>
									<option value=44>大分県</option>
									<option value=45>宮崎県</option>
									<option value=46>鹿児島県</option>
									<option value=47>沖縄県</option>
									</select>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">■Ｆ２．性別</td></tr>
								<tr><td class="font-qa">
									<nobr><input type="radio" name="f2" value=1>男</nobr><br>
									<nobr><input type="radio" name="f2" value=2>女</nobr><br>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">■Ｆ３．年齢（半角数字で）</td></tr>
								<tr><td class="font-qa">
									<input type="text" name="f3" size=4 maxlength=3>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">■Ｆ４．未既婚</td></tr>
								<tr><td class="font-qa">
									<nobr><input type="radio" name="f4" value=1>未婚</nobr><br>
									<nobr><input type="radio" name="f4" value=2>既婚</nobr><br>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">■Ｆ５．職業</td></tr>
								<tr><td class="font-qa">
									<nobr><input type="radio" name="f5" value=1>会社員</nobr><br>
									<nobr><input type="radio" name="f5" value=2>専業主婦・主夫</nobr><br>
									<nobr><input type="radio" name="f5" value=3>学生</nobr><br>
									<nobr><input type="radio" name="f5" value=4>パート・アルバイト</nobr><br>
									<nobr><input type="radio" name="f5" value=5>自営業</nobr><br>
									<nobr><input type="radio" name="f5" value=6>公務員</nobr><br>
									<nobr><input type="radio" name="f5" value=7>専門職（SOHO・デザイナー・クリエイター等）</nobr><br>
									<nobr><input type="radio" name="f5" value=8>専門職（弁護士・医師・会計士等）</nobr><br>
									<nobr><input type="radio" name="f5" value=9>会社経営・役員</nobr><br>
									<nobr><input type="radio" name="f5" value=10>教職</nobr><br>
									<nobr><input type="radio" name="f5" value=11>無職</nobr><br>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">■Ｆ６．ご登録メールアドレス</td></tr>
								<tr><td class="font-qa">
									<input type="text" name="f6" size=50>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">■Ｆ７．回答用ＩＤ（ご案内メールに記載されております）</td></tr>
								<tr><td class="font-qa">
									<input type="text" name="f7" size=50>
								</td></tr>
						</table>
					</td>
				</tr>
			</table>
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
