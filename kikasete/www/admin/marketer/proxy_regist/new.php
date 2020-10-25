<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:有料マーケター代理登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// メイン処理
set_global('marketer', 'マーケター管理', '有料マーケター代理登録', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.name1.value == '') {
		alert("名前（漢字）を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == '') {
		alert("名前（漢字）を入力してください。");
		f.name2.focus();
		return false;
	}
	if (f.name1_kana.value == '') {
		alert("名前（カナ）を入力してください。");
		f.name1_kana.focus();
		return false;
	}
	if (f.name2_kana.value == '') {
		alert("名前（カナ）を入力してください。");
		f.name2_kana.focus();
		return false;
	}
	if (f.birthday_y.value == '') {
		alert("生年月日を入力してください。");
		f.birthday_y.focus();
		return false;
	}
	if (!f.sex[0].checked && !f.sex[1].checked) {
		alert("性別を選択してください。");
		f.sex[0].focus();
		return false;
	}
	if (f.mail_addr.value == '') {
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == '') {
		alert("パスワードを入力してください。");
		f.password.focus();
		return false;
	}
	if (f.shokugyou_cd.value == '') {
		alert("職業を選択してください。");
		f.shokugyou_cd.focus();
		return false;
	}
	if (f.gyoushu_cd.value == '') {
		alert("業種を選択してください。");
		f.gyoushu_cd.focus();
		return false;
	}
	if (f.shokushu_cd.value == '') {
		alert("職種を選択してください。");
		f.shokushu_cd.focus();
		return false;
	}
	if (f.kinmu_name.value == '') {
		alert("会社名を入力してください。");
		f.kinmu_name.focus();
		return false;
	}
	if (f.kinmu_zip1.value == '') {
		alert("勤務先郵便番号を入力してください。");
		f.kinmu_zip1.focus();
		return false;
	}
	if (f.kinmu_zip2.value == '') {
		alert("勤務先郵便番号を入力してください。");
		f.kinmu_zip2.focus();
		return false;
	}
	if (f.kinmu_area.value == '') {
		alert("勤務先地域を選択してください。");
		f.kinmu_area.focus();
		return false;
	}
	if (f.kinmu_addr1.value == '') {
		alert("勤務先住所（市区）を入力してください。");
		f.kinmu_addr1.focus();
		return false;
	}
	if (f.kinmu_addr2.value == '') {
		alert("勤務先住所（町村）を入力してください。");
		f.kinmu_addr2.focus();
		return false;
	}
	if (f.kinmu_tel.value == '') {
		alert("勤務先電話番号を入力してください。");
		f.kinmu_tel.focus();
		return false;
	}
	if (f.agent_id.value == '') {
		alert("エージェントを選択してください。");
		f.agent_id.focus();
		return false;
	}
	return confirm("有料マーケターを登録します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■基本情報</td>
	</tr>
	<tr>
		<td class="m1">名前（漢字）<?=MUST_ITEM?></td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1" size=25 maxlength=25>
			<span class="note">（全角２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2" size=25 maxlength=25>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）<?=MUST_ITEM?></td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25>
			<span class="note">（全角カタカナ２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25>
			<span class="note">（全角カタカナ２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">生年月日<?=MUST_ITEM?></td>
		<td class="n1">
			西暦<input class="number" type="text" name="birthday_y" size=4>年
			<select name="birthday_m"><? select_month() ?></select>月
			<select name="birthday_d"><? select_day() ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">性別<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" value="1">男性
			<input type="radio" name="sex" value="2">女性
		</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">職業<?=MUST_ITEM?></td>
		<td class="n1"><select name="shokugyou_cd"><? select_shokugyou('- 未設定 -')?></select></td>
	</tr>
	<tr>
		<td class="m1">業種<?=MUST_ITEM?></td>
		<td class="n1"><select name="gyoushu_cd"><? select_gyoushu('- 未設定 -', '', 2)?></select></td>
	</tr>
	<tr>
		<td class="m1">職種<?=MUST_ITEM?></td>
		<td class="n1"><select name="shokushu_cd"><? select_shokushu('- 未設定 -')?></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■勤務先情報</td>
	</tr>
	<tr>
		<td class="m1">会社名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">部署名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">役職</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先郵便番号<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="kinmu_zip1" size=3 maxlength=3>
			-
			<input class="number" type="text" name="kinmu_zip2" size=4 maxlength=4>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先地域<?=MUST_ITEM?></td>
		<td class="n1"><select name="kinmu_area"><? select_area('- 未設定 -')?></select></td>
	</tr>
	<tr>
		<td class="m1">勤務先住所（市区）<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr1" size=80 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先住所（町村）<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr2" size=80 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先住所（ビル名など）</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr3" size=80 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先電話番号<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_tel" size=20 maxlength=20>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">勤務先FAX番号</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_fax" size=20 maxlength=20>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m1">エージェント<?=MUST_ITEM?></td>
		<td class="n1"><select name="agent_id"><? select_agent('- 未設定 -', '', AG_MAIN)?></select></td>
	</tr>
<? /*
	<tr>
		<td class="m1">希望メールアカウント<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="text" name="choice_email1" size=20>
		</td>
	</tr>
*/ ?>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
