<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:����Ŀ͸���
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
//include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
//include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// ��Ͽ����
function decode_step($code) {
	if ($code) {
		switch($code) {
		case 1:
			return "����Ͽ��";
			break;
		case 2:
			return "����Ͽ��";
			break;
		case 3:
			return "����Ͽ";
			break;
		case 9:
			return "���";
			break;
		default:
			return "����";
			break;
		}
	}
}

$sql = "SELECT * FROM t_member LEFT JOIN t_point ON pt_seq_no=mb_seq_no WHERE mb_seq_no=" . sql_number($seq_no);
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('��˥����ɣĤ�����', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=�������?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onload() {
	var f = document.form1;
	if (f.family_ctg)
		show_hide('family', f.disp_family.checked);
}

function show_hide(id, show) {
	if (!document.form1.disp_family.disabled)
	document.all(id)[0].style.display = show ? "" : "none";
}

function onSubmit_form1(f) {
	if (f.member_id.value == "") {
		alert("���ID�����Ϥ��Ƥ���������");
		f.member_id.focus();
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
	return confirm("�����Ͽ����򹹿����ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="onload()">

<div align="center">
<form method="post" name="form1">
<table width=410 class="small">
	<tr>
		<td align="right" colspan=2><input type="button" value="���Ĥ��롡" onclick="window.close()"></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>���������</td>
	</tr>
	<tr>
		<td class="m1" width=100>��Ͽ����</td>
		<td class="n1"><?=decode_step($fetch->mb_step)?></td>
	</tr>
	<tr>
		<td class="m1">���ID</td>
		<td class="n1"><?=$fetch->mb_id?></td>
	</tr>
	<tr>
		<td class="m1">�ѥ����</td>
		<td class="n1"><?=$fetch->mb_password?></td>
	</tr>
	<tr>
		<td class="m1">�᡼�륢�ɥ쥹</td>
		<td class="n1"><?=$fetch->mb_mail_addr?></td>
	</tr>
<?
if ($fetch->mb_step != 1) {
?>
	<tr>
		<td class="m1">̾���ʴ�����</td>
		<td class="n1"><?=$fetch->mb_name1?>&nbsp;<?=$fetch->mb_name2?></td>
	</tr>
	<tr>
		<td class="m1">̾���ʥ��ʡ�</td>
		<td class="n1"><?=$fetch->mb_name1_kana?>&nbsp;<?=$fetch->mb_name2_kana?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_sex($fetch->mb_sex)?></td>
	</tr>
	<tr>
		<td class="m1">��ǯ����</td>
		<td class="n1">
			����&nbsp;<?=get_datepart('Y', $fetch->mb_birthday)?>ǯ
			<?=get_datepart('M', $fetch->mb_birthday)?></select>��
			<?=get_datepart('D', $fetch->mb_birthday)?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�</td>
		<td class="n1"><?=substr($fetch->mb_zip, 0, 3)?>-<?=substr($fetch->mb_zip, 3, 4)?></td>
	</tr>
	<tr>
		<td class="m1">���𽻽�</td>
		<td class="n1"><?=decode_area($fetch->mb_area_cd)?>&nbsp;<?=$fetch->mb_address?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>��Ʊ��Τ���²</td>
	</tr>
	<tr>
		<td class="m1">��²���ƥ���</td>
		<input type="hidden" name="family_ctg" <?=value($fetch->mb_family_ctg)?>>
		<td class="n1">
			<?=decode_family_ctg($fetch->mb_family_ctg)?>&nbsp;&nbsp;
			<input type="checkbox" name="disp_family" onclick="show_hide('family', checked)" <?=$fetch->mb_family_ctg > 1 ? '': 'disabled';?>>
			<span class="note">��²����ɽ��</span></td>
	</tr>
<?
if ($fetch->mb_family_ctg > 1) {
?>
	<tr id="family">
		<td class="m1">��²����</td>
		<td class="n1">
			<table border=0 cellspacing=2 cellpadding=0 width="100%">
<?
	$sql = "select * from t_family where fm_mb_seq_no=" . sql_number($seq_no);
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch2 = pg_fetch_object($result, $i);
?>
				<tr id="family">
					<td align="right" width=30><?=$i + 1?>.</td>
					<td width=100><font size=-1><?=decode_family_rel($fetch2->fm_family_rel_cd)?></font></td>
					<td><font size=-1><?=$fetch2->fm_age?> ��</font></td>
				</tr>
<?
	}
?>
			</table>
		</td>
	</tr>
<?
}
?>
	<tr>
		<td class="m0" colspan=2>������ˤĤ���</td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1">
			���֡�&nbsp;<?=decode_keitai($fetch->mb_keitai_cd)?><br>
			�Ǻࡧ&nbsp;<?=decode_sozai($fetch->mb_sozai_cd)?><br>
			�ء��٥�ϥ�����&nbsp;<?=$fetch->mb_hebel_flag == 1?'�Ϥ�':'������'?>
		</td>
	</tr>
	<tr>
		<td class="m1">����ǯ��</td>
		<td class="n1"><?=decode_chikunen($fetch->mb_chikunen_cd)?>&nbsp;ǯ</td>
	</tr>
	<tr>
		<td class="m1">����ּ��</td>
		<td class="n1"><?=decode_room($fetch->mb_room_cd)?></td>
	</tr>
	<tr>
		<td class="m1">�ｻ����</td>
		<td class="n1"><?=decode_space($fetch->mb_space_cd)?>&nbsp;m3</td>
	</tr>
	<tr>
		<td class="m1">����勵����</td>
		<td class="n1"><?=decode_boiler($fetch->mb_boiler_cd)?></td>
	</tr>
	<tr>
		<td class="m1">������</td>
		<td class="n1"><?=decode_gas_kind($fetch->mb_gas_kind_cd)?>
<?
if ($fetch->mb_gas_kind_cd == 1) {
?>
			<font size=2>���Իԥ��������Τߢ� </font>
			<!-- �Իԥ�������ԤΤ�ͭ�� -->
			<?=decode_gas_type($fetch->mb_gas_type_cd)?>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">��ƻ�����</td>
		<td class="n1"><?=$fetch->mb_water_month == 1?'�����':'������'?></td>
	</tr>
	<tr>
		<td class="m1">�ֽ�ͭ</td>
		<td class="n1">
			<table width=100%>
				<tr>
					<td width=70><font size=2>��������</font></td>
					<td><font size=2><?=$fetch->mb_gcar_num?>&nbsp;��</font></td>
				</tr>
				<tr>
					<td><font size=2>�ǥ��������</font></td>
					<td><font size=2><?=$fetch->mb_dcar_num?>&nbsp;��</font></td>
				</tr>
				<tr>
					<td><font size=2>�Х���</font></td>
					<td><font size=2><?=$fetch->mb_bike_num?>&nbsp;��</font></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�����Ϲ��ܾ���</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_input($fetch->mb_ol_flag)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=decode_input($fetch->mb_gm_flag)?></td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1"><?=decode_input($fetch->mb_gl_flag)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m1">�������</td>
		<td class="n1"><?=$fetch->mb_kind == 1?'�ط���':'���̲��'?></select></td>
	</tr>
	<tr>
		<td class="m1">�ݥ����</td>
		<td class="n1"><?=$fetch->pt_point != ''?$fetch->pt_point:0?>&nbsp;�ݥ����</td>
	</tr>
	<tr>
		<td class="m1">��Ͽ����</td>
		<td class="n1"><?=format_datetime($fetch->mb_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">�ǿ���������</td>
		<td class="n1"><?=format_datetime($fetch->mb_modify_date)?></td>
	</tr>
<?
}
?>
</table>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</div>
</body>
</html>
