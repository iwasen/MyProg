<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:スタッフ登録変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 権限選択肢
function select_privilege($sel) {
	global $g_privilege;

	echo "<option ", value_selected('', $sel), ">- 選択してください -</option>";
	echo "<option ", value_selected(PRIV_DHSTAFF, $sel), ">DHスタッフ</option>";
	echo "<option ", value_selected(PRIV_OPERATOR, $sel), ">オペレータ</option>";
}

// スタッフ状態選択肢
function select_staff_status($sel) {
	echo "<option ", value_selected('0', $sel), ">有効</option>";
	echo "<option ", value_selected('9', $sel), ">無効</option>";
}

// 入力パラメータ
$staff_id = $_GET['staff_id'];

// 登録済みスタッフ情報取得
$sql = "SELECT * FROM t_staff WHERE stf_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error("スタッフＩＤが不正", __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onload_body() {
	onclick_password();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}
function update_check(f) {
	if (f.mail_addr.value == "") {
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
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
	return confirm("スタッフ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("スタッフを削除します。よろしいですか？");
}
function onclick_password() {
	var f = document.form1;
	f.password.style.display = f.password_change.checked ? "" : "none";
}
//-->
</script>
</head>
<body onload="onload_body()">

<? center_header('マスタメンテナンス｜事務局スタッフ（オペレータ）マスタ｜登録変更') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■スタッフ情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=50 <?=value($fetch->stf_mail_addr)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード</td>
		<td class="n1">
			<input type="checkbox" name="password_change" value="1" onclick="onclick_password()">変更　
			<input type="password" name="password" maxlength=20 style="display:none">
		</td>
	</tr>
	<tr>
		<td class="m1">名前<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name" size=50 maxlength=25 <?=value($fetch->stf_name)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">権限<?=MUST_ITEM?></td>
		<td class="n1"><select name="privilege"><? select_privilege($fetch->stf_privilege)?></select></td>
	</tr>
	<tr>
		<td class="m1">署名</td>
		<td class="n1"><textarea name="signature" cols=76 rows=5><?=htmlspecialchars($fetch->stf_signature)?></textarea></td>
	</tr>
	<tr>
		<td class="m1">状態<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_staff_status($fetch->stf_status)?></select></td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="staff_id" <?=value($fetch->stf_staff_id)?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
