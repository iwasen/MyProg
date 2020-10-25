<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:事務局admin登録変更画面
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// 管理者状態選択肢
function select_admin_status($sel) {
	echo "<option ", value_selected('0', $sel), ">有効</option>";
	echo "<option ", value_selected('9', $sel), ">無効</option>";
}

// メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', '管理者マスタ', BACK_TOP);

$sql = "SELECT * FROM m_admin WHERE ad_admin_id=$admin_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('管理者ＩＤが不正', __FILE__);
$fetch = pg_fetch_object($result, 0);
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
	if (f.password.value == "") {
		alert("パスワードを入力してください。");
		f.password.focus();
		return false;
	}
	if (f.name1.value == "") {
		alert("名前（漢字）を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name1_kana.value == "") {
		alert("名前（カナ）を入力してください。");
		f.name1_kana.focus();
		return false;
	}
	return confirm("管理者情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("管理者を削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■管理者情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->ad_mail_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="password" name="password" maxlength=20 <?=value($fetch->ad_password)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（漢字）<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->ad_name1)?>>姓
			<span class="note">（全角２５文字まで）</span>
			<br>
			<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->ad_name2)?>>名
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->ad_name1_kana)?>>姓
			<span class="note">（全角カタカナ２５文字まで）</span>
			<br>
			<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->ad_name2_kana)?>>名
			<span class="note">（全角カタカナ２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">生年月日</td>
		<td class="n1">
			西暦<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->ad_birthday))?>>年
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->ad_birthday))?></select>月
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->ad_birthday))?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">性別<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->ad_sex)?>>男性
			<input type="radio" name="sex" <?=value_checked(2, $fetch->ad_sex)?>>女性
		</td>
	</tr>
	<tr>
		<td class="m1">企業名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->ad_kinmu_name)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">部署名</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->ad_kinmu_unit)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">役職</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->ad_kinmu_post)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">状態<?=MUST_ITEM?></td>
		<td class="n1"><select name="status"><? select_admin_status($fetch->ad_status) ?></select></td>
	</tr>
</table>
<br>
<input type="hidden" name="admin_id" <?=value($admin_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
