<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:広告バナー設定
'******************************************************/

$title_text = $TT_banner_set;
$title_color = $TC_OTHER;

$sql = "SELECT BN_kind, BN_title, BN_picture, BN_send_url"
	. ", to_char(BN_start_dt,'YYYY') as BN_start_year"
	. ", to_char(BN_start_dt,'MM') as BN_start_month"
	. ", to_char(BN_start_dt,'DD') as BN_start_day"
	. ", to_char(BN_start_dt,'HH24') as BN_start_hour"
	. ", to_char(BN_end_dt,'YYYY') as BN_end_year"
	. ", to_char(BN_end_dt,'MM') as BN_end_month"
	. ", to_char(BN_end_dt,'DD') as BN_end_day"
	. ", to_char(BN_end_dt,'HH24') as BN_end_hour"
	. " FROM T_BANNER"
	. " WHERE BN_id = $bn_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$bn_kind = $fetch->bn_kind;
	$bn_title = $fetch->bn_title;
	$bn_picture = $fetch->bn_picture;
	$bn_send_url = $fetch->bn_send_url;
	$bn_start_year = $fetch->bn_start_year;
	$bn_start_month = $fetch->bn_start_month;
	$bn_start_day = $fetch->bn_start_day;
	$bn_start_hour = $fetch->bn_start_hour;
	$bn_end_year = $fetch->bn_end_year;
	$bn_end_month = $fetch->bn_end_month;
	$bn_end_day = $fetch->bn_end_day;
	$bn_end_hour = $fetch->bn_end_hour;
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
function  ShowHide( id, show ) {
	var disp = document.all(id).style.display;
	if (show == 'no')
		document.all(id).style.display = "none";
	else
	if (show == 'yes')
		document.all(id).style.display = "block";
}

function OnClick_showpict() {
	if (document.form1.banner_pict.value != "") {
		document.all.pict.style.width = "";
		document.all.pict.style.height = "";
		document.all.pict.src = document.form1.banner_pict.value;
	}
}
function OnClick_info() {
  var win;
  win = window.open(document.form1.bn_send_url.value, "link_url", "scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes,location=yes");
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
  if (fm.bn_title.value == "") {
    alert("バナータイトルを入力してください。");
    fm.bn_title.focus();
    return false;
  }
	if (!check_date(fm.bn_start_year.value, fm.bn_start_month.value, fm.bn_start_day.value)) {
		alert("日時の指定が正しくありません。");
		fm.bn_start_day.focus();
		return(false);
	}
	var dt1 = new Date(fm.bn_start_year.value, fm.bn_start_month.value - 1, fm.bn_start_day.value, fm.bn_start_hour.value);
	var dt2 = new Date(fm.bn_end_year.value, fm.bn_end_month.value - 1, fm.bn_end_day.value, fm.bn_end_hour.value);
	if (dt1 >= dt2) {
		alert("表示期間をご確認下さい。");
		fm.bn_end_hour.focus();
		return(false);
	}
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("削除します。よろしいですか？")) {
    location.href = "banner_set5.php?bn_id=<?= $bn_id ?>&oid=<?= $bn_picture ?>";
	}
}

//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" ENCTYPE="multipart/form-data" action="banner_set4.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■広告バナー修正</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="700">
		<tr>
			<td class="m4"><nobr>バナー種別</nobr></td>
			<td class="n6">
				<input type="radio" name="bn_kind" value="1"  <? if ($bn_kind == '1') { print " checked"; } ?>>トップバナー
				<input type="radio" name="bn_kind" value="2" <? if ($bn_kind == '2') { print " checked"; } ?>>サイドバナー
			</td>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>バナータイトル</nobr></td>
			<td class="n6"><input size=80 name="bn_title" maxlength=100 value="<?= htmlspecialchars($bn_title) ?>"></td>
		</tr>
		<tr>
			<td class="m4"><nobr>バナー画像</nobr></td>
			<td class="n6">
				<img src="./banner_pict.php?bn_picture=<?= $bn_picture ?>" id="pict" alt="画像"><br>
				<input size="60" name="banner_pict" type="file" >
				<input type="button" value="表示" onclick="OnClick_showpict()">
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>リンク先URL</nobr></td>
			<td class="n6">
				<input size=60 name="bn_send_url" maxlength=100 value="<?= $bn_send_url ?>">
				<input type="button" value="確認" onclick="OnClick_info()">
				<font class="note">（半角英数）</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>表示開始日時</nobr></td>
			<td class="n6">
				<select name="bn_start_year"><? select_year(2001, '', date($bn_start_year)); ?></select>年
				<select name="bn_start_month"><? select_month('', date($bn_start_month)); ?></select>月
				<select name="bn_start_day"><? select_day('', date($bn_start_day)); ?></select>日
				<select name="bn_start_hour"><? select_hour('', date($bn_start_hour)); ?></select>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>表示終了日時</nobr></td>
			<td>
				<select name="bn_end_year"><? select_year(2001, '', date($bn_end_year)); ?></select>年
				<select name="bn_end_month"><? select_month('', date($bn_end_month)); ?></select>月
				<select name="bn_end_day"><? select_day('', date($bn_end_day)); ?></select>日
				<select name="bn_end_hour"><? select_hour('', date($bn_end_hour)); ?></select>
			</td>
		</tr>
	</table><br>
	<input type="hidden" name="bn_id" value=<?= $bn_id ?>>
	<input type="submit" value=" 更新 ">
	<input type="reset" value="リセット">
	<input type="reset" value="削除" onclick="OnClick_sakujo()">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
