<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:モニター個人検索
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/lifeinfo.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// 謝礼支払い方法
function select_payment_type($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>iMiポイント</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>郵便為替</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>ネットバンク</option>', "\n";
}

// メイン処理
set_global('monitor', 'モニター管理', 'モニター個人検索', BACK_TOP);

$sql = "SELECT * FROM t_monitor LEFT JOIN t_monitor_point ON mp_monitor_id=mn_monitor_id WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error('モニターＩＤが不正', __FILE__);
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
	if (f.name1.value == "") {
		alert("名前（漢字）の姓を入力してください。");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("名前（漢字）の名を入力してください。");
		f.name2.focus();
		return false;
	}
	if (f.birthday_y.value == "") {
		alert("生年月日を入力してください。");
		f.birthday_y.focus();
		return false;
	}
	if (f.mikikon[0].checked == false && f.mikikon[1].checked == false) {
		alert("未既婚を選択してください。");
		f.mikikon[0].focus();
		return false;
	}
	if (f.sex[0].checked == false && f.sex[1].checked == false) {
		alert("性別を選択してください。");
		f.sex[0].focus();
		return false;
	}
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
	if (f.shokugyou_cd.value == "") {
		alert("職業を選択してください。");
		f.shokugyou_cd.focus();
		return false;
	}
	return confirm("モニター登録情報を更新します。よろしいですか？");
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
			姓<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->mn_name1)?>>
			<span class="note">（全角２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->mn_name2)?>>
			<span class="note">（全角２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">名前（カナ）</td>
		<td class="n1">
			姓<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->mn_name1_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
			<br>
			名<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->mn_name2_kana)?>>
			<span class="note">（全角カタカナ２５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">生年月日<?=MUST_ITEM?></td>
		<td class="n1">
			西暦<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->mn_birthday))?>>年
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->mn_birthday))?></select>月
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->mn_birthday))?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">未既婚<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="mikikon" <?=value_checked(1, $fetch->mn_mikikon)?>>未婚
			<input type="radio" name="mikikon" <?=value_checked(2, $fetch->mn_mikikon)?>>既婚
		</td>
	</tr>
	<tr>
		<td class="m1">性別<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->mn_sex)?>>男性
			<input type="radio" name="sex" <?=value_checked(2, $fetch->mn_sex)?>>女性
		</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->mn_mail_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->mn_password)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">郵便番号（自宅）</td>
		<td class="n1">
			<input class="number" type="text" name="jitaku_zip1" size=3 maxlength=3 <?=value(substr($fetch->mn_jitaku_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="jitaku_zip2" size=4 maxlength=4 <?=value(substr($fetch->mn_jitaku_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">居住地域（自宅）<?=MUST_ITEM?></td>
		<td class="n1"><select name="jitaku_area"><? select_area('- 未設定 -', $fetch->mn_jitaku_area)?></select></td>
	</tr>
	<tr>
		<td class="m1">自宅住所２</td>
		<td class="n1">
			<input class="kanji" type="text" name="jitaku_addr2" size=80 maxlength=50 <?=value($fetch->mn_jitaku_addr2)?>>
			<span class="note">（全角５０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">職業</td>
		<td class="n1"><select name="shokugyou_cd"><? select_shokugyou('- 未設定 -', $fetch->mn_shokugyou_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">チェーン</td>
		<td class="n1"><?checkbox_chain($monitor_id, 4)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■その他情報</td>
	</tr>
	<tr>
		<td class="m1">状態</td>
		<td class="n1"><select name="status"><? select_monitor_status($fetch->mn_status)?></select></td>
	</tr>
	<tr>
		<td class="m1">ポイント</td>
		<td class="n1">
			現ポイント<input class="number" type="text" name="curr_point" size=10 maxlength=10 <?=value($fetch->mp_curr_point)?>>
			累積ポイント<input class="number" type="text" name="total_point" size=10 maxlength=10 <?=value($fetch->mp_total_point)?>>
			　<input type="checkbox" name="point_change" value="1">変更
		</td>
	</tr>
	<tr>
		<td class="m1">謝礼支払い方法</td>
		<td class="n1"><select name="payment_type"><? select_payment_type('- 未設定 -', $fetch->mn_payment_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">口座番号</td>
		<td class="n1"><input class="number" type="text" name="account_num" size=20 maxlength=20 <?=value($fetch->mn_account_num)?>></td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->mn_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最新更新日時</td>
		<td class="n1"><?=format_datetime($fetch->mn_update_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="monitor_id" <?=value($monitor_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
