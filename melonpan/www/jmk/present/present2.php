<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:プレゼント応募管理
'******************************************************/

$title_text = $TT_present;
$title_color = $TC_OTHER;

$sql = "SELECT PD_present_type, PD_present_name, PD_explain, PD_link_text, PD_page_title, PD_presenter, PD_link_url, PD_picture, PD_keyword_flag"
	. ", PD_keyword1, PD_keyword2, PD_keyword3"
	. ", TO_CHAR(PD_campaign_start, 'YYYY') AS campaign_s_year"
	. ", TO_CHAR(PD_campaign_start, 'MM') AS campaign_s_month"
	. ", TO_CHAR(PD_campaign_start, 'DD') AS campaign_s_day"
	. ", TO_CHAR(PD_campaign_end, 'YYYY') AS campaign_e_year"
	. ", TO_CHAR(PD_campaign_end, 'MM') AS campaign_e_month"
	. ", TO_CHAR(PD_campaign_end, 'DD') AS campaign_e_day"
	. ", TO_CHAR(PD_insert_start, 'YYYY') AS insert_s_year"
	. ", TO_CHAR(PD_insert_start, 'MM') AS insert_s_month"
	. ", TO_CHAR(PD_insert_start, 'DD') AS insert_s_day"
	. ", TO_CHAR(PD_insert_end, 'YYYY') AS insert_e_year"
	. ", TO_CHAR(PD_insert_end, 'MM') AS insert_e_month"
	. ", TO_CHAR(PD_insert_end, 'DD') AS insert_e_day"
	. ", TO_CHAR(PD_lottery_date, 'YYYY') AS lottery_year"
	. ", TO_CHAR(PD_lottery_date, 'MM') AS lottery_month"
	. ", TO_CHAR(PD_lottery_date, 'DD') AS lottery_day"
	. ", PD_prize_1, PD_prize_2, PD_prize_3, PD_prize_name1, PD_prize_name2, PD_prize_name3, PD_status"
	. " FROM M_PRESENT_DATA"
	. " WHERE PD_present_id='$present_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$present_type = $fetch->pd_present_type;
	$present_name = $fetch->pd_present_name;
	$link_text = $fetch->pd_link_text;
	$page_title = $fetch->pd_page_title;
	$explain = $fetch->pd_explain;
	$presenter = $fetch->pd_presenter;
	$link_url = $fetch->pd_link_url;
	$picture = $fetch->pd_picture;
	$keyword_flag = $fetch->pd_keyword_flag;
	$keyword1 = $fetch->pd_keyword1;
	$keyword2 = $fetch->pd_keyword2;
	$keyword3 = $fetch->pd_keyword3;
	$campaign_s_year = $fetch->campaign_s_year;
	$campaign_s_month = $fetch->campaign_s_month;
	$campaign_s_day = $fetch->campaign_s_day;
	$campaign_e_year = $fetch->campaign_e_year;
	$campaign_e_month = $fetch->campaign_e_month;
	$campaign_e_day = $fetch->campaign_e_day;
	$insert_s_year = $fetch->insert_s_year;
	$insert_s_month = $fetch->insert_s_month;
	$insert_s_day = $fetch->insert_s_day;
	$insert_e_year = $fetch->insert_e_year;
	$insert_e_month = $fetch->insert_e_month;
	$insert_e_day = $fetch->insert_e_day;
	$lottery_year = $fetch->lottery_year;
	$lottery_month = $fetch->lottery_month;
	$lottery_day = $fetch->lottery_day;
	$lottery_year = $fetch->lottery_year;
	$lottery_month = $fetch->lottery_month;
	$lottery_day = $fetch->lottery_day;
	$prize_1 = $fetch->pd_prize_1;
	$prize_2 = $fetch->pd_prize_2;
	$prize_3 = $fetch->pd_prize_3;
	$prize_name1 = $fetch->pd_prize_name1;
	$prize_name2 = $fetch->pd_prize_name2;
	$prize_name3 = $fetch->pd_prize_name3;
	$status = $fetch->pd_status;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<META HTTP-EQUIV="Content-Type" CONTENT="image/jpeg">
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
function  ShowHide( id, show ) {
	var disp = document.all(id).style.display;
	if (show == 'no')
		document.all(id).style.display = "none";
	else
	if (show == 'yes')
		document.all(id).style.display = "block";
}

function OnClick_showpict() {
	if (document.form1.prize_pict.value != "") {
		document.all.pict.style.width = "";
		document.all.pict.style.height = "";
		document.all.pict.src = document.form1.prize_pict.value;
	}
}
function OnClick_info() {
  var win;
  win = window.open(document.form1.link_url.value, "link_url", "scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes,location=yes");
  win.focus();
}

