<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:Ĵ����̿������åץ��ɲ���
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('etc', '����¾����', 'Ĵ����̥��åץ���', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.chain_or_gyoutai[0].checked && f.chain_cd.value == "") {
		alert("������������򤷤Ƥ���������");
		f.chain_cd.focus();
		return false;
	}
	if (f.chain_or_gyoutai[1].checked && f.gyoutai_cd.value == "") {
		alert("���֤����򤷤Ƥ���������");
		f.chain_cd.focus();
		return false;
	}
	if (f.title.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.title.focus();
		return false;
	}
	if (f.from_month.value == "") {
		alert("Ĵ�����֤����Ϥ��Ƥ���������");
		f.from_month.focus();
		return false;
	}
	if (f.from_day.value == "") {
		alert("Ĵ�����֤����Ϥ��Ƥ���������");
		f.from_day.focus();
		return false;
	}
	if (f.to_month.value == "") {
		alert("Ĵ�����֤����Ϥ��Ƥ���������");
		f.to_month.focus();
		return false;
	}
	if (f.to_day.value == "") {
		alert("Ĵ�����֤����Ϥ��Ƥ���������");
		f.to_day.focus();
		return false;
	}
	if (f.report_file.value == "") {
		alert("��ݡ��ȥե���������Ϥ��Ƥ���������");
		f.report_file.focus();
		return false;
	}
	if (f.sum_file.value == "") {
		alert("���׷�̥ե���������Ϥ��Ƥ���������");
		f.sum_file.focus();
		return false;
	}
	if (f.enquete_file.value == "") {
		alert("Ĵ��ɼ�ե���������Ϥ��Ƥ���������");
		f.enquete_file.focus();
		return false;
	}
	return confirm("Ĵ����̤򥢥åץ��ɤ��ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" enctype="multipart/form-data" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>��Ĵ����̤����Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%"><input type="radio" name="chain_or_gyoutai" value="1" checked>��������<?=MUST_ITEM?></td>
		<td class="n1" width="80%"><select name="chain_cd"><? select_chain('- ���򤷤Ƥ������� -', '') ?></select></td>
	</tr>
	<tr>
		<td class="m1" width="20%"><input type="radio" name="chain_or_gyoutai" value="2">������<?=MUST_ITEM?></td>
		<td class="n1" width="80%"><select name="gyoutai_cd"><? select_gyoutai('- ���򤷤Ƥ������� -', '') ?></select></td>
	</tr>
	<tr>
		<td class="m1">�����ȥ�<?=MUST_ITEM?></td>
		<td class="n1"><input type="text" name="title" size=80></td>
	</tr>
	<tr>
		<td class="m1">Ĵ������<?=MUST_ITEM?></td>
		<td class="n1">
                        <?/*����ǯ����ɽ��������ɲ�20061222 */?> 
                        <select name="from_year"><? select_year(date('Y')-1,"",date('Y')) ?></select>ǯ
                        <?/*�������ȥ�����20061222 */?>
			<?/*<select name="from_year"><? select_year(date('Y')) ?></select>ǯ*/?>
			<select name="from_month"><? select_month('��') ?></select>��
			<select name="from_day"><? select_day('��') ?></select>��
			��
                        <?/*����ǯ����ɽ��������ɲ�20061222 */?>
                        <select name="to_year"><? select_year(date('Y')-1,"",date('Y')) ?></select>ǯ                        
                        <?/*�������ȥ�����20061222 */?>
			<?/*<select name="to_year"><? select_year(date('Y')) ?></select>ǯ*/?>
			<select name="to_month"><? select_month('��') ?></select>��
			<select name="to_day"><? select_day('��') ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">��ݡ��ȥե�����<?=MUST_ITEM?></td>
		<td class="n1"><input type="file" name="report_file" size=60></td>
	</tr>
	<tr>
		<td class="m1">���׷�̥ե�����<?=MUST_ITEM?></td>
		<td class="n1"><input type="file" name="sum_file" size=60></td>
	</tr>
	<tr>
		<td class="m1">Ĵ��ɼ�ե�����<?=MUST_ITEM?></td>
		<td class="n1"><input type="file" name="enquete_file" size=60></td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
