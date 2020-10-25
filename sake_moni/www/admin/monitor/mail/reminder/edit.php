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
include("$inc/decode.php");
include("$inc/my_search.php");
include("reminder.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global('monitor', '条件指定メール配信', '督促メール配信内容', BACK_TOP);

if (!$enquete_id && !isset($_SESSION['ss_enq_rm']))
	redirect('list.php');
elseif (!isset($_SESSION['ss_enq_rm'])) {
	$_SESSION['ss_enq_rm'] = new reminder_class;

	$rm = &$_SESSION['ss_enq_rm'];
	$rm->get_mail_template();

} else {
	$rm = &$_SESSION['ss_enq_rm'];
}

if(!$rm->search_set)
	$rm->set_search_data();
$rm->mail_set = false;

if (!is_array($rm->enquete_id))
	$enquete_id = array($en_enquete_id);
else {
	$in = implode(',', $rm->enquete_id);
	$sql = "SELECT en_title FROM t_enquete WHERE en_enquete_id IN ($in)";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enq_title .= htmlspecialchars($fetch->en_title) . '<br>';
	}
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
function onload_body(){
	var f = document.form1;

 if(f.send_flag.checked)
	document.getElementById("s_date").style.display = "";
 else
	document.getElementById("s_date").style.display = "none";
}

function set_send_flag(){
	var f = document.form1;
	var disp = f.send_flag.checked;

	document.getElementById("s_date").style.display = disp ? "" : "none";
}

function edit_search(){
	var f = document.form1;
	f.action = "edit_search.php";
	f.submit();
}

function onclick_search() {
	window.open("search.php", "search");
}

function onclick_confirm(){
	var f = document.form1;
	f.action = "confirm.php";
	f.submit();
}
function onclick_test_mail(){
	var f = document.form1;
	f.action = "test_mail.php";
	f.submit();
}
//-->
</script>
</head>
<body onload="onload_body();">
<? page_header() ?>
<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■対象Myアンケート</td>
	</tr>
	<tr>
		<td class="m1" width="20%">選択アンケート名</td>
		<td class="n1" width="80%"><?=$enq_title?></td>
	</tr>
	<tr>
		<td class="m0">■絞込条件設定</td>
		<td align="right"><input type="button" value="　設定　" onclick="edit_search()"></td>
	</tr>
<? if(!$rm->search_set) {?>
	<tr>
		<td class="m1" width="20%">絞込条件</td>
		<td class="n1" width="80%">無し</td>
	</tr>
<? } else { ?>
	<tr>
		<td class="m1">性別</td>
		<td class="n1"><?=decode_sex($rm->sex, '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">年代</td>
		<td class="n1"><? disp_age2($rm->age_cd, $rm->age_from, $rm->age_to) ?></td>
	</tr>
	<tr>
		<td class="m1">未既婚</td>
		<td class="n1"><?=decode_mikikon($rm->mikikon, '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">居住地域</td>
		<td class="n1"><? disp_area($rm->jitaku_area) ?></td>
	</tr>
	<tr>
		<td class="m1">職業</td>
		<td class="n1"><? disp_shokugyou($rm->shokugyou) ?></td>
	</tr>
	<tr>
		<td class="m1">チェーン</td>
		<td class="n1"><? disp_chain($rm->chain) ?></td>
	</tr>
<? } ?>
	<tr>
		<td class="m0" colspan=2>■メール内容</td>
	</tr>
	<tr>
		<td class="m1">メールタイトル</td>
		<td class="n1">
			<input type="text" name="mail_title" size="50" maxlength="100" <?=value($rm->mail_title)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">ヘッダ</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_header"><?=htmlspecialchars($rm->mail_header)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">本文</td>
		<td class="n1">
			<textarea rows="20" cols="70" wrap="hard" name="mail_body"><?=htmlspecialchars($rm->mail_body)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">フッタ</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_footer"><?=htmlspecialchars($rm->mail_footer)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">送信日時<?=$rm->send_flag?></td>
		<td class="n1">
			<input type="checkbox" name="send_flag" <?=value_checked(1, $rm->send_flag)?> onclick="set_send_flag()">指定
			<span class="note">（指定しない場合は即時）</span>
			<span id="s_date">
				<select name="send_year"><? select_year(2004, '', $rm->send_year?$rm->send_year:date('Y')) ?></select>年
				<select name="send_month"><? select_month('', $rm->send_year?$rm->send_month:date('m')) ?></select>月
				<select name="send_day"><? select_day('', $rm->send_day?$rm->send_day:date('d')) ?></select>日
				<select name="send_hour"><? select_hour('', $rm->send_hour != ''?$rm->send_hour:date('H')) ?></select>時
			</span>
		</td>
	</tr>
</table>
<br>
<input type="button" value="対象人数検索" onclick="onclick_search()">
<input type="submit" value="テストメール送信" onclick="onclick_test_mail()">
<input type="submit" value="　登録　" onclick="onclick_confirm()">
<input type="button" value="　戻る　" onclick="location.href='list.php'">
<input type="hidden" name="next_action">
<input type="hidden" name="staff_id" value="<?=$staff_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=implode(',', $enquete_id)?>">
</form>
<? page_footer() ?>
</body>
</html>

