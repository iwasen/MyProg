<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:督促メール配信更新処理
'******************************************************/

$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/search.php");
include("$inc/my_search.php");
include("reminder.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

if (!isset($_SESSION['ss_enq_rm']))
	redirect('list.php');
$rm = &$_SESSION['ss_enq_rm'];

if(!$rm->mail_set)
	$rm->set_mail_data();
$rm->search_set = false;

//メイン処理
set_global('monitor', '条件指定メール配信', '督促メール配信内容', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	return confirm("このＭｙアンケートを更新します。よろしいですか？");
}
function change_age_type() {
	var f = document.form1;
	if (f.age_type.selectedIndex == 1) {
		document.getElementById("age_type1").disabled = false;
	} else {
		document.getElementById("age_type1").disabled = true;
		for (var i = 0; i < f["age_cd[]"].length; i++) {
			f["age_cd[]"][i].checked = false;
		}
	}
	if (f.age_type.selectedIndex == 2) {
		document.getElementById("age_type2").disabled = false;
		f.age_from.disabled = false;
		f.age_to.disabled = false;
	} else {
		document.getElementById("age_type2").disabled = true;
		f.age_from.disabled = true;
		f.age_to.disabled = true;
		f.age_from.value = "";
		f.age_to.value = "";
	}
}
function onload_body() {
	change_age_type();
}

function onclick_back(){
	var f = document.form1;
	f.target = "";
	f.action="edit.php?back=1";
	f.submit();
}
function onclick_confirm(){
	var f = document.form1;
	f.target = "";
	f.action = "confirm.php";
	f.submit();
}

function onclick_search(){
	var f = document.form1;
	f.action = "search.php?form=1";
	f.target = "search";
	f.submit();
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>
<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■条件設定内容</td>
	</tr>
	<tr>
		<td class="m1">性別<?=$rm->sex?></td>
		<td class="n1">
			<input type="checkbox" name="sex[]" <?=value_checked('1', $rm->sex)?>>男性&nbsp;
			<input type="checkbox" name="sex[]" <?=value_checked('2', $rm->sex)?>>女性
		</td>
	</tr>
	<tr>
		<td class="m1">年代</td>
		<td class="n1">
			<select name="age_type" onchange="change_age_type()">
				<option <?=value_selected('', $rm->age_type)?>>形式を選択してください</option>
				<option <?=value_selected('1', $rm->age_type)?>>5歳刻みの年齢</option>
				<option <?=value_selected('2', $rm->age_type)?>>任意の年齢</option>
			</select>
			<span id=age_type1>
<?
$sql = "SELECT ae_age_cd,ae_age_text FROM m_age ORDER BY ae_age_cd";
checkbox_common($sql, 'age_cd', $rm->age_cd, 4);
?>
			</span>
			<span id="age_type2">
			<table border=0 width="100%">
				<tr>
					<td><input type="text" name="age_from" <?=value($rm->age_from)?> size="4" maxlength="2">
					才〜
					<input type="text" name="age_to" <?=value($rm->age_to)?> size="4" maxlength="2">
					才<font class="note">（半角数字）</font></td>
				</tr>
			</table>
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">未既婚</td>
		<td class="n1">
			<input type="checkbox" name="mikikon[]" <?=value_checked('1', $rm->mikikon)?>>独身&nbsp;
			<input type="checkbox" name="mikikon[]" <?=value_checked('2', $rm->mikikon)?>>既婚
		</td>
	</tr>
	<tr>
		<td class="m1">居住地域</td>
		<td class="n1">
<?
$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
checkbox_common($sql, 'jitaku_area', $rm->jitaku_area, 5);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">職業</td>
		<td class="n1">
<?
$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
checkbox_common($sql, 'shokugyou', $rm->shokugyou, 2);
?>
		</td>
	</tr>
	<tr>
		<td class="m1">チェーン</td>
		<td class="n1">
<?
$sql = "SELECT ch_chain_cd,ch_name FROM m_chain WHERE ch_status=0 ORDER BY ch_order";
checkbox_common($sql, 'chain', $rm->chain, 2);
?>
		</td>
	</tr>
</table>

<br>
<input type="button" value="対象人数検索" onclick="onclick_search()">
<input type="submit" value="　登録　" onclick="onclick_confirm()">
<input type="button" value="　戻る　" onclick="onclick_back()">
<input type="hidden" name="next_action">
<input type="hidden" name="staff_id" value="<?=$staff_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
