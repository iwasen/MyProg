<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��¥�᡼���ۿ���������
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

//�ᥤ�����
set_global('monitor', '������᡼���ۿ�', '��¥�᡼���ۿ�����', BACK_TOP);

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
		<td class="m0" colspan=2>���о�My���󥱡���</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���򥢥󥱡���̾</td>
		<td class="n1" width="80%"><?=$enq_title?></td>
	</tr>
	<tr>
		<td class="m0">���ʹ��������</td>
		<td align="right"><input type="button" value="�����ꡡ" onclick="edit_search()"></td>
	</tr>
<? if(!$rm->search_set) {?>
	<tr>
		<td class="m1" width="20%">�ʹ����</td>
		<td class="n1" width="80%">̵��</td>
	</tr>
<? } else { ?>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_sex($rm->sex, '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">ǯ��</td>
		<td class="n1"><? disp_age2($rm->age_cd, $rm->age_from, $rm->age_to) ?></td>
	</tr>
	<tr>
		<td class="m1">̤����</td>
		<td class="n1"><?=decode_mikikon($rm->mikikon, '<br>')?></td>
	</tr>
	<tr>
		<td class="m1">�ｻ�ϰ�</td>
		<td class="n1"><? disp_area($rm->jitaku_area) ?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><? disp_shokugyou($rm->shokugyou) ?></td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1"><? disp_chain($rm->chain) ?></td>
	</tr>
<? } ?>
	<tr>
		<td class="m0" colspan=2>���᡼������</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륿���ȥ�</td>
		<td class="n1">
			<input type="text" name="mail_title" size="50" maxlength="100" <?=value($rm->mail_title)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">�إå�</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_header"><?=htmlspecialchars($rm->mail_header)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">��ʸ</td>
		<td class="n1">
			<textarea rows="20" cols="70" wrap="hard" name="mail_body"><?=htmlspecialchars($rm->mail_body)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">�եå�</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_footer"><?=htmlspecialchars($rm->mail_footer)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">��������<?=$rm->send_flag?></td>
		<td class="n1">
			<input type="checkbox" name="send_flag" <?=value_checked(1, $rm->send_flag)?> onclick="set_send_flag()">����
			<span class="note">�ʻ��ꤷ�ʤ�����¨����</span>
			<span id="s_date">
				<select name="send_year"><? select_year(2004, '', $rm->send_year?$rm->send_year:date('Y')) ?></select>ǯ
				<select name="send_month"><? select_month('', $rm->send_year?$rm->send_month:date('m')) ?></select>��
				<select name="send_day"><? select_day('', $rm->send_day?$rm->send_day:date('d')) ?></select>��
				<select name="send_hour"><? select_hour('', $rm->send_hour != ''?$rm->send_hour:date('H')) ?></select>��
			</span>
		</td>
	</tr>
</table>
<br>
<input type="button" value="�оݿͿ�����" onclick="onclick_search()">
<input type="submit" value="�ƥ��ȥ᡼������" onclick="onclick_test_mail()">
<input type="submit" value="����Ͽ��" onclick="onclick_confirm()">
<input type="button" value="����롡" onclick="location.href='list.php'">
<input type="hidden" name="next_action">
<input type="hidden" name="staff_id" value="<?=$staff_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=implode(',', $enquete_id)?>">
</form>
<? page_footer() ?>
</body>
</html>

