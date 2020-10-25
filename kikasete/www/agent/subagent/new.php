<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:サブエージェント新規登録
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// エージェント識別
function select_agent_disc($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>○○○○○○社員</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>バイター</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>協力会社社員</option>', "\n";
}

// メイン処理
set_global('サブエージェント新規登録', BACK_TOP);
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
	if (f.name1.value == "") {
		alert("名前（漢字）を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("名前（漢字）を入力してください。");
		f.name2.focus();
		return false;
	}
	if (f.name1_kana.value == "") {
		alert("名前（カナ）を入力してください。");
		f.name1_kana.focus();
		return false;
	}
	if (f.name2_kana.value == "") {
		alert("名前（カナ）を入力してください。");
		f.name2_kana.focus();
		return false;
	}
	if (f.mail_addr.value == "") {
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
		return false;
	}
	if (f.mail_addr_copy.value == "") {
		alert("メールアドレス確認を入力してください。");
		f.mail_addr_copy.focus();
		return false;
	}
	if (f.mail_addr.value != f.mail_addr_copy.value) {
		alert("メールアドレスが一致していません。");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("パスワードを入力してください。");
		f.password.focus();
		return false;
	}
	if (f.password_copy.value == "") {
		alert("パスワード確認を入力してください。");
		f.password.focus();
		return false;
	}
	if (f.password.value != f.password_copy.value) {
		alert("パスワードが一致していません。");
		f.password_copy.focus();
		return false;
	}
	if (f.agent_disc.value == "") {
		alert("エージェント識別を選択してください。");
		f.agent_disc.focus();
		return false;
	}
	return confirm("サブエージェントを登録します。よろしいですか？");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td align="center" class="m4" colspan=2><b>サブエージェント登録</b></td>
	</tr>
	<tr>
		<td class="m0" colspan=2 align="left">■基本情報</td>
	</tr>
	<tr>
		<td class="m1" width="20%">名前（漢字）<?=MUST_ITEM?></td>
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
		<td class="m1">生年月日</td>
		<td class="n1">
			西暦<input class="number" type="text" name="birthday_y" size=4>年
			<select name="birthday_m"><?select_month(' ', '')?></select>月
			<select name="birthday_d"><?select_day(' ', '')?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">性別</td>
		<td class="n1">
			<input type="radio" name="sex" value=1>男性
			<input type="radio" name="sex" value=2>女性
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
		<td class="m1">メールアドレス確認<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr_copy" size=50 maxlength=100>
			<span class="note"><nobr>（確認のため、もう一度メールアドレスを入力してください。）</nobr></span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="password" name="password">
			<span class="note">（半角英数字６〜２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード確認<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="password" name="password_copy">
			<span class="note">（確認のため、もう一度パスワードを入力してください。）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">エージェント識別<?=MUST_ITEM?></td>
		<td class="n1"><select name="agent_disc"><? select_agent_disc('選択して下さい', '')?></select></td>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■勤務先情報</td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
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
		<td class="m1">エージェント所属部署</td>
		<td class="n1">
			<input class="kanji" type="text" name="agent_unit" size=50 maxlength=50>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
</table>
<br>
<div align="center">
	<input type="hidden" name="next_action">
	<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
	<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
