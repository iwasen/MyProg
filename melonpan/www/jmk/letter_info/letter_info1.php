<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/database.php");
include ("../inc/admin_inc.php");
include ("../inc/com_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���ޥ����ܾ�����ƥʥ�
'******************************************************/
if($mag_id == '000013') {
	$title_text = $TT_letter_bakyun;
	$title_color = $TC_MASTER;
}

//'=== ���ƥ�������� ===
function SelectCategory($mag_catg) {
	$sql = "SELECT * FROM M_MAG_CATG ORDER BY catg_id";
	$select = new select_sql($sql);
	while ($select->fetch()) {
		$catg_id = $select->field("catg_id");
		$catg = $select->field("catg");
		if ($catg_id == $mag_catg) {
			print "<option value=\"$catg_id\" selected>". $catg ."</option>\n";
		} else {
			print "<option value=\"$catg_id\">". $catg . "</option>\n";
		}
	}
}

//'=== �ɤ��ط������ ===
function SelectBg($mag_bg) {
	$sql = "SELECT * FROM M_MAG_BG ORDER BY bg_id";
	$select = new select_sql($sql);
	while ($select->fetch()) {
		$bg_id = $select->field("bg_id");
		$bg = $select->field("bg");
		if ($bg_id == $mag_bg) {
			print "<option value=\"$bg_id\" selected>". $bg ."</option>\n";
		} else {
			print "<option value=\"$bg_id\">". $bg . "</option>\n";
		}
	}
}

//'=== �ɤ���ߤ������� ===
function SelectOccup($mag_reader_occup) {
	$sql = "SELECT * FROM M_MAG_OCCUP ORDER BY occup_id";
	$select = new select_sql($sql);
	while ($select->fetch()) {
		$occup_id = $select->field("occup_id");
		$occup = $select->field("occup");
		if ($occup_id == $mag_reader_occup) {
			print "<option value=\"$occup_id\" selected>". $occup ."</option>\n";
		} else {
			print "<option value=\"$occup_id\">". $occup . "</option>\n";
		}
	}
}

$sql = "SELECT * FROM M_MAGAZINE MM, M_PUBLISHER MP WHERE MM.mag_id='000013'";
$select = new select_sql($sql);
if ($select->fetch()) {
	$mail_add_main = $select->field("mail_add_main");
	$melmel_id = $select->field("melmel_id");
	$mail_add_publish = $select->field("mail_add_publish");
	$mag_nm = $select->field("mag_nm");
	$mag_intro_long = $select->field("mag_intro_long");
	$mag_intro_short = $select->field("mag_intro_short");
	$mag_hp = $select->field("mag_hp");
	$mag_type = $select->field("mag_type");
	$mag_cycle_day = $select->field("mag_cycle_day");
	$mag_catg_1 = $select->field("mag_catg_1");
	$mag_catg_2 = $select->field("mag_catg_2");
	$mag_bg_1 = $select->field("mag_bg_1");
	$mag_bg_2 = $select->field("mag_bg_2");
	$mag_reader_sex = $select->field("mag_reader_sex");
	$mag_reader_age = $select->field("mag_reader_age");
	$mag_reader_area = $select->field("mag_reader_area");
	$mag_reader_occup = $select->field("mag_reader_occup");
	$mag_bn_pub = $select->field("mag_bn_pub");
	$mag_present_ins_flg = $select->field("mag_present_ins_flg");
	$mag_from_addr = $select->field("mag_from_addr");
	$mag_subject_tmpl = $select->field("mag_subject_tmpl");
	$mag_header_tmpl = $select->field("mag_header_tmpl");
	$mag_footer_tmpl = $select->field("mag_footer_tmpl");
	$mag_original_flg = $select->field("mag_original_flg");
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
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnSubmit_form1() {
 with (document.form1) {
    if (mail_add_publish.value == "") {
      alert("���ޥ�ȯ�Ը��᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
      mail_add_publish.focus();
      return false;
    }
    if (mag_nm.value == "") {
      alert("���ޥ������ȥ�����Ϥ��Ƥ���������");
      mag_nm.focus();
      return false;
    }
    if (mag_intro_long.value == "") {
      alert("���ޥ��Ҳ�ʸ[���]�����Ϥ��Ƥ���������");
      mag_intro_long.focus();
      return false;
    }
    if (mag_intro_short.value == "") {
      alert("���ޥ��Ҳ�ʸ[���硼��]�����Ϥ��Ƥ���������");
      mag_intro_short.focus();
      return false;
    }
    if (mag_cycle_day.value == "") {
      alert("ȯ�Լ��������Ϥ��Ƥ���������");
      mag_cycle_day.focus();
      return false;
    }
  }
	return confirm("�������ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" style="margin:0" action="letter_info2.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">�����ޥ����ܾ�����ƥʥ�</td>
		</tr>
	</table>
	<table border=0 cellspacing=1 cellpadding=3 width="800">
		<tr>
			<td class="m4" align="right"><nobr>1.</nobr></td>
			<td class="m4"><nobr>���ܥ᡼�륢�ɥ쥹</nobr></td>
			<td class="n6"><?= $mail_add_main ?></td>
			</td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>2.</nobr></td>
			<td class="m4"><nobr>����뎥net ID</nobr></td>
			<td class="n6"><input size=6 name="melmel_id" maxlength=5 value="<?= $melmel_id ?>"></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>3.</nobr></td>
			<td class="m4"><nobr>���ޥ�ȯ�Ը�<br>�᡼�륢�ɥ쥹</nobr></td>
			<td class="n6"><input size=50 name="mail_add_publish" maxlength=50 value="<?= $mail_add_publish ?>"></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>4.</nobr></td>
			<td class="m4"><nobr>���ޥ������ȥ�</nobr></td>
			<td class="n6"><input size=80 name=mag_nm maxlength=40 value="<?= $mag_nm ?>"></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>5.</nobr></td>
			<td class="m4"><nobr>���ޥ��Ҳ�ʸ[���]</nobr></td>
			<td class="n6"><textarea  name=mag_intro_long cols="60" rows="5"><?= $mag_intro_long ?></textarea></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>6.</nobr></td>
			<td class="m4"><nobr>���ޥ��Ҳ�ʸ[���硼��]</nobr></td>
			<td class="n6"><textarea  name=mag_intro_short cols="60" rows="3"><?= $mag_intro_short ?></textarea></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>7.</nobr></td>
			<td class="m4"><nobr>�ۡ���ڡ���URL��Ǥ�ա�</nobr></td>
			<td class="n6"><input size=80 name=mag_hp maxlength=80 value="<?= $mag_hp ?>"></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>8.</nobr></td>
			<td class="m4"><nobr>������</nobr></td>
			<td class="n6">
				<input type="radio" name="mag_type" value="01" checked>�ƥ����ȤΤ�
			</td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>9.</nobr></td>
			<td class="m4"><nobr>ȯ�Լ���</nobr></td>
			<td class="n6"><input size=20 name=mag_cycle_day maxlength=20 value="<?= $mag_cycle_day ?>"></td>
		</tr>
		<tr>
			<td class="m4" align="right" rowspan=2><nobr>10.</nobr></td>
			<td class="m4" rowspan=2><nobr>���ƥ���</nobr></td>
			<td class="n6">1��<select name="mag_catg_1"><? SelectCategory($mag_catg_1); ?></select>
		</tr>
		<tr>
			<td class="n6">2��<select name="mag_catg_2"><? SelectCategory($mag_catg_2); ?></select></td>
		</tr>
		<tr>
			<td class="m4" align="right" rowspan=2><nobr>11.</nobr></td>
			<td class="m4" rowspan=2><nobr>�ɤ��ط�</nobr></td>
			<td class="n6">1��<select name="mag_bg_1"><? SelectBg($mag_bg_1); ?></select>
		</tr>
		<tr>
			<td class="n6">2��<select name="mag_bg_2"><? SelectBg($mag_bg_2); ?></select></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>12.</nobr></td>
			<td class="m4"><nobr>�ɤ�Ǥ�餤��������</nobr></td>
			<td class="n6">
				<input type="radio" name="mag_reader_sex" value="1" <? if ($mag_reader_sex == '1') { print " checked"; } ?>>����
				<input type="radio" name="mag_reader_sex" value="2" <? if ($mag_reader_sex == '2') { print " checked"; } ?>>����
			</td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>13.</nobr></td>
			<td class="m4"><nobr>�ɤ�Ǥ�餤����ǯ��</nobr></td>
			<td class="n6">
				<input type="radio" name="mag_reader_age" value="10" <? if ($mag_reader_age == '10') { print " checked"; } ?>>10��
				<input type="radio" name="mag_reader_age" value="20" <? if ($mag_reader_age == '20') { print " checked"; } ?>>20��
				<input type="radio" name="mag_reader_age" value="30" <? if ($mag_reader_age == '30') { print " checked"; } ?>>30��
				<input type="radio" name="mag_reader_age" value="40" <? if ($mag_reader_age == '40') { print " checked"; } ?>>40��
				<input type="radio" name="mag_reader_age" value="50" <? if ($mag_reader_age == '50') { print " checked"; } ?>>50��
				<input type="radio" name="mag_reader_age" value="60" <? if ($mag_reader_age == '60') { print " checked"; } ?>>60��

			</td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>14.</nobr></td>
			<td class="m4"><nobr>�ɤ�Ǥ�餤�������ꥢ</nobr></td>
			<td class="n6">
				<input type="radio" name="mag_reader_area" value="0001" <? if ($mag_reader_area == '0001') { print " checked"; } ?>>�̳�ƻ
				<input type="radio" name="mag_reader_area" value="0002" <? if ($mag_reader_area == '0002') { print " checked"; } ?>>����
				<input type="radio" name="mag_reader_area" value="0003" <? if ($mag_reader_area == '0003') { print " checked"; } ?>>����
				<input type="radio" name="mag_reader_area" value="0004" <? if ($mag_reader_area == '0004') { print " checked"; } ?>>����
				<input type="radio" name="mag_reader_area" value="0005" <? if ($mag_reader_area == '0005') { print " checked"; } ?>>�ᵦ
				<input type="radio" name="mag_reader_area" value="0006" <? if ($mag_reader_area == '0006') { print " checked"; } ?>>���
				<input type="radio" name="mag_reader_area" value="0007" <? if ($mag_reader_area == '0007') { print " checked"; } ?>>�͹�
				<input type="radio" name="mag_reader_area" value="0008" <? if ($mag_reader_area == '0008') { print " checked"; } ?>>�彣������
				<input type="radio" name="mag_reader_area" value="0009" <? if ($mag_reader_area == '0009') { print " checked"; } ?>>����
			</td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>15</nobr>.</td>
			<td class="m4"><nobr>�ɤ�Ǥ�餤��������</nobr></td>
			<td class="n6"><select name="mag_reader_occup"><? SelectOccup($mag_reader_occup); ?></select>
			</td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>16.</nobr></td>
			<td class="m4"><nobr>�Хå��ʥ�С��θ���/�����</nobr></td>
			<td class="n6">
				<input type="radio" name="mag_bn_pub" value="1" <? if ($mag_bn_pub == '1') { print " checked"; } ?>>����
				<input type="radio" name="mag_bn_pub" value="2" <? if ($mag_bn_pub == '2') { print " checked"; } ?>>�����
				<input type="radio" name="mag_bn_pub" value="3" <? if ($mag_bn_pub == '3') { print " checked"; } ?>>�ǿ���Τ߸���
			</td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>17.</nobr></td>
			<td class="m4"><nobr>�ץ쥼��ȱ����������</nobr></td>
			<td class="n6">
				<input type="radio" name="mag_present_ins_flg" value="1" <? if ($mag_present_ins_flg == '1') { print " checked"; } ?>>��������
				<input type="radio" name="mag_present_ins_flg" value="2" <? if ($mag_present_ins_flg == '2') { print " checked"; } ?>>�������ʤ�
			</td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>18.</nobr></td>
			<td class="m4"><nobr>�ۿ����ޥ���from���ɥ쥹</nobr></td>
			<td class="n6"><input size=50 name="mag_from_addr" maxlength=50 value="<?= $mag_from_addr ?>"></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>19.</nobr></td>
			<td class="m4"><nobr>�ۿ����ޥ��Υ��֥�������</nobr></td>
			<td class="n6"><input size=80 name="mag_subject_tmpl" maxlength=100 value="<?= $mag_subject_tmpl ?>"></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>20.</nobr></td>
			<td class="m4"><nobr>���ޥ��إå��Υƥ�ץ졼��</nobr></td>
			<td class="n6"><textarea  name="mag_header_tmpl" cols="60" rows="5"><?= $mag_header_tmpl ?></textarea></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>21.</nobr></td>
			<td class="m4"><nobr>���ޥ��եå��Υƥ�ץ졼��</nobr></td>
			<td class="n6"><textarea  name="mag_footer_tmpl" cols="60" rows="5"><?= $mag_footer_tmpl ?></textarea></td>
		</tr>
		<tr>
			<td class="m4" align="right"><nobr>22.</nobr></td>
			<td class="m4"><nobr>����Ѥ󥪥ꥸ�ʥ�</nobr></td>
			<td class="n6">
				<input type="radio" name="mag_original_flg" value="1" <? if ($mag_original_flg == '1') { print " checked"; } ?>>����Ѥ�Τߤ�ȯ��
				<input type="radio" name="mag_original_flg" value="2" <? if ($mag_original_flg == '2') { print " checked"; } ?>>����Ѥ�ʳ��Ǥ�ȯ��
			</td>
		</tr>
	</table><br>
	<input type="hidden" name="mag_id" value="<?= $mag_id ?>">
	<input type="submit" value=" ���� ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
