<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:���鳨��������û��鳨���եꥹ��
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// ͥ��������
function select_priority($selected) {
		echo '<option ', value_selected('', $selected), '>�̾�</option>', "\n";
		echo '<option ', value_selected(1, $selected), '>ͥ�褹��</option>', "\n";
}

// �̿�����URL����
$ur_photo = get_system_info('url_photo');

// ���鳨URL����
$ur_nigaoe = get_system_info('url_nigaoe');

// �ᥤ�����
set_global('nigaoe', '���鳨�������', '���鳨���եꥹ��', BACK_TOP);
//to_char(nd_nouki_date - now(),'dd�� HH24����MIʬ')

$sql = "SELECT * , to_char(nd_appl_date-nd_accept_date, 'dd�� HH24��MI') AS appl, to_char(nd_photo_date-nd_appl_date, 'dd�� HH24��MI') AS photo"
	.", to_char(nd_assign_date-nd_photo_date, 'dd�� HH24��MI') AS assign, to_char(nd_creation_date-nd_assign_date, 'dd�� HH24��MI') AS creation"
	. ", to_char(nd_completion_date-nd_creation_date, 'dd�� HH24��MI') AS completion, to_char(nd_dl_mail_date-nd_completion_date, 'dd�� HH24��MI') AS dl_mail"
	. ", to_char(nd_download_date-nd_dl_mail_date, 'dd�� HH24��MI') AS download"
	. " FROM t_nigaoe_data WHERE nd_nigaoe_id = $nd_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("��°̾ID������", __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function open_photo() {
	window.open("<?=$ur_photo?><?=$fetch->nd_photo_file?>", "_blank", "width=300,height=300,scrollbars=yes,resizable=yes,status=no,menubar=no,toolbar=no");
}

