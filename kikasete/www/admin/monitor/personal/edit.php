<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��˥����Ŀ͸���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/lifeinfo.php");
include("$inc/format.php");
include("$inc/ma_profile.php");

// �ᥤ�����
set_global('monitor', '��˥�������', '��˥����Ŀ͸���', BACK_TOP);

$sql = "SELECT * FROM t_monitor JOIN t_monitor_point ON mp_monitor_id=mn_monitor_id WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('��˥����ɣĤ�����', __FILE__);
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
function onSubmit_form1(f) {
	if (f.name1.value == "") {
		alert("̾���ʴ����ˤ��������Ϥ��Ƥ���������");
		f.name1.focus();
		return false;
	}
	if (f.name2.value == "") {
		alert("̾���ʴ����ˤ�̾�����Ϥ��Ƥ���������");
		f.name2.focus();
		return false;
	}
	if (f.name1_kana.value == "") {
		alert("̾���ʥ��ʡˤ��������Ϥ��Ƥ���������");
		f.name1_kana.focus();
		return false;
	}
	if (f.name2_kana.value == "") {
		alert("̾���ʥ��ʡˤ�̾�����Ϥ��Ƥ���������");
		f.name2_kana.focus();
		return false;
	}
	if (f.birthday_y.value == "") {
		alert("��ǯ���������Ϥ��Ƥ���������");
		f.birthday_y.focus();
		return false;
	}
	if (f.mikikon[0].checked == false && f.mikikon[1].checked == false && f.mikikon[2].checked == false) {
		alert("̤���������򤷤Ƥ���������");
		f.mikikon[0].focus();
		return false;
	}
	if (f.sex[0].checked == false && f.sex[1].checked == false) {
		alert("���̤����򤷤Ƥ���������");
		f.sex[0].focus();
		return false;
	}
	if (f.mail_addr.value == "") {
		alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
		f.mail_addr.focus();
		return false;
	}
	if (f.password.value == "") {
		alert("�ѥ���ɤ����Ϥ��Ƥ���������");
		f.password.focus();
		return false;
	}
	if (f.jitaku_zip1.value == "") {
		alert("͹���ֹ�ʼ���ˤ����Ϥ��Ƥ���������");
		f.jitaku_zip1.focus();
		return false;
	}
	if (f.jitaku_zip2.value == "") {
		alert("͹���ֹ�ʼ���ˤ����Ϥ��Ƥ���������");
		f.jitaku_zip2.focus();
		return false;
	}
	if (f.jitaku_area.value == "") {
		alert("�ｻ�ϰ�ʼ���ˤ����򤷤Ƥ���������");
		f.jitaku_area.focus();
		return false;
	}
	if (f.shokugyou_cd.value == "") {
		alert("���Ȥ����򤷤Ƥ���������");
		f.shokugyou_cd.focus();
		return false;
	}
	if (f.shokugyou_cd.value != "5" && f.shokugyou_cd.value != "10" && f.shokugyou_cd.value != "11") {
		if (f.gyoushu_cd.value == "") {
			alert("�ȼ�����򤷤Ƥ���������");
			f.gyoushu_cd.focus();
			return false;
		}
		if (f.shokushu_cd.value == "") {
			alert("��������򤷤Ƥ���������");
			f.shokushu_cd.focus();
			return false;
		}
	}
	return confirm("��˥�����Ͽ����򹹿����ޤ���������Ǥ�����");
}
function onload_body() {
	disp_spouse();
	disp_child();
}
function disp_spouse() {
	document.getElementById("id_spouse").style.display = document.form1.spouse_flg[0].checked ? "" : "none";
}
function disp_child() {
	var f = document.form1;
	document.getElementById("id_child").style.display = f.have_child[0].checked ? "" : "none";
	for (i = 0; i < <?=MAX_MONITOR_CHILD?>; i++)
		document.getElementById("id_child_" + i).style.display = (i < f.child_num.value) ? "" : "none";
	document.getElementById("id_add_child").style.display = (f.child_num.value < <?=MAX_MONITOR_CHILD?>) ? "" : "none";
	document.getElementById("id_del_child").style.display = (f.child_num.value > 1) ? "" : "none";
}
function add_child() {
	document.form1.child_num.value++;
	disp_child();
}
function del_child() {
	document.form1.child_num.value--;
	disp_child();
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�����ܾ���</td>
	</tr>
	<tr>
		<td class="m1">̾���ʴ�����<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1" size=25 maxlength=25 <?=value($fetch->mn_name1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2" size=25 maxlength=25 <?=value($fetch->mn_name2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�<?=MUST_ITEM?></td>
		<td class="n1">
			��<input class="kanji" type="text" name="name1_kana" size=25 maxlength=25 <?=value($fetch->mn_name1_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
			<br>
			̾<input class="kanji" type="text" name="name2_kana" size=25 maxlength=25 <?=value($fetch->mn_name2_kana)?>>
			<span class="note">�����ѥ������ʣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ǯ����<?=MUST_ITEM?></td>
		<td class="n1">
			����<input class="number" type="text" name="birthday_y" size=4 <?=value(get_datepart('Y', $fetch->mn_birthday))?>>ǯ
			<select name="birthday_m"><?select_month('', get_datepart('M', $fetch->mn_birthday))?></select>��
			<select name="birthday_d"><?select_day('', get_datepart('D', $fetch->mn_birthday))?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">̤����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="mikikon" <?=value_checked(1, $fetch->mn_mikikon)?>>̤��
			<input type="radio" name="mikikon" <?=value_checked(2, $fetch->mn_mikikon)?>>����
			<input type="radio" name="mikikon" <?=value_checked(3, $fetch->mn_mikikon)?>>����¾
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="sex" <?=value_checked(1, $fetch->mn_sex)?>>����
			<input type="radio" name="sex" <?=value_checked(2, $fetch->mn_sex)?>>����
		</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="mail_addr" size=50 maxlength=100 <?=value($fetch->mn_mail_addr)?>>
			<span class="note">��Ⱦ�ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�ѥ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="alpha" type="text" name="password" maxlength=20 <?=value($fetch->mn_password)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">HTML�᡼�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="html_mail" <?=value_checked(DBTRUE, $fetch->mn_html_mail)?>>��������
			<input type="radio" name="html_mail" <?=value_checked(DBFALSE, $fetch->mn_html_mail)?>>�������ʤ�
		</td>
	</tr>
	<tr>
		<td class="m1">�Ϥ��᡼������ե饰<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="haimail_flag" <?=value_checked(DBTRUE, $fetch->mn_haimail_flag)?>>��������
			<input type="radio" name="haimail_flag" <?=value_checked(DBFALSE, $fetch->mn_haimail_flag)?>>�������ʤ�
		</td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�ʼ����<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="number" type="text" name="jitaku_zip1" size=3 maxlength=3 <?=value(substr($fetch->mn_jitaku_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="jitaku_zip2" size=4 maxlength=4 <?=value(substr($fetch->mn_jitaku_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">�ｻ�ϰ�ʼ����<?=MUST_ITEM?></td>
		<td class="n1"><select name="jitaku_area"><? select_area('- ̤���� -', $fetch->mn_jitaku_area)?></select></td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="shokugyou_cd"><? select_shokugyou('- ̤���� -', $fetch->mn_shokugyou_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">�ȼ�<?=MUST_ITEM?></td>
		<td class="n1"><select name="gyoushu_cd"><? select_gyoushu('- ̤���� -', $fetch->mn_gyoushu_cd, 1)?></select></td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="shokushu_cd"><? select_shokushu('- ̤���� -', $fetch->mn_shokushu_cd)?></select></td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�ʶ�̳���</td>
		<td class="n1">
			<input class="number" type="text" name="kinmu_zip1" size=3 maxlength=3 <?=value(substr($fetch->mn_kinmu_zip, 0, 3))?>>
			-
			<input class="number" type="text" name="kinmu_zip2" size=4 maxlength=4 <?=value(substr($fetch->mn_kinmu_zip, 3, 4))?>>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳���ϰ�<?=MUST_ITEM?></td>
		<td class="n1"><select name="kinmu_area"><? select_area('- ̤���� -', $fetch->mn_kinmu_area)?></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���������</td>
	</tr>
	<tr>
		<td class="m1">Ʊ���²���۶��ԡ�</td>
		<td class="n1">
			<input type="radio" name="spouse_flg" <?=value_checked('1', $fetch->mn_spouse_flg)?> onclick="disp_spouse()">���ꡡ
			<input type="radio" name="spouse_flg" <?=value_checked('2', $fetch->mn_spouse_flg)?> onclick="disp_spouse()">�ʤ���
			<input type="radio" name="spouse_flg" <?=value_checked('3', $fetch->mn_spouse_flg)?> onclick="disp_spouse()">����¾��
			<input type="text" name="spouse_other" <?=value($fetch->mn_spouse_other)?>>
			<div id="id_spouse">
				<br>
				�ݡ��۶��Ԥ���ǯ����<br>
				&nbsp;&nbsp;&nbsp;
				<select name="spouse_birthday_y"><? select_year(1900, ' ', get_datepart('Y', $fetch->mn_spouse_birthday), date('Y')) ?></select> ǯ��
				<select name="spouse_birthday_m"><? select_month(' ', get_datepart('M', $fetch->mn_spouse_birthday)) ?></select> �
				<select name="spouse_birthday_d"><? select_day(' ', get_datepart('D', $fetch->mn_spouse_birthday)) ?></select> ��
				<br><br>
				�ݡ��۶��Ԥο���<br>
				&nbsp;&nbsp;&nbsp;
				<select name="spouse_shokugyo_cd" size="1"><?select_shokugyou('�����Ӳ�����', $fetch->mn_spouse_shokugyo_cd)?></select>
			</div>
		</td>
	</tr>
	<tr>
		<td class="m1">Ʊ���²�ʻҶ���</td>
		<td class="n1">
			<input type="radio" name="have_child" <?=value_checked('1', $fetch->mn_have_child)?> onclick="disp_child()">�Ҷ����ꡡ
			<input type="radio" name="have_child" <?=value_checked('2', $fetch->mn_have_child)?> onclick="disp_child()">�Ҷ��ʤ�
			<div id="id_child">
<?
$child = array();
$sql = "SELECT mc_sex,mc_birthday FROM t_monitor_child WHERE mc_monitor_id=$monitor_id ORDER BY mc_child_no";
$result2 = db_exec($sql);
$nrow2 = pg_numrows($result2);
for ($i = 0; $i < $nrow2; $i++) {
	$fetch2 = pg_fetch_object($result2, $i);

	$child[$i]->sex = $fetch2->mc_sex;
	$child[$i]->birthday_y = get_datepart('Y', $fetch2->mc_birthday);
	$child[$i]->birthday_m = get_datepart('M', $fetch2->mc_birthday);
	$child[$i]->birthday_d = get_datepart('D', $fetch2->mc_birthday);
}

$child_num = count($child);

for ($i = 0; $i < MAX_MONITOR_CHILD; $i++) {
?>
				<table cellpadding="1" cellspacing="0" id="id_child_<?=$i?>" <?=$i < $child_num ? '' : 'style="display:none"'?>>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">�ݡ�<?=$i + 1?>����</td>
					</tr>
					<tr>
						<td width="100">&nbsp;&nbsp;&nbsp; ����</td>
						<td>
							<input type="radio" name="child_sex[<?=$i?>]" <?=value_checked('1', $child[$i]->sex)?>>�˻ҡ�
							<input type="radio" name="child_sex[<?=$i?>]" <?=value_checked('2', $child[$i]->sex)?>>����
						</td>
					</tr>
					<tr>
						<td width="100">&nbsp;&nbsp;&nbsp; ��ǯ����</td>
						<td>
							<select name="child_birthday_y[<?=$i?>]"><? select_year(1900, ' ', $child[$i]->birthday_y, date('Y')) ?></select> ǯ��
							<select name="child_birthday_m[<?=$i?>]"><? select_month(' ', $child[$i]->birthday_m) ?></select> �
							<select name="child_birthday_d[<?=$i?>]"><? select_day(' ', $child[$i]->birthday_d) ?></select> ��
						</td>
					</tr>
				</table>
<?
}
?>
				&nbsp;&nbsp;&nbsp;
				<input type="button" id="id_add_child" value="�ɲ�" onclick="add_child()">
				<input type="button" id="id_del_child" value="���" onclick="del_child()">
				<input type="hidden" name="child_num" <?=value($child_num)?>>
			</div>
		</td>
	</tr>
	<tr>
		<td class="m1">Ʊ���²�ʥ��˥��ء�</td>
		<td class="n1">
			<input type="radio" name="senior_flg" <?=value_checked('1', $fetch->mn_senior_flg)?>>���ꡡ
			<input type="radio" name="senior_flg" <?=value_checked('2', $fetch->mn_senior_flg)?>>�ʤ�
		</td>
	</tr>
	<tr>
		<td class="m1">�������<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="housing_form" <?=value_checked(1, $fetch->mn_housing_form)?>>��ͷ���
			<input type="radio" name="housing_form" <?=value_checked(2, $fetch->mn_housing_form)?>>���罻��
		</td>
	</tr>
	<tr>
		<td class="m1">�֤ν�ͭ<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="have_car" <?=value_checked(1, $fetch->mn_have_car)?>>�ȵ��ȼ֤��ͭ���Ƥ���
			<input type="radio" name="have_car" <?=value_checked(2, $fetch->mn_have_car)?>>�ȵ��Τ߽�ͭ���Ƥ���
			<input type="radio" name="have_car" <?=value_checked(3, $fetch->mn_have_car)?>>�ȵ��Ͻ�ͭ���Ƥ��ʤ�
		</td>
	</tr>
	<tr>
		<td class="m1">�褯�Ԥ�����ӥ�</td>
		<td class="n1"><? checkbox_conveni($monitor_id, 4)?></td>
	</tr>
	<tr>
		<td class="m1">�褯�Ԥ������ѡ�</td>
		<td class="n1"><? checkbox_super($monitor_id, 4)?></td>
	</tr>
<?
// �ɲåץ�ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
	<tr>
		<td class="m1"><?=htmlspecialchars($profile_name)?></td>
		<td class="n1"><? checkbox_ma_profile($monitor_id, $profile_id, 4)?></td>
<?
}
?>
	<tr>
		<td class="m1">��̣������</td>
		<td class="n1"><? checkbox_genre($monitor_id, 4)?></td>
	</tr>
<? /* ?>
	<tr>
		<td class="m1">���ʾҲ�ʸ</td>
		<td class="n1">
			<textarea class="kanji" name="self_introduction" cols=60 rows=5><?=htmlspecialchars($fetch->mn_self_introduction)?></textarea><br>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
<? */ ?>
	<tr>
		<td class="m0" colspan=2>���Ŀ;���</td>
	</tr>
	<tr>
		<td class="m1">���𽻽꣱</td>
		<td class="n1">
			<input class="kanji" type="text" name="jitaku_addr1" size=80 maxlength=50 <?=value($fetch->mn_jitaku_addr1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���𽻽ꣲ</td>
		<td class="n1">
			<input class="kanji" type="text" name="jitaku_addr2" size=80 maxlength=50 <?=value($fetch->mn_jitaku_addr2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���𽻽ꣳ</td>
		<td class="n1">
			<input class="kanji" type="text" name="jitaku_addr3" size=80 maxlength=50 <?=value($fetch->mn_jitaku_addr3)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���������ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="jitaku_tel" size=20 maxlength=20 <?=value($fetch->mn_jitaku_tel)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����FAX�ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="jitaku_fax" size=20 maxlength=20 <?=value($fetch->mn_jitaku_fax)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�۵�Ϣ����</td>
		<td class="n1">
			<input class="kanji" type="text" name="urgent_addr" size=80 maxlength=50 <?=value($fetch->mn_urgent_addr)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
<?
/*
?>
	<tr>
		<td class="m1">��̳�轻�꣱</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr1" size=80 maxlength=50 <?=value($fetch->mn_kinmu_addr1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�轻�ꣲ</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr2" size=80 maxlength=50 <?=value($fetch->mn_kinmu_addr2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�轻�ꣳ</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_addr3" size=80 maxlength=50 <?=value($fetch->mn_kinmu_addr3)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳�������ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_tel" size=20 maxlength=20 <?=value($fetch->mn_kinmu_tel)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��̳��FAX�ֹ�</td>
		<td class="n1">
			<input class="alpha" type="text" name="kinmu_fax" size=20 maxlength=20 <?=value($fetch->mn_kinmu_fax)?>>
			<span class="note">��Ⱦ�ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
<?
*/
?>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_name" size=50 maxlength=50 <?=value($fetch->mn_kinmu_name)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">����̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_unit" size=50 maxlength=50 <?=value($fetch->mn_kinmu_unit)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��</td>
		<td class="n1">
			<input class="kanji" type="text" name="kinmu_post" size=50 maxlength=50 <?=value($fetch->mn_kinmu_post)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="type"><? select_monitor_type('', $fetch->mn_type)?></select></td>
	</tr>
	<tr>
		<td class="m1">�֥�å��ꥹ�ȥե饰</td>
		<td class="n1">
			<select name="blacklist_flg"><? select_blacklist('����ʤ�', $fetch->mn_blacklist_flg) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><select name="status"><? select_monitor_status('', $fetch->mn_status)?></select></td>
	</tr>
	<tr>
		<td class="m1">�ݥ����</td>
		<td class="n1">
			���ݥ����<input class="number" type="text" name="curr_point" size=10 maxlength=10 <?=value($fetch->mp_curr_point)?>>
			���ѥݥ����<input class="number" type="text" name="total_point" size=10 maxlength=10 <?=value($fetch->mp_total_point)?>>
			��<input type="checkbox" name="point_change" value="1">�ѹ�
		</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȥ�����ȥ�����</td>
		<td class="n1"><select name="stop_period_flag"><option <?=value_selected(DBFALSE, $fetch->mp_stop_period_flag)?>>ͭ��</option><option <?=value_selected(DBTRUE, $fetch->mp_stop_period_flag)?>>̵��</option></select></td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->mn_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǿ���������</td>
		<td class="n1"><?=format_datetime($fetch->mn_update_date)?></td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1"><?=format_datetime($fetch->mn_taikai_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="monitor_id" <?=value($monitor_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
