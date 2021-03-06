<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:企業マスタ登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/select.php");

//メイン処理

set_global2('project', 'プロジェクト管理｜マスタ登録', '企業情報登録', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	document.form1.cl_name.focus();
}
function onSubmit_form1(f) {
	if (f.cl_name.value == "") {
		alert("企業名を入力してください。");
		f.cl_name.focus();
		return false;
	}
	if (f.cl_name_kana.value == "") {
		alert("企業名（カナ）を入力してください。");
		f.cl_name_kana.focus();
		return false;
	}
	return confirm("企業情報を登録します。よろしいですか？");
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="onLoad_body()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="cl_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0 width=700>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td align="center" class="m4"><?=$g_title?></td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td class="m0" colspan=2>■登録する企業情報を入力してください</td>
				</tr>
				<tr>
					<td class="m1">企業名<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="kanji" type="text" name="cl_name" size=70 maxlength=50>
						<span class="note">（全角５０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">企業名(カナ)<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="kanji" type="text" name="cl_name_kana" size=70 maxlength=50>
						<span class="note">（全角５０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">郵便番号</td>
					<td class="n1">
						<input class="number" type="text" name="cl_zip1" size=4 maxlength=3>-
						<input class="number" type="text" name="cl_zip2" size=5 maxlength=4>
						<span class="note">（半角数字）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">住所</td>
					<td class="n1">
						<select name="cl_address1"><?select_area('選択して下さい', '')?>
						</select>
						<br>
						<input class="kanji" type="text" name="cl_address2" size=70 maxlength=50>
						<span class="note">（市区）</span>
						<br>
						<input class="kanji" type="text" name="cl_address3" size=70 maxlength=50>
						<span class="note">（町村番地）</span>
						<br>
						<input class="kanji" type="text" name="cl_address4" size=70 maxlength=50>
						<span class="note">（ビル名）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">業種</td>
					<td class="n1">
						<select name="cl_gyoushu_cd"><?select_gyoushu('選択して下さい', '', 2)?>
						</select>
					</td>
				</tr>
			</table><br>
			<div align="center">
			<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
			<input type="reset" value="リセット">
			<input type="button" value="　戻る　" onclick="history.back()">
			<input type="hidden" name="next_action" value="new">
			<input type="hidden" name="back_url" <?=value($back_url)?>>
			</div>
		</td>
	<tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
