<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:�ޥ������ƥʥ󥹡û��鳨��Ϣ�ޥ����å����ڡ���ޥ���
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �ᥤ�����
set_global('master_st', '�ޥ��������û��鳨��Ϣ�ޥ���', '�����ڡ���ޥ���', BACK_TOP);

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
	if (f.cp_campaign_cd.value == "") {
		alert("�����ڡ��󥳡��ɤ����Ϥ��Ƥ���������");
		f.cp_campaign_cd.focus();
		return false;
	}
	if (f.cp_server_id.value == "") {
		alert("�ݶ⥵���Ф����򤷤Ƥ���������");
		f.cp_server_id.focus();
		return false;
	}
	if (f.cp_name.value == "") {
		alert("�����ڡ���̾�����Ϥ��Ƥ���������");
		f.cp_name.focus();
		return false;
	}
	if (f.cp_brushwork.value == "") {
		alert("���������򤷤Ƥ���������");
		f.cp_brushwork.focus();
		return false;
	}
	return confirm("�����ڡ���������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.cp_campaign_cd.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>����Ͽ���륭���ڡ����������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">�����ڡ��󥳡���<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="cp_campaign_cd" size=10 maxlength=6>
			<span class="note">��Ⱦ�ѱѿ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ݶ⥵����ID<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="cp_server_id"><?=select_ac_server('���򤷤Ʋ�����', '')?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">�����ڡ���̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cp_name" size=60>
		</td>
	</tr>
	<tr>
		<td class="m1">�����ڡ���ܺ�</td>
		<td class="n1">
			<textarea class="kanji" name="cp_explain" cols=50 rows=5></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="cp_brushwork"><?=select_brushwork('���򤷤Ʋ�����', '')?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">Ǽ����������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="cp_nouki"><?=select_day('', 7)?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">�����ڡ��󳫻���</td>
		<td class="n1">
			<select name="cp_from_date_y"><? select_year('2003', '̤����', get_datepart('Y', $fetch->cp_from_date)) ?></select>ǯ
			<select name="cp_from_date_m"><? select_month('̤����', get_datepart('M', $fetch->cp_from_date)) ?></select>��
			<select name="cp_from_date_d"><? select_day('̤����', get_datepart('D', $fetch->cp_from_date)) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">�����ڡ���λ��</td>
		<td class="n1">
			<select name="cp_to_date_y"><? select_year('2003', '̤����', get_datepart('Y', $fetch->cp_to_date)) ?></select>ǯ
			<select name="cp_to_date_m"><? select_month('̤����', get_datepart('M', $fetch->cp_to_date)) ?></select>��
			<select name="cp_to_date_d"><? select_day('̤����', get_datepart('D', $fetch->cp_to_date)) ?></select>��
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="���" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
