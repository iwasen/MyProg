<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_melonpan;
$title_color = $TC_MASTER;

$mag_id = "000049";

//=== 「めろんぱんレター」volume ===
$sql = "SELECT COUNT(*) as mag_count FROM L_SEND WHERE mag_id='$mag_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mag_count = $fetch->mag_count;
	$mag_volume =  sprintf("%03d", $mag_count + 1);
}

//=== 発行部数 ===
$sql = "SELECT COUNT(*) as reader_count FROM T_MAILADDR WHERE mag_id='$mag_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$reader_count = $fetch->reader_count;
	$reader_count =  number_format($reader_count);
}

//=== おすすめメルマガ表示 ===
for($i = 0;$i < count($osusume_mag_id); $i++) {
	if ($osusume_mag_id[$i] == $osusume_keisai[$i]) {
	$osusume_mag .= "'" . $osusume_keisai[$i] . "',";
	}
}

if ($osusume_mag <> "") {
	$osusume_mag = substr($osusume_mag, 0, strlen($osusume_mag) - 1);
	$sql = "SELECT MM.mag_id, MM.mag_nm, MM.mag_cycle_day, MM.mag_intro_short, OL.reason, MP.melonpai_nic"
		. " FROM M_MAGAZINE MM, T_OSUSUME_LIST OL, M_MELONPAI MP"
		. " WHERE MM.mag_id IN (" . $osusume_mag . ") AND OL.date_to IS NULL AND MM.mag_id=OL.mag_id AND OL.melonpai_id=MP.melonpai_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	for ($j = 0; $j < $nrow; $j++) {
		$fetch = pg_fetch_object($result, $j);
		$mag_osusume_intro = "";
		$mag_osusume_msg = "";
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$mag_cycle_day = $fetch->mag_cycle_day;
		$mag_intro_short = $fetch->mag_intro_short;
		$reason = $fetch->reason;
		$reason = replace_return($reason);
		$melonpai_nic = $fetch->melonpai_nic;
		$melonpai_nic = replace_return($melonpai_nic);

		//タイトル+発行周期
		$mag_nm = trim($mag_nm);
		$reason = trim($reason);
		$mag_intro_short = $mag_intro_short . " ";
		$mag_title_str = "　◎" . trim($mag_nm) . "【" . $mag_cycle_day . "】";
		$mag_cycle = "【" . $mag_cycle_day . "】";
		$mag_nm_len = mbstrlen($mag_title_str);
		if ($mag_nm_len <= 34) {
			$mag_title = "　◎" . $mag_nm . str_pad($mag_cycle, 64 - strlen($mag_nm), " ", STR_PAD_LEFT);
		} elseif (mbstrlen("　◎" . $mag_nm) <= 34) {
			$mag_title = "　◎" . $mag_nm . "\n" . str_pad($mag_cycle, 68, " ", STR_PAD_LEFT);
		} elseif (mbstrlen("　◎" . $mag_nm) > 34) {
			$mag_title = mbsubstr("　◎" . $mag_nm, 0, 34) . "\n"
							. "　　" . mbsubstr("　◎" . $mag_nm, 34) . str_pad($mag_cycle, 64 - strlen(trim(mbsubstr("　◎" . $mag_nm, 34))), " ", STR_PAD_LEFT);
		}

		//メルマガ紹介文（ショート）
		for ($i = 0;$i < mbstrlen($mag_intro_short); $i = $i + 32) {
			$mag_osusume_intro .= "　　" . mbsubstr($mag_intro_short, $i, 32) . "\n";
		}

		//おすすめ理由
		if (mbstrlen($reason) < 32) {
			$mag_osusume_msg .= "　「" . $reason . "」\n";
		} else {
			$mag_osusume_msg .= "　「" . mbsubstr($reason , 0, 32) . "\n";
			for ($i = 32;$i < mbstrlen("「" . $reason . "」"); $i = $i + 32) {
				$mag_osusume_msg .= "　　" . mbsubstr($reason . "」 " , $i, 32) . "\n";
			}
		}
//		$mag_osusume_msg .= "」\n";

		//おすすめメルマガ情報
		$mag_body .= "〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜\n"
	 			. $mag_title . "\n"
	 			. "     http://www.melonpan.net/mag.php?" . $mag_id . "\n\n"
				. $mag_osusume_intro . "\n"
				. "　⇒⇒メルマガナビ“" . $melonpai_nic . "”からのメッセージ\n"
				. $mag_osusume_msg;
	}
}
//=== 新着メルマガ表示 ===
for($i = 0;$i < count($new_mag_id); $i++) {
	if ($new_mag_id[$i] == $new_keisai[$i]) {
	$new_mag .= "'" . $new_keisai[$i] . "',";
	}
}
$new_mag_body = ""; $catg1_temp = "";
if ($new_mag <> "") {
	$new_mag = substr($new_mag, 0, strlen($new_mag) - 1);

//新着メルマガ日付範囲
	$sql= "SELECT to_char(MIN(mag_agr_dt), 'YYYY/MM/DD') as date_from, to_char(MAX(mag_agr_dt), 'MM/DD') as date_to"
		. " FROM M_MAGAZINE"
		. " WHERE mag_id IN (" . $new_mag . ")";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$date_from = $fetch->date_from;
		$date_to = $fetch->date_to;
	}
	$mag_date_range = $date_from . "〜" . $date_to;
	
	
	$sql = "SELECT mag_id, mag_nm, mag_cycle_day, mag_intro_long, SUBSTR(catg_id, 1, 2) as catg1, catg"
		. " FROM M_MAGAZINE, M_MAG_CATG"
		. " WHERE mag_id IN (" . $new_mag . ") AND RPAD(SUBSTR(mag_catg_1, 1, 2), 4, '0')=catg_id"
		. " ORDER BY catg1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($j = 0; $j < $nrow; $j++) {
		$fetch = pg_fetch_object($result, $j);
		$mag_new_intro = "";
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$mag_cycle_day = $fetch->mag_cycle_day;
		$mag_intro_long = $fetch->mag_intro_long;
		$catg1 = $fetch->catg1;
		$catg = $fetch->catg;
		$mag_intro_long = replace_return($mag_intro_long);

		//タイトル+発行周期
		$mag_nm = trim($mag_nm);
		$mag_title_str = "　●" . trim($mag_nm) . "【" . $mag_cycle_day . "】";
		$mag_cycle = "【" . $mag_cycle_day . "】";
		$mag_nm_len = mbstrlen($mag_title_str);
		if ($mag_nm_len <= 34) {
			$mag_title = "　●" . $mag_nm . str_pad($mag_cycle, 64 - strlen($mag_nm), " ", STR_PAD_LEFT);
		} elseif (mbstrlen("　●" . $mag_nm) <= 34) {
			$mag_title = "　●" . $mag_nm . "\n" . str_pad($mag_cycle, 68, " ", STR_PAD_LEFT);
		} elseif (mbstrlen("　●" . $mag_nm) > 34) {
			$mag_title = mbsubstr("　●" . $mag_nm, 0, 34) . "\n"
							. "　　" . mbsubstr("　●" . $mag_nm, 34) . str_pad($mag_cycle, 64 - strlen(trim(mbsubstr("　◎" . $mag_nm, 34))), " ", STR_PAD_LEFT);
		}

		//メルマガ紹介文（ロング）
		$mag_intro_long = trim($mag_intro_long);
		for ($i = 0;$i < mbstrlen($mag_intro_long); $i = $i + 33) {
			$mag_new_intro .= "　　" . mbsubstr($mag_intro_long . " ", $i, 33) . "\n";
		}
		
		//新着メルマガ情報
		if ($catg1_temp <> $catg1) {
			$new_mag_body .= "\n>" . $catg . "\n"
				. " ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
		}
		$new_mag_body	.= $mag_title . "\n"
			. "      http://www.melonpan.net/mag.php?" . $mag_id . "\n"
			. $mag_new_intro . "\n";

	$catg1_temp = $catg1;
	}
}

