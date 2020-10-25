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
if ($seq_no) {
	$sql = "select to_char(sp_start_date, 'YYYY') as start_year, to_char(sp_start_date,'MM') as start_month,to_char(sp_start_date,'DD') as start_day,"
				. "to_char(sp_start_date,'hh24') as start_hour,to_char(sp_end_date, 'YYYY') as end_year, to_char(sp_end_date,'MM') as end_month,"
				. "to_char(sp_end_date,'DD') as end_day,to_char(sp_end_date,'hh24') as end_hour, to_char(sp_info_start, 'YYYY') as info_s_year,"
				. "to_char(sp_info_start, 'MM') as info_s_month,to_char(sp_info_start,'DD') as info_s_day,to_char(sp_info_end, 'YYYY') as info_e_year,"
				. "to_char(sp_info_end, 'MM') as info_e_month,to_char(sp_info_end, 'DD') as info_e_day,sp_msg,"
				. "to_char(sp_regist_date,'yyyy/mm/dd hh24:MI:SS') as sp_regist_date"
				. " from t_stop_period where sp_seq_no=" . $seq_no;
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$start_year = $fetch->start_year;
		$start_month = $fetch->start_month;
		$start_day = $fetch->start_day;
		$start_hour = $fetch->start_hour;
		$end_year = $fetch->end_year;
		$end_month = $fetch->end_month;
		$end_day = $fetch->end_day;
		$end_hour = $fetch->end_hour;
		$info_s_year = $fetch->info_s_year;
		$info_s_month = $fetch->info_s_month;
		$info_s_day = $fetch->info_s_day;
		$info_e_year = $fetch->info_e_year;
		$info_e_month = $fetch->info_e_month;
		$info_e_day = $fetch->info_e_day;
		$sp_msg = $fetch->sp_msg;
		$sp_regist_date = $fetch->sp_regist_date;
	}
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
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}

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
function update_check(f) {
  fm = document.form1;
	if (!check_date(fm.start_year.value, fm.start_month.value, fm.start_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.start_day.focus();
		return(false);
	}
	if (!check_date(fm.end_year.value, fm.end_month.value, fm.end_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.end_day.focus();
		return(false);
	}
	if (!check_date(fm.info_s_year.value, fm.info_s_month.value, fm.info_s_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.info_s_day.focus();
		return(false);
	}
	if (!check_date(fm.info_e_year.value, fm.info_e_month.value, fm.info_e_day.value)) {
		alert("日付の指定が正しくありません。");
		fm.info_e_day.focus();
		return(false);
	}
  if (fm.sp_msg.value == "") {
    alert("コメントを入力してください。");
    fm.sp_msg.focus();
    return false;
  }
	return confirm("更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("削除します。よろしいですか？");
}

//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="update.php" name="form1" onSubmit="return onSubmit_form1(this);">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">■予約禁止期間設定更新</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="750">
		<tr>
			<td class="m4" width=120><nobr>サーバ停止</nobr></td>
			<td class="n6">
				<select name="start_year"><? select_year(2002, ' ', $start_year); ?></select>年
				<select name="start_month"><? select_month(' ', $start_month); ?></select>月
				<select name="start_day"><? select_day(' ', $start_day); ?></select>日
				<select name="start_hour"><? select_hour(' ', $start_hour); ?></select>時 〜 
				<select name="end_year"><? select_year(2002, ' ', $end_year); ?></select>年
				<select name="end_month"><? select_month(' ', $end_month); ?></select>月
				<select name="end_day"><? select_day(' ', $end_day); ?></select>日
				<select name="end_hour"><? select_hour(' ', $end_hour); ?></select>時
			</td>
		</tr>
		<tr>
			<td class="m4" width=120><nobr>アナウンス表示</nobr></td>
			<td class="n6">
				<select name="info_s_year"><? select_year(2002, ' ', $info_s_year); ?></select>年
				<select name="info_s_month"><? select_month(' ', $info_s_month); ?></select>月
				<select name="info_s_day"><? select_day(' ', $info_s_day); ?></select>日　　　　　　　　〜 
				<select name="info_e_year"><? select_year(2002, ' ', $info_e_year); ?></select>年
				<select name="info_e_month"><? select_month(' ', $info_e_month); ?></select>月
				<select name="info_e_day"><? select_day(' ', $info_e_day); ?></select>日
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>コメント</nobr></td>
			<td class="n6"><font class="note">※差込文字が使えます。（開始日時： @start_date@、終了日時： @end_date@）</font><br>
			<textarea name="sp_msg" cols="70" rows="5"><?=$sp_msg?></textarea></td>
		</tr>
		<tr>
			<td class="m4"><nobr>登録日時</nobr></td>
			<td class="n6"><?= $sp_regist_date ?></td>
		</tr>
	</table><br>
	<input type="hidden" name="next_action">
	<input type="hidden" name="seq_no" <?= value($seq_no) ?>>
	<input type="submit" value=" 　更新　 " onclick="document.form1.next_action.value='update'">
	<input type="submit" value=" 削除 " onclick="document.form1.next_action.value='delete'">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