function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}

function OnSubmit_form1() {
  fm = document.form1;
  if (fm.present_name.value == "") {
    alert("プレゼント名を入力してください。");
    fm.present_name.focus();
    return false;
  }
  if (fm.explain.value == "") {
    alert("説明文を入力してください。");
    fm.explain.focus();
    return false;
  }
  if (fm.presenter.value == "") {
    alert("提供会社名を入力してください。");
    fm.presenter.focus();
    return false;
  }
  if (fm.keyword_flag[0].checked == false && fm.keyword_flag[1].checked == false) {
    alert("キーワード設定を選択してください。");
    return false;
  }
  if (fm.keyword_flag[0].checked == true && fm.keyword1.value == "") {
    alert("キーワードを入力してください。");
    fm.keyword1.focus();
    return false;
  }
  if (fm.prize_1.value == "") {
    alert("当選人数を入力してください。");
    fm.prize_1.focus();
    return false;
  }
	if (!num_chk(fm.prize_1.value)){
		alert("当選人数は半角の数値で入力してください。");
		fm.prize_1.focus();
		return(false);
	}
  if (fm.prize_name1.value == "") {
    alert("賞品名を入力してください。");
    fm.prize_name1.focus();
    return false;
  }
	if (!num_chk(fm.prize_2.value)){
		alert("当選人数は半角の数値で入力してください。");
		fm.prize_2.focus();
		return(false);
	}
	if (!num_chk(fm.prize_3.value)){
		alert("当選人数は半角の数値で入力してください。");
		fm.prize_3.focus();
		return(false);
	}
	if (!check_date(fm.insert_s_year.value, fm.insert_s_month.value, fm.insert_s_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.insert_s_day.focus();
		return(false);
	}
	if (!check_date(fm.insert_e_year.value, fm.insert_e_month.value, fm.insert_e_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.insert_e_day.focus();
		return(false);
	}
	if (!check_date(fm.campaign_s_year.value, fm.campaign_s_month.value, fm.campaign_s_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.campaign_s_day.focus();
		return(false);
	}
	if (!check_date(fm.campaign_e_year.value, fm.campaign_e_month.value, fm.campaign_e_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.campaign_e_day.focus();
		return(false);
	}
	if (!check_date(fm.lottery_year.value, fm.lottery_month.value, fm.lottery_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.lottery_day.focus();
		return(false);
	}
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("削除します。よろしいですか？")) {
    location.href = "present5.php?present_id=<?= $present_id ?>&oid=<?= $picture ?>";
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" ENCTYPE="multipart/form-data" action="present4.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■プレゼント応募詳細設定</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="700">
		<tr>
			<td class="m4" width="25%"><nobr>プレゼントID</nobr></td>
			<td class="n6" colspan="2"><?= $present_id ?></td>
		</tr>
		<tr>
			<td class="m4"><nobr>応募タイプ</nobr></td>
			<td class="n6" colspan="2">
				<input type="radio" name="present_type" value="A" <? if ($present_type == 'A') { print " checked"; } ?>>新規購読
				<input type="radio" name="present_type" value="B" <? if ($present_type == 'B') { print " checked"; } ?>>既存購読
			</td>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>プレゼント名</nobr></td>
			<td class="n6" colspan="2"><input size=60 name="present_name" maxlength=100 value="<?= htmlspecialchars($present_name) ?>"></td>
		</tr>
		<tr>
			<td class="m4"><nobr>リンク元テキスト</nobr></td>
			<td class="n6" colspan="2"><input size=60 name="link_text" maxlength=100 value="<?= htmlspecialchars($link_text) ?>"></td>
		</tr>
		<tr>
			<td class="m4"><nobr>応募ページタイトル</nobr></td>
			<td class="n6" colspan="2"><textarea name=page_title cols="70" rows="5"><?= htmlspecialchars($page_title) ?></textarea></td>
		</tr>
		<tr>
			<td class="m4"><nobr>賞品紹介文</nobr></td>
			<td class="n6" colspan="2"><textarea name=explain cols="70" rows="8"><?= htmlspecialchars($explain) ?></textarea></td>
		</tr>
		<tr>
				<td class="m4"><nobr>賞品画像</nobr></td>
				<td class="n6" colspan="2">
					<img src="./prize_pict.php?pd_picture=<?= $picture ?>" id="pict" alt="画像"><br>
					<input size="40" name="prize_pict" type="file">
					<input type="button" value="表示" onclick="OnClick_showpict()">
				</td>
		</tr>
		<tr>
			<td class="m4"><nobr>提供会社名</nobr></td>
			<td class="n6" colspan="2"><input size=60 name="presenter" maxlength=100 value="<?= htmlspecialchars($presenter) ?>"></td>
		</tr>
		<tr>
			<td class="m4"><nobr>提供会社URL</nobr></td>
			<td class="n6" colspan="2"><input size=60 name="link_url" maxlength=100 value="<?= htmlspecialchars($link_url) ?>">
				<input type="button" value="確認" onclick="OnClick_info()">
				<font class="note">（半角英数）</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>キーワード設定</nobr></td>
			<td class="n6" colspan="2">
				<input type="radio" name="keyword_flag" onclick="ShowHide('show', 'yes')" value="1" <? if ($keyword_flag == '1') { print " checked"; } ?>>あり
				<input type="radio" name="keyword_flag" onclick="ShowHide('show', 'no')" value="0" <? if ($keyword_flag == '0') { print " checked"; } ?>>なし
			</td>
		</tr>
		<tr ID="show" <? if ($keyword_flag == '0') { print "style=\"display:none;\""; } ?>>
			<td class="m4"><nobr>キーワード</nobr></td>
			<td class="n6" colspan="2">
				<input size=40 name="keyword1" maxlength=20 value="<?= htmlspecialchars($keyword1) ?>"> <font class="note">（全角：20文字以内）</font><br>
				<input size=40 name="keyword2" maxlength=20 value="<?= htmlspecialchars($keyword2) ?>"><br>
				<input size=40 name="keyword3" maxlength=20 value="<?= htmlspecialchars($keyword3) ?>">
			</td>
		</tr>
		<tr>
			<td class="m4" rowspan="3"><nobr>当選人数<br>　　および賞品名</nobr></td>
			<td class="m6s" align="center" width="7%">１等</td>
			<td class="n6">
				<input size=5 name="prize_1" maxlength=9 value="<?= $prize_1 ?>">名　
				<input type="text" name="prize_name1" size="50" maxlength="100" value="<?= htmlspecialchars($prize_name1) ?>">
			</td>
		</tr>
		<tr>
			<td class="m6s" align="center">２等</td>
			<td class="n6">
				<input size=5 name="prize_2" maxlength=9 value="<?= $prize_2 ?>">名　
				<input type="text" name="prize_name2" size="50" maxlength="100" value="<?= htmlspecialchars($prize_name2) ?>">
			</td>
		</tr>
		<tr>
			<td class="m6s" align="center">３等</td>
			<td class="n6">
				<input size=5 name="prize_3" maxlength=9 value="<?= $prize_3 ?>">名　
				<input type="text" name="prize_name3" size="50" maxlength="100" value="<?= htmlspecialchars($prize_name3) ?>">
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>応募券挿入期間</nobr></td>
			<td class="n6" colspan="2">
				<select name="insert_s_year"><? select_year(2001, '', date($insert_s_year)); ?></select>年
				<select name="insert_s_month"><? select_month('', date($insert_s_month)); ?></select>月
				<select name="insert_s_day"><? select_day('', date($insert_s_day)); ?></select>日〜
				<select name="insert_e_year"><? select_year(2001, '', date($insert_e_year)); ?></select>年
				<select name="insert_e_month"><? select_month('', date($insert_e_month)); ?></select>月
				<select name="insert_e_day"><? select_day('', date($insert_e_day)); ?></select>日
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>応募受付期間</nobr></td>
			<td class="n6" colspan="2">
				<select name="campaign_s_year"><? select_year(2001, '', date($campaign_s_year)); ?></select>年
				<select name="campaign_s_month"><? select_month('', date($campaign_s_month)); ?></select>月
				<select name="campaign_s_day"><? select_day('', date($campaign_s_day)); ?></select>日〜
				<select name="campaign_e_year"><? select_year(2001, '', date($campaign_e_year)); ?></select>年
				<select name="campaign_e_month"><? select_month('', date($campaign_e_month)); ?></select>月
				<select name="campaign_e_day"><? select_day('', date($campaign_e_day)); ?></select>日
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>抽選日</nobr></td>
			<td class="n6" colspan="2">
				<select name="lottery_year"><? select_year(2001, '', date($lottery_year)); ?></select>年
				<select name="lottery_month"><? select_month('', date($lottery_month)); ?></select>月
				<select name="lottery_day"><? select_day('', date($lottery_day)); ?></select>日
			</td>
		</tr>
	</table><br>
	<input type="hidden" name="status" value="<?= $status ?>" >
	<input type="hidden" name="present_id" value="<?= $present_id ?>" >
	<input type="submit" value=" 更新 ">
	<input type="reset" value="リセット">
	<input type="reset" value="削除" onclick="OnClick_sakujo()">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