$sql = "SELECT * FROM M_MAIL_TEMPLATE WHERE MT_id='melonpan'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$guide = $fetch->mt_guide;
	$subject = $fetch->mt_subject;
	$from = $fetch->mt_from;
	$cc = $fetch->mt_cc;
	$bcc = $fetch->mt_bcc;
	$body = $fetch->mt_body;
	$body = str_replace("@mag_volume@", $mag_volume, $body);
	$body = str_replace("@reader_count@", $reader_count, $body);
	$body = str_replace("@mag_date_range@", $mag_date_range, $body);
	$body = str_replace("@mag_osusume@", $mag_body, $body);
	$body = str_replace("@mag_new@", $new_mag_body, $body);

}



?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
		if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
		return true;
	else
		return false;
	}
}

function OnSubmit_form1() {
  with (document.form1) {
    if (subject.value == "") {
      alert("件名を入力してください。");
      subject.focus();
      return false;
    }
    if (body.value == "") {
      alert("本文を入力してください。");
      body.focus();
      return false;
    }
		if (!check_date(reserve_year.value, reserve_month.value, reserve_day.value)) {
			alert("日付の指定が正しくありません。");
			reserve_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate(), dd.getHours(), dd.getMinutes());
		var dt = new Date(reserve_year.value, reserve_month.value - 1, reserve_day.value, reserve_hour.value, reserve_minutes.value);
		if (dd > dt) {
			alert("過去の日時付は指定できません。");
			reserve_minutes.focus();
			return(false);
		}
  }
  return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" style="margin:0" action="letter_melonpan3.php" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■メール情報<font size=-1>（※登録時に、メルマガ発行者宛てに「掲載のお知らせ」が送信されます。）</font></td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4" width="20%">種類</td>
      <td class="n6"><?= htmlspecialchars($guide) ?></td>
    </tr>
    <tr>
      <td class="m4">件名</td>
      <td class="n6"><input type="text" name="subject" size=80 value="<?= htmlspecialchars($subject) ?>"></td>
    </tr>
    <tr>
      <td class="m4">本文</td>
      <td class="n6"><textarea cols=80 rows=30 name="body" class="np"><?= htmlspecialchars($body) ?></textarea></td>
    </tr>
    </tr>
<?php
$sql = "SELECT admin_msg FROM T_MAG_FOOTER WHERE access_day=TO_CHAR(now(), 'YYYYMMDD') AND letter_footer=1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$admin_msg = $fetch->admin_msg;
	$auto_footer = "<xmp>" . $admin_msg . "</xmp>";
} else {
	$auto_footer = "<font color=\"gray\" size=-1>--本日はありません--</font>";
}
?>
		<tr>
      <td class="m4">自動挿入フッタ</td>
      <td class="n6"><?= $auto_footer ?></td>
    </tr>
		<tr>
      <td class="m4">配信予約日時</td>
      <td class="n6">
				<select name="reserve_year"><? select_year(2001, ' ', ''); ?></select>年
				<select name="reserve_month"><? select_month(' ', ''); ?></select>月
				<select name="reserve_day"><? select_day(' ', ''); ?></select>日
				<select name="reserve_hour"><? select_hour(' ', ''); ?></select>
				<select name="reserve_minutes"><? select_minute(' ', ''); ?></select>
			</td>
    </tr>
  </table>
	<input type="hidden" name="osusume_mag" value="<?= $osusume_mag ?>">
	<input type="hidden" name="new_mag" value="<?= $new_mag ?>">
  <input type="submit" value=" 登録 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
