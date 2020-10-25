<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:モニター個人検索
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('staff', 'スタッフ管理', 'スタッフ個人検索', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.birthday_y.value != '') {
		if (f.birthday_y.value.length != 4 || !is_number(f.birthday_y.value)) {
			alert("生年月日の年には４桁の数字を入力してください。");
			f.birthday_y.focus();
			return false;
		}
	}
	return true;
}
function is_number(str) {
	return !isNaN(parseInt(str));
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="list.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td colspan=2>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0">■検索したいモニター情報を入力してください</td>
					<td align="right">
						<input type="radio" name="and_or" value="A" checked>AND
						<input type="radio" name="and_or" value="O">OR
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">メールアドレス</td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=40 maxlength=100>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（漢字）</td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size=25 maxlength=25>姓
			<br>
			<input class="kanji" type="text" name="name2" size=25 maxlength=25>名
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）</td>
		<td class="n1">
			<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25>姓
			<br>
			<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25>名
		</td>
	</tr>
	<tr>
		<td class="m1">生年月日</td>
		<td class="n1">
			西暦<input class="number" type="text" name="birthday_y" size=4>年
			<select name="birthday_m"><? select_month() ?></select>月
			<select name="birthday_d"><? select_day() ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="pset" value="1">
<input type="submit" value="　検索　">
<input type="button" value="キャンセル" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
