<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");
include ("../inc/code.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:広告クリックカウンタ設定
'******************************************************/

$title_text = $TT_click;
$title_color = $TC_OTHER;

// *** main ***
$sql = "select * from t_click_counter where cc_ct_id=".sql_number($ct_id);
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow > 0) {
	$fetch = pg_fetch_object($result, 0);
	$cc_click_name = $fetch->cc_click_name;
	$cc_client_name = $fetch->cc_client_name;
	$cc_start_date = $fetch->cc_start_date;
	$cc_all_num = $fetch->cc_all_num;
	$cc_jump_url = $fetch->cc_jump_url;
	$cc_click_num = $fetch->cc_click_num;
}
if ($count_set == "reset") {
	$cc_click_num = 0;
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
function OnClick_info() {
  var win;
  win = window.open(document.form1.cc_jump_url.value, "jump_url", "scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes,location=yes");
  win.focus();
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

function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@:";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function OnClick_reset(id){
	if (confirm("カウンタをリセットします。よろしいですか？")) {
		document.form1.target = "";
		document.form1.count_set.value='reset'
		document.form1.action = "click2.php";
		document.form1.submit();
	}
}
function update_check(f) {
  if (f.cc_click_name.value == "") {
    alert("広告名を入力してください。");
    f.cc_click_name.focus();
    return false;
  }
  if (f.cc_client_name.value == "") {
    alert("クライアント名を入力してください。");
    f.cc_client_name.focus();
    return false;
  }
  if (f.cc_start_date.value.length != 8) {
    alert("日付が正しくないようです。");
    f.cc_start_date.focus();
    return false;
  }
	if (!num_chk(f.cc_start_date.value)) {
		alert("半角数字で入力してください。");
		f.cc_start_date.focus();
		return false;
	}
  if (f.cc_all_num.value <= 0) {
    alert("全体数を入力してください。");
    f.cc_all_num.focus();
    return false;
  }
	if (!num_chk(f.cc_all_num.value)) {
		alert("半角数字で入力してください。");
		f.cc_all_num.focus();
		return false;
	}
  if (f.cc_jump_url.value == "") {
    alert("URLを入力してください。");
    f.cc_jump_url.focus();
    return false;
  }
  if (f.cc_jump_url.value.length <= 7) {
    alert("URLが正しくないようです。");
    f.cc_jump_url.focus();
    return false;
  }
	if (!isA(f.cc_jump_url.value)) {
		alert("URLは半角英数で入力してください。");
		f.cc_jump_url.focus();
		return false;
	}
	return confirm("更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("この情報を削除します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="click3.php" name="form1" onsubmit="return onSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">■広告クリックカウンタ詳細設定</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="800">
		<tr>
			<td class="m4"><nobr>広告名</nobr></td>
			<td class="n6"><input size=80 name="cc_click_name" maxlength=100 <?= value($cc_click_name) ?>></td>
		</tr>
		<tr>
			<td class="m4"><nobr>クライアント名</nobr></td>
			<td class="n6"><input size=80 name="cc_client_name" maxlength=100 <?= value($cc_client_name) ?>></td>
		</tr>
		<tr>
			<td class="m4"><nobr>開始日</nobr></td>
			<td class="n6"><input size=20 name="cc_start_date" maxlength=8 <?= value($cc_start_date) ?>><font class="note">（半角数字のみ　例：20020101）</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>全体数</nobr></td>
			<td class="n6"><input size=10 name="cc_all_num" maxlength=10 <?= value($cc_all_num) ?>></td>
		</tr>
		<tr>
			<td class="m4"><nobr>クリック数</nobr></td>
			<td class="n6"><font class="note"><?= number_format($cc_click_num) ?> 回</font>　　　
<?
if ($cc_click_num) {
?>
				<a href='JavaScript:OnClick_reset("<?= $ct_id ?>")'><font size=-1>リセットする</font></a>
<?
}
?>
			</td>
			<input type="hidden" name="cc_click_num" <?= value($cc_click_num) ?>>
		</tr>
		<tr>
			<td class="m4"><nobr>飛び先URL</nobr></td>
			<td class="n6" colspan="2">
				<nobr>
				<input size=80 maxlength=100 name="cc_jump_url" <?= value($cc_jump_url) ?>>
				<input type="button" value="確認" onclick="OnClick_info()">
				<font class="note">（半角英数）</font></td>
				</nobr>
		</tr>
		<tr>
			<td class="m4"><nobr>クリックカウンタ設定後URL</nobr></td>
			<td class="n6" colspan="2"><font class="note"><?= set_url($ct_id) ?></font></td>
		</tr>
	</table><br>
	<input type="hidden" name="count_set">
	<input type="hidden" name="next_action">
	<input type="hidden" name="ct_id" <?= value($ct_id) ?>>
	<input type="submit" value=" 　更新　 " onclick="document.form1.next_action.value='update'">
	<input type="submit" value=" 削除 " onclick="document.form1.next_action.value='delete'">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="location.href='index.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