function onSubmit_form1(f) {
	if (f.nd_random_id.value == "") {
		alert("������ID�����Ϥ��Ƥ���������");
		f.nd_random_id.focus();
		return false;
	}
	return confirm("���鳨����򹹿����ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" width=30%>������������鳨��������Ϥ��Ƥ���������</td>
		<td align="right" width=70%><input type="button" value="����롡" onclick="history.back()"></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m1" width=30%>�ݶ⥵����ID<?=MUST_ITEM?></td>
		<td class="n1" width=70%>
			<?=$fetch->nd_server_id?>
		</td>
	</tr>
	<tr>
		<td class="m1">�����ڡ��󥳡���<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="nd_campaign_cd"><?=select_campaign('', $fetch->nd_campaign_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">���ʥ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="nd_product_cd"><?=select_product('', $fetch->nd_product_cd)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">ͥ����</td>
		<td class="n1">
			<select name="nd_priority"><?=select_priority($fetch->nd_priority)?></select>
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m2" width=30%>������ID<?=MUST_ITEM?></td>
		<td class="n1" width=70%>
			<input class="alpha" type="text" name="nd_random_id" size=50 <?=value($fetch->nd_random_id)?>>
		</td>
	</tr>
	<tr>
		<td class="m2">�᡼�륢�ɥ쥹</td>
		<td class="n1">
			<input class="alpha" type="text" name="nd_mail_addr" size=50 <?=value($fetch->nd_mail_addr)?>>
		</td>
	</tr>
	<tr>
		<td class="m2">�����᡼����ʸ</td>
		<td class="n1">
			<textarea rows=3 cols=50 disabled><?=htmlspecialchars($fetch->nd_mail_body)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m2">����</td>
		<td class="n1">
			<select name="nd_sex_code"><?=select_sex('', $fetch->nd_sex_code)?></select>
		</td>
	</tr>
	<tr>
		<td class="m2">ǯ��</td>
		<td class="n1">
			<input type="text" name="nd_age" size=6 maxlength=3 <?=value($fetch->nd_age)?>>��
		</td>
	</tr>
	<tr>
		<td class="m2">����</td>
		<td class="n1">
			<select name="nd_brushwork"><?=select_brushwork('̤����', $fetch->nd_brushwork)?></select>
		</td>
	</tr>
	<tr>
		<td class="m2">�˥å��͡���</td>
		<td class="n1">
			<input type="text" size=60 maxlength=40 name="nd_name_text" <?=value($fetch->nd_name_text)?>>
		</td>
	</tr>
	<tr>
		<td class="m2">�̿��ե�����̾</td><td class="n1"><?=$fetch->nd_photo_file != '' ? $fetch->nd_photo_file : '̤����'?></td>
	</tr>
	<tr>
		<td class="m2">����̿�</td>
		<td class="n1">
<?
if ($fetch->nd_photo_file) {
?>
			<a href="javascript:open_photo()"><img src="<?=$ur_photo?><?=$fetch->nd_photo_file?>" alt="<?=$random_id?>" border=0 width=160></a>
<?
} else {
?>
			̤����
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m2">���鳨�ե�����̾</td><td class="n1"><?=$fetch->nd_nigaoe_file != '' ? $fetch->nd_nigaoe_file : '̤����'?></td>
	</tr>
	<tr>
		<td class="m2">���鳨����</td>
		<td class="n1">
<?
if ($fetch->nd_nigaoe_file) {
?>
			<img src="<?=$ur_nigaoe?><?=$fetch->nd_nigaoe_file?>" alt="<?=$random_id?>" width=160>
<?
} else {
?>
			̤����
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m2">ô����襹���å�</td>
		<td class"n1">
			<select name="nd_staff_id"><?=select_staff('̤����', $fetch->nd_staff_id)?></select>
		</td>
	</tr>
	<tr>
		<td class="m2">���ơ�����</td>
		<td class"n1">
			<select name="nd_status"><?=select_status('', $fetch->nd_status)?></select>
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m3" width=30%>Ǽ��</td>
		<td class="n1" width=70%>
			<select name="nd_nouki_date_y"><? select_year('2003', '', get_datepart('Y', $fetch->nd_nouki_date)) ?></select>ǯ
			<select name="nd_nouki_date_m"><? select_month('', get_datepart('M', $fetch->nd_nouki_date)) ?></select>��
			<select name="nd_nouki_date_d"><? select_day('', get_datepart('D', $fetch->nd_nouki_date)) ?></select>��&nbsp;
			<select name="nd_nouki_date_h"><? select_hour('', get_datepart('h', $fetch->nd_nouki_date)) ?></select>��
			<select name="nd_nouki_date_mi"><? select_minute('', get_datepart('m', $fetch->nd_nouki_date)) ?></select>ʬ
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m3" width=30%>��ѥ����Ф���μ�������</td>
		<td class="n1" width=70%><?=format_datetime($fetch->nd_accept_date)?>
		</td>
	</tr>
	<tr>
		<td class="m3">�桼������ο���������</td>
		<td class="n1"><?=format_datetime($fetch->nd_appl_date,$def='̤��')?>&nbsp;&nbsp;<?=$fetch->appl != '' ? "[�в���֡�$fetch->appl]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">�桼������μ̿���������</td>
		<td class="n1"><?=format_datetime($fetch->nd_photo_date,$def='̤��')?>&nbsp;&nbsp;<?=$fetch->photo != '' ? "[�в���֡�$fetch->photo]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">��襹���åճ�������</td>
		<td class="n1"><?=format_datetime($fetch->nd_assign_date,$def='̤��')?>&nbsp;&nbsp;<?=$fetch->assign != '' ? "[�в���֡�$fetch->assign]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">���鳨������������</td>
		<td class="n1"><?=format_datetime($fetch->nd_creation_date,$def='̤��')?>&nbsp;&nbsp;<?=$fetch->creation != '' ? "[�в���֡�$fetch->creation]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">���鳨��������</td>
		<td class="n1"><?=format_datetime($fetch->nd_completion_date,$def='̤��')?>&nbsp;&nbsp;<?=$fetch->completion != '' ? "[�в���֡�$fetch->completion]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">�桼����������</td>
		<td class="n1"><?=format_datetime($fetch->nd_dl_mail_date,$def='̤��')?>&nbsp;&nbsp;<?=$fetch->dl_mail != '' ? "[�в���֡�$fetch->dl_mail]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">���鳨�������������</td>
		<td class="n1"><?=format_datetime($fetch->nd_download_date,$def='̤��')?>&nbsp;&nbsp;<?=$fetch->download != '' ? "[�в���֡�$fetch->download]":''?>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="nd_id" <?=value($nd_id)?>>
<?
if ($fetch->nd_status != 10) {
	print "<input type='submit' value='��������'>\n";
}
?>
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
