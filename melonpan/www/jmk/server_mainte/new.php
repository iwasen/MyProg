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

$title_text = $TT_server_mainte;
$title_color = $TC_OTHER;

$default_msg = "※ @start_date@〜@end_date@にサーバメンテナンスを行います。恐れ入りますが、この間はメルマガを配信することができませんので、ご注意いただきますようお願いいたします。";

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
	if (!check_date(fm.start_year.value, fm.start_month.value, fm.start_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.start_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.start_year.value, fm.start_month.value - 1, fm.start_day.value);
	if (dd > dt) {
		alert("過去の日付は指定できません。");
		fm.start_day.focus();
		return(false);
	}
	if (!check_date(fm.end_year.value, fm.end_month.value, fm.end_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.end_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.end_year.value, fm.end_month.value - 1, fm.end_day.value);
	if (dd > dt) {
		alert("過去の日付は指定できません。");
		fm.end_day.focus();
		return(false);
	}

	if (!check_date(fm.info_s_year.value, fm.info_s_month.value, fm.info_s_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.info_s_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.info_s_year.value, fm.info_s_month.value - 1, fm.info_s_day.value);
	if (dd > dt) {
		alert("過去の日付は指定できません。");
		fm.info_s_day.focus();
		return(false);
	}
	if (!check_date(fm.info_e_year.value, fm.info_e_month.value, fm.info_e_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.info_e_day.focus();
		return(false);
	}
	dd = new Date();
	dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
	var dt = new Date(fm.info_e_year.value, fm.info_e_month.value - 1, fm.info_e_day.value);
	if (dd > dt) {
		alert("過去の日付は指定できません。");
		fm.info_e_day.focus();
		return(false);
	}
  if (fm.sp_msg.value == "") {
    alert("コメントを入力してください。");
    fm.sp_msg.focus();
    return false;
  }
	return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="update.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">■予約禁止期間詳細設定</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="750">
		<tr>
			<td class="m4" width=120><nobr>サーバ停止</nobr></td>
			<td class="n6">
				<select name="start_year"><? select_year(2002, ' ', ''); ?></select>年
				<select name="start_month"><? select_month(' ', ''); ?></select>月
				<select name="start_day"><? select_day(' ', ''); ?></select>日
				<select name="start_hour"><? select_hour(' ', ''); ?></select>時 〜 
				<select name="end_year"><? select_year(2002, ' ', ''); ?></select>年
				<select name="end_month"><? select_month(' ', ''); ?></select>月
				<select name="end_day"><? select_day(' ', ''); ?></select>日
				<select name="end_hour"><? select_hour(' ', ''); ?></select>時
			</td>
		</tr>
		<tr>
			<td class="m4" width=120><nobr>アナウンス表示</nobr></td>
			<td class="n6">
				<select name="info_s_year"><? select_year(2002, ' ', ''); ?></select>年
				<select name="info_s_month"><? select_month(' ', ''); ?></select>月
				<select name="info_s_day"><? select_day(' ', ''); ?></select>日　　　　　　　　〜 
				<select name="info_e_year"><? select_year(2002, ' ', ''); ?></select>年
				<select name="info_e_month"><? select_month(' ', ''); ?></select>月
				<select name="info_e_day"><? select_day(' ', ''); ?></select>日
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>コメント</nobr></td>
			<td class="n6"><font class="note">※差込文字が使えます。（開始日時： @start_date@ 、終了日時： @end_date@ ）</font><br>
			<textarea name="sp_msg" cols="70" rows="5"><?=$default_msg?></textarea></td>
		</tr>
	</table><br>
	<input type="hidden" name="next_action" value="new">
	<input type="submit" value=" 登録 " onclick="document.form1.next_action.value='new'">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
