<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:スタッフ登録リスト 
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// 種別別所属選択
function select_staff_org2($default, $selected, $kind) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	if ($kind != '') {
		$where = "WHERE so_kind_cd=$kind";
		$sql = "SELECT so_org_cd,so_name FROM m_staff_org $where ORDER BY so_name_kana";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			echo '<option ', value_selected($fetch->so_org_cd, $selected), '>', htmlspecialchars($fetch->so_name), '</option>', "\n";
		}
	}
}

// メイン処理
set_global('staff', 'スタッフ情報管理', 'スタッフ登録リスト', BACK_TOP);

if ($page == '') {
	$sub = "SELECT so_kind_cd FROM m_staff_org WHERE so_org_cd=st_organization";
	$sql = "SELECT *, ($sub) AS staff_kind FROM m_staff WHERE st_staff_id = $st_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		system_error("スタッフIDが不正", __FILE__);
	$fetch = pg_fetch_object($result, 0);
	$st_password = $fetch->st_password;
	$st_status = $fetch->st_status;
	$st_name1 = $fetch->st_name1;
	$st_name2 = $fetch->st_name2;
	$st_name1_kana = $fetch->st_name1_kana;
	$st_name2_kana = $fetch->st_name2_kana;
	$st_mail_addr = $fetch->st_mail_addr;
	$st_zip1 = substr($fetch->st_zip_code,0,3);
	$st_zip2 = substr($fetch->st_zip_code,3,4);
	$st_address = $fetch->st_address;
	$st_tel_no = $fetch->st_tel_no;
	$st_priority_rank = $fetch->st_priority_rank;
	$st_level_rank = $fetch->st_level_rank;
	$st_organization = $fetch->st_organization;
	$st_from_date_y = get_datepart('Y',$fetch->st_from_date);
	$st_from_date_m = get_datepart('M',$fetch->st_from_date);
	$st_from_date_d = get_datepart('D',$fetch->st_from_date);
	$st_to_date_y = get_datepart('Y',$fetch->st_to_date);
	$st_to_date_m = get_datepart('M',$fetch->st_to_date);
	$st_to_date_d = get_datepart('D',$fetch->st_to_date);
	$staff_kind = $fetch->staff_kind;
	$page = 'next';
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function check_number(s) {
	var okStr = "0123456789 ";
	var ok = true;
	for (var i = 0; i < s.length; i++) {
		if (okStr.indexOf(s.charAt(i)) == -1) {
			ok = false;
			break;
		}
	}
	return ok;
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
	if (f.staff_kind.value != '' && f.st_organization.value == ''){
		alert("所属を選択してください。");
		f.st_organization.focus();
		return false;
	}
	if (f.st_name1.value == "") {
		alert("スタッフ名を入力してください。");
		f.st_name1.focus();
		return false;
	}
	if (f.st_mail_addr.value == "") {
		alert("メールアドレスを入力してください。");
		f.st_mail_addr.focus();
		return false;
	}
	if (f.st_password.value == "") {
		alert("パスワードを入力してください。");
		f.st_password.focus();
		return false;
	}
	if (f.st_priority_rank.value == "") {
		alert("優先度ランクを入力してください。");
		f.st_priority_rank.focus();
		return false;
	}
	if (!check_number(f.st_priority_rank.value)) {
		alert("優先度ランクは数値で入力してください。");
		f.st_priority_rank.value = '';
		f.st_priority_rank.focus();
		return false;
	}
	if (f.st_level_rank.value == "") {
		alert("レベルランクを入力してください。");
		f.st_level_rank.focus();
		return false;
	}
	if (!check_number(f.st_level_rank.value)) {
		alert("レベルランクは数値で入力してください。");
		f.st_level_rank.value = '';
		f.st_level_rank.focus();
		return false;
	}
	f.action = "update.php";
	return confirm("スタッフ情報を更新します。よろしいですか？");
}

function delete_check(f) {
	f.action = "update.php";
	return confirm("スタッフ情報を削除します。よろしいですか？");
}

function OnChange_kind() {
	var f = document.form1;
	f.st_organization.value = '';
	f.action = "edit.php";
	f.submit();
}
//-->
</script>
</head>
<body onload="document.form1.staff_kind.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録するスタッフ情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width=30%>所属種別</td>
		<td class="n1" width=70%>
			<select name="staff_kind" onchange="OnChange_kind()"><?=select_staff_kind('選択してください', $staff_kind)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">所属</td>
		<td class="n1">
			<select name="st_organization"><?=select_staff_org2('選択してください', $st_organization, $staff_kind)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">氏名<?=MUST_ITEM?></td>
		<td class="n1">
			<span class="note">姓</span>
			<input class="kanji" type="text" name="st_name1" size=15 maxlength=20 <?=value($st_name1)?>>
			<span class="note">名</span>
			<input class="kanji" type="text" name="st_name2" size=15 maxlength=20 <?=value($st_name2)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">氏名（カナ）</td>
		<td class="n1">
			<span class="note">セイ</span>
			<input class="kanji" type="text" name="st_name1_kana" size=15 maxlength=20 <?=value($st_name1_kana)?>>
			<span class="note">メイ</span>
			<input class="kanji" type="text" name="st_name2_kana" size=15 maxlength=20 <?=value($st_name2_kana)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">メールアドレス<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="st_mail_addr" size=50 <?=value($st_mail_addr)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">パスワード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="st_password" size=50 <?=value($st_password)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">郵便番号</td>
		<td class="n1">
			<input class="number" type="text" name="st_zip1" size=3 maxlength=3 <?=value($st_zip1)?>>-
			<input class="number" type="text" name="st_zip2" size=4 maxlength=4 <?=value($st_zip2)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">住所</td>
		<td class="n1">
			<input class="kanji" type="text" name="st_address" size=50 <?=value($st_address)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">電話番号</td>
		<td class="n1">
			<input class="alpha" type="text" name="st_tel_no" size=50 <?=value($st_tel_no)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">依頼可能開始日</td>
		<td class="n1">
			<select name="st_from_date_y"><? select_year('2003', '', $st_from_date_y != '' ? $st_from_date_y : date(Y)) ?></select>年
			<select name="st_from_date_m"><? select_month('', $st_from_date_m != '' ? $st_from_date_m : date(m)) ?></select>月
			<select name="st_from_date_d"><? select_day('', $st_from_date_d != '' ? $st_from_date_d : date(d)) ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">依頼可能終了日</td>
		<td class="n1">
			<select name="st_to_date_y"><? select_year('2003', '', $st_to_date_y != '' ? $st_to_date_y : date(Y)) ?></select>年
			<select name="st_to_date_m"><? select_month('', $st_to_date_m != '' ? $st_to_date_m : date(m)) ?></select>月
			<select name="st_to_date_d"><? select_day('', $st_to_date_d != '' ? $st_to_date_d : date(d)) ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">優先度ランク<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="st_priority_rank" size=2 maxlength=2 <?=value($st_priority_rank)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">レベルランク<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="st_level_rank"><?=select_level_rank('', $st_level_rank)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">状態<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="st_status"><?=select_judge('', $st_status)?></select>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="st_id" <?=value($st_id)?>>
<input type="hidden" name="page" <?=value($page)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="kind">
</form>
</div>

<? page_footer() ?>
</body>
</html>
