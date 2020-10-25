<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:事務局スタッフ新規登録画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");

// 権限選択肢
function select_privilege() {
	echo "<option ", value(''), ">- 選択してください -</option>";
	echo "<option ", value(PRIV_DHSTAFF), ">DHスタッフ</option>";
	echo "<option ", value(PRIV_OPERATOR), ">オペレータ</option>";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.mail_addr.value == "") {
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("パスワードを入力してください。");
		f.password.focus();
		return false;
	}
	if (f.name.value == "") {
		alert("名前を入力してください。");
		f.name.focus();
		return false;
	}
	if (f.privilege.selectedIndex == 0) {
		alert("権限を選択してください。");
		f.privilege.focus();
		return false;
	}
	return confirm("スタッフを登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.mail_addr.focus()">

<? center_header('マスタメンテナンス｜事務局スタッフ（オペレータ）マスタ｜新規登録') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■登録するスタッフ情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=50>
			<span class="note">（半角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" maxlength=20>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=50 maxlength=25>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">権限<?=MUST_ITEM?></td>
		<td class="n1"><select name="privilege"><?=select_privilege()?></select></td>
	</tr>
	<tr>
		<td class="m1">署名</td>
		<td class="n1"><textarea name="signature" cols=76 rows=5></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
