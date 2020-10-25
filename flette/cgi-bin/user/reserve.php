#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");
include("$inc/select.php");

// 第n希望選択
function select_choice($chice_num, $selected) {
	for ($i = 0; $i < $chice_num; $i++)
		option_tag($i, $selected, mb_convert_kana($i + 1, 'N'));
}

// カウンセラーの性別選択
function select_sex($selected) {
	option_tag('3', $selected, '特に希望しない');
	option_tag('1', $selected, '男性');
	option_tag('2', $selected, '女性');
}

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('/index.html');

for ($chice_num = 0; $chice_num < MAX_CHOICE; $chice_num++) {
	if ($reserve->choice[$chice_num]->room_id == 0)
		break;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function appl() {
	var f = document.form1;
	if (f["choice[]"].length) {
		for (i = 0; i < f["choice[]"].length; i++) {
			for (j = i + 1; j < f["choice[]"].length; j++) {
				if (f["choice[]"][i].selectedIndex == f["choice[]"][j].selectedIndex) {
					alert("ご希望順位が正しく選択されていないようです。");
					f["choice[]"][j].focus();
					return;
				}
			}
		}
	}
	if (f.name1.value == "") {
		alert("お名前（姓）が入力されていないようです。");
		f.name1.focus();
		return;
	}
	if (f.name2.value == "") {
		alert("お名前（名）が入力されていないようです。");
		f.name2.focus();
		return;
	}
	if (f.kana1.value == "") {
		alert("お名前（セイ）が入力されていないようです。");
		f.kana1.focus();
		return;
	}
	if (f.kana2.value == "") {
		alert("お名前（メイ）が入力されていないようです。");
		f.kana2.focus();
		return;
	}
	if (f.mail_addr.value == "") {
		alert("メールアドレスが入力されていないようです。");
		f.mail_addr.focus();
		return;
	}
	if (f.mail_addr2.value == "") {
		alert("メールアドレス（確認）が入力されていないようです。");
		f.mail_addr2.focus();
		return;
	}
	if (f.mail_addr.value != f.mail_addr2.value) {
		alert("入力されたメールアドレスが一致していないようです。");
		f.mail_addr.focus();
		return;
	}
	if (!f.mail_addr.value.match(/^[a-zA-Z0-9._\/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$/)) {
		alert("メールアドレスが正しくないようです。");
		f.mail_addr.focus();
		return;
	}
	if (!f.contact[0].checked && !f.contact[1].checked && !f.contact[2].checked) {
		alert("希望連絡先が選択されていないようです。");
		f.contact[0].focus();
		return;
	}
	if (f.contact[1].checked && f.tel_fax.value == "") {
		alert("TEL番号が入力されていないようです。");
		f.tel_fax.focus();
		return;
	}
	if (f.contact[2].checked && f.tel_fax.value == "") {
		alert("FAX番号が入力されていないようです。");
		f.tel_fax.focus();
		return;
	}
	if (f["counseling_id[]"]) {
		sel = false;
		if (f["counseling_id[]"].length) {
			for (i = 0; i < f["counseling_id[]"].length; i++) {
				if (f["counseling_id[]"][i].checked) {
					sel = true;
					break;
				}
			}
		} else {
			if (f["counseling_id[]"].checked)
				sel = true;
		}
		if (!sel) {
			alert("相談内容を１つ以上選択してください。");
			return;
		}
	}
	f.next.value = "confirm.php";
	f.submit();
}
//-->
</script>
</head>
<body>
予約必要項目入力
<br>
<form method="post" name="form1" action="reserve2.php">
<input type="hidden" name="next">
<table border=1 cellspacing=1>
	<tr>
		<td>予約コース</td>
		<td colspan=3>◆<?=$reserve->course?> <?=$reserve->shop?></td>
	</tr>
<?
for ($i = 0; $i < $chice_num; $i++) {
	$choice = &$reserve->choice[$i];
	$week = date('w', strtotime($choice->date));

	$sql = sprintf("SELECT sum(wk_sex_cd) as sex_cd"
			. " FROM t_waku"
			. " WHERE wk_room_id=%s AND wk_course_id=%s AND wk_date=%s AND wk_start_time=%s AND wk_end_time=%s",
			sql_number($choice->room_id),
			sql_number($reserve->course_id),
			sql_date($choice->date),
			sql_date($choice->start_time),
			sql_date($choice->end_time));
	$sex_cd = db_fetch1($sql);

?>
	<tr>
		<td>第<select name="choice[]"><? select_choice($chice_num, $i) ?></select>希望</td>
		<td><?=$choice->date?>（<?=decode_week($week)?>） <?=$choice->start_time?>〜<?=$choice->end_time?></td>
		<td><?=$choice->room?></td>
		<td>カウンセラーの性別：
<?
	if ($sex_cd == 1) {
?>
			男性<input type="hidden" name="sex[]" value="1">
<?
	} elseif ($sex_cd == 2) {
?>
			女性<input type="hidden" name="sex[]" value="2">
<?
	} else {
?>
			<select name="sex[]"><? select_sex($choice->sex) ?></select>
<?
	}
?>
		</td>
	</tr>
<?
}
?>
</table>
<hr>
<table border=1 cellspacing=1>
	<tr>
		<td>お名前</td>
		<td>
			姓<input type="text" name="name1" <?=value($reserve->name_1)?>>
			名<input type="text" name="name2" <?=value($reserve->name_2)?>>
			<br>
			セイ<input type="text" name="kana1" <?=value($reserve->kana_1)?>>
			メイ<input type="text" name="kana2" <?=value($reserve->kana_2)?>>
		</td>
	</tr>
	<tr>
		<td>メールアドレス</td>
		<td>
			<input type="text" name="mail_addr" <?=value($reserve->mail_addr)?>>
			<br>
			<input type="text" name="mail_addr2" <?=value($reserve->mail_addr)?>>
		</td>
	</tr>
	<tr>
		<td>希望連絡先</td>
		<td>
			<input type="radio" name="contact" <?=value_checked(1, $reserve->contact)?>>メール
			<input type="radio" name="contact" <?=value_checked(2, $reserve->contact)?>>TEL
			<input type="radio" name="contact" <?=value_checked(3, $reserve->contact)?>>FAX
			<br>
			TELまたはFAXをご指定の場合はTEL/FAX番号を入力してください。
			<input type="text" name="tel_fax" <?=value($reserve->tel . $reserve->fax)?>>
		</td>
	</tr>
	<tr>
		<td>相談内容</td>
		<td>
			<table border=0 cellspacing=1>
<?
$sql = "SELECT cs_counseling_id,cs_content"
		. " FROM m_counseling"
		. " WHERE cs_course_id=" . sql_number($reserve->course_id);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if ($i % 2 == 0) {
?>
				<tr>
<?
	}
?>
					<td><input type="checkbox" name="counseling_id[]" <?=value($fetch->cs_counseling_id)?> <?=checked(in_array($fetch->cs_counseling_id, $reserve->counseling_id))?>><?=htmlspecialchars($fetch->cs_content)?></td>
<?
	if ($i % 2 == 1) {
?>
				</tr>
<?
	}
}
if ($i % 2 == 1) {
?>
					<td></td>
				</tr>
<?
}
?>
			</table>
			<hr>
			<input type="radio" name="change" <?=value_checked(1, $reserve->change)?>>前回と同じカウンセラーを希望する
			<input type="radio" name="change" <?=value_checked(2, $reserve->change)?>>別のカウンセラーを希望する
		</td>
	</tr>
	<tr>
		<td>その他、ご要望など</td>
		<td><textarea name="comment" cols=60 rows=5><?=htmlspecialchars($reserve->comment)?></textarea></td>
</table>
<br>
<input type="button" value="予約申込み" onclick="appl()">
<input type="button" value="　戻る　" onclick="location.href='choice.php'">
</form>
</body>
</html>
