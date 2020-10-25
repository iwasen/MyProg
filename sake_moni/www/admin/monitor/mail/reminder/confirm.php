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
include("$inc/search.php");
include("$inc/my_search.php");
include("reminder.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global('monitor', '������᡼���ۿ�', '��¥�᡼���ۿ�����', BACK_TOP);

if (!isset($_SESSION['ss_enq_rm']))
	redirect('list.php');
$rm = &$_SESSION['ss_enq_rm'];

if (!$rm->mail_set)
	$rm->set_mail_data();

if(!$rm->search_set)
	$rm->set_search_data();


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

function set_send_date(){
	var f = document.form1;
	var disp = f.send_date.checked;

	document.getElementById("s_date").style.display = disp ? "" : "none";
}

function onclick_back(){
	var f = document.form1;
	f.action = "edit.php";
	f.submit();
}

function onclick_update() {
	var f = document.form1;

	if (confirm("��¥�᡼����������ޤ���������Ǥ�����")){
		f.action = "update.php";
		f.submit();
	} else {
		return false;
	}
}
//-->
</script>
</head>
<body>
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
		<td class="m0" colspan=2>�������������</td>
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
		<td class="n1"><?=htmlspecialchars($rm->mail_title)?></td>
	</tr>
	<tr>
		<td class="m1">�إå�</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($rm->mail_header))?></span></td>
	</tr>
	<tr>
		<td class="m1">��ʸ</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($rm->mail_body))?></span></td>
	</tr>
	<tr>
		<td class="m1">�եå�</td>
		<td class="n1"><span class="small"><?=nl2br(htmlspecialchars($rm->mail_footer))?></span></td>
	</tr>
<?if(!$rm->send_flag) {?>
	<tr>
		<td class="m1">��������</td>
		<td class="n1"><span class="small">����̵��</span></td>
	</tr>
<? } else { ?>
	<tr>
		<td class="m1">��������</td>
		<td class="n1"><?=$rm->send_year?>ǯ<?=$rm->send_month?>��<?=$rm->send_day?>��<?=$rm->send_hour?>��</td>
	</tr>
<? } ?>
</table>
<br>
<input type="button" value="��¥�᡼������" onclick="onclick_update()">
<input type="button" value="����롡" onclick="onclick_back()">
<input type="hidden" name="next_action">
<input type="hidden" name="staff_id" value="<?=$staff_id?>">
<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
<input type="hidden" name="enquete_id" value="<?=implode(',', $enquete_id)?>">
</form>
<? page_footer() ?>
</body>
</html>

