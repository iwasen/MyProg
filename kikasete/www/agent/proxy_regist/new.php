<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:ͭ���ޡ�������������Ͽ
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// �ᥤ�����
set_global('ͭ���ޡ�������������Ͽ', BACK_TOP);
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
	if (f.name1.value == '') {
		alert("̾���ʴ����ˤ����Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == '') {
		alert("̾���ʴ����ˤ����Ϥ��Ƥ���������");
		f.name2.focus();
		return false;
	}
	if (f.name1_kana.value == '') {
		alert("̾���ʥ��ʡˤ����Ϥ��Ƥ���������");
		f.name1_kana.focus();
		return false;
	}
	if (f.name2_kana.value == '') {
		alert("̾���ʥ��ʡˤ����Ϥ��Ƥ���������");
		f.name2_kana.focus();
		return false;
	}
	if (f.birthday_y.value == '') {
		alert("��ǯ���������Ϥ��Ƥ���������");
		f.birthday_y.focus();
		return false;
	}
	if (!f.sex[0].checked && !f.sex[1].checked) {
		alert("���̤����򤷤Ƥ���������");
		f.sex[0].focus();
		return false;
	}
	if (f.mail_addr.value == '') {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == '') {
		alert("�ѥ���ɤ����Ϥ��Ƥ���������");
		f.password.focus();
		return false;
	}
	if (f.shokugyou_cd.value == '') {
		alert("���Ȥ����򤷤Ƥ���������");
		f.shokugyou_cd.focus();
		return false;
	}
	if (f.gyoushu_cd.value == '') {
		alert("�ȼ�����򤷤Ƥ���������");
		f.gyoushu_cd.focus();
		return false;
	}
	if (f.shokushu_cd.value == '') {
		alert("��������򤷤Ƥ���������");
		f.shokushu_cd.focus();
		return false;
	}
	if (f.kinmu_name.value == '') {
		alert("���̾�����Ϥ��Ƥ���������");
		f.kinmu_name.focus();
		return false;
	}
	if (f.kinmu_zip1.value == '') {
		alert("��̳��͹���ֹ�����Ϥ��Ƥ���������");
		f.kinmu_zip1.focus();
		return false;
	}
	if (f.kinmu_zip2.value == '') {
		alert("��̳��͹���ֹ�����Ϥ��Ƥ���������");
		f.kinmu_zip2.focus();
		return false;
	}
	if (f.kinmu_area.value == '') {
		alert("��̳���ϰ�����򤷤Ƥ���������");
		f.kinmu_area.focus();
		return false;
	}
	if (f.kinmu_addr1.value == '') {
		alert("��̳�轻��ʻԶ�ˤ����Ϥ��Ƥ���������");
		f.kinmu_addr1.focus();
		return false;
	}
	if (f.kinmu_addr2.value == '') {
		alert("��̳�轻���Į¼�ˤ����Ϥ��Ƥ���������");
		f.kinmu_addr2.focus();
		return false;
	}
	if (f.kinmu_tel.value == '') {
		alert("��̳�������ֹ�����Ϥ��Ƥ���������");
		f.kinmu_tel.focus();
		return false;
	}
	if (f.agent_id.value == '') {
		alert("����������Ȥ����򤷤Ƥ���������");
		f.agent_id.focus();
		return false;
	}
	return confirm("ͭ���ޡ�����������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=3 width=100%>
	<tr><td align="center" class="m4"><b>ͭ���ޡ�������������Ͽ</b></td></tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�����ܾ���</td>
	</tr>
	<tr>
		<td class="m1">̾���ʴ�����<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1" size=25 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2" size=25 maxlength=25>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����<?=MUST_ITEM?></td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4>ǯ
			<select name="birthday_m"><? select_month() ?></select>��
			<select name="birthday_d"><? select_day() ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" value="1">����
			<input type="radio" name="sex" value="2">����
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="shokugyou_cd"><? select_shokugyou('- ̤���� -')?></select></td>
	</tr>
	<tr>
		<td class="m1">�ȼ�<?=MUST_ITEM?></td>
		<td class="n1"><select name="gyoushu_cd"><? select_gyoushu('- ̤���� -', '', 2)?></select></td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="shokushu_cd"><? select_shokushu('- ̤���� -')?></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����̳�����</td>
	</tr>
	<tr>
		<td class="m1">���̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳��͹���ֹ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="kinmu_zip1" size=3 maxlength=3>
			-
			<input class="number" type="text" name="kinmu_zip2" size=4 maxlength=4>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳���ϰ�<?=MUST_ITEM?></td>
		<td class="n1"><select name="kinmu_area"><? select_area('- ̤���� -')?></select></td>
	</tr>
	<tr>
		<td class="m1">��̳�轻��ʻԶ��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr1" size=80 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�轻���Į¼��<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr2" size=80 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�轻��ʥӥ�̾�ʤɡ�</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr3" size=80 maxlength=50>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�������ֹ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_tel" size=20 maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳��FAX�ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_fax" size=20 maxlength=20>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">�����������<?=MUST_ITEM?></td>
		<td class="n1"><select name="agent_id"><? select_agent('- ̤���� -', $agent_id, AG_MAIN)?></select></td>
	</tr>
<? /*
	<tr>
		<td class="m1">��˾�᡼�륢�������<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="text" name="choice_email1" size=20>
		</td>
	</tr>
*/ ?>
</table>
<br>
<div align="center">
	<input type="submit" value="����Ͽ��">
</div>
</form>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
