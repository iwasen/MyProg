<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�ޡ���������å�����ɽ��
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/decode.php");

//window_size 500:510

//����DECODE
function decode_type($code) {
	switch ($code) {
	case 1:
		return '�᡼��˥塼����Ͽ��';
		break;
	case 2:
		return '̵����˥�����Ͽ��';
		break;
	case 3:
		return 'ͭ����˥�����Ͽ��';
		break;
	case 4:
		return '�����������';
		break;
	default:
		return '̤����';
		break;
	}
}
//����DECODE
function decode_status($code) {
	switch ($code) {
	case 0:
		return 'ͭ��';
		break;
	case 2:
		return '��ã����';
		break;
	case 9:
		return '���';
		break;
	default:
		return '����';
		break;
	}
}
// �ޡ�������DECODE
function decode_marketer($code) {
	if ($code != '') {
		$sql = "SELECT mr_name1 || ' ' || mr_name2 FROM t_marketer WHERE mr_marketer_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}
//��å�����DECODE
function decode_msg($code) {
	if ($code == 1) {
		$msg = " �ͤ���Υ�å��������ƤǤ���";
	}	elseif ($code == 2) {
		$msg = " �ͤؤΥ�å��������ƤǤ���";
	}
	return $msg;
}
//�ᥤ�����

set_global('�ޡ���������å�����ɽ��','');

$sql = "SELECT * FROM t_communication WHERE co_seq_no=$co_no";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, $i);
if ($fetch->co_direction == 1) {
	$msg = " �ͤ���Υ�å��������ƤǤ���";
	$border_color = "66cc66";
	$bordercolorlight = "ccffcc";
	$bgcolor = "#ccffcc";
} elseif ($fetch->co_direction == 2) {
	$msg = " �ͤؤΥ�å��������ƤǤ���";
	$border_color = "#ff9966";
	$bordercolorlight = "#ffcc99";
	$bgcolor = "#ffd0b0";

}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=650,height=700");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1" action="kanri_suuji2.php">
	<center>
		<table width='450'>
			<tr>
				<td align=right valign=bottom norap><small><font color=blue>��</font><A href='JavaScript:window.close()'>�Ĥ���</A></small></td>
				</td>
			</tr>
			<tr>
				<td>��<?=htmlspecialchars(decode_marketer($fetch->co_marketer_id) . "$msg")?></td>
			</tr>
		</table>
		<table width='450' border=1 cellspacing=0 cellpadding=1 bordercolorlight="<?=$bordercolorlight?>" frame="box" bordercolor="<?=$border_color?>">
			<tr>
				<td width="35%" bgcolor="<?=$bgcolor?>">�����ȥ�</td>
				<td><?=htmlspecialchars($fetch->co_title)?></td>
			</tr>
			<tr>
				<td bgcolor="<?=$bgcolor?>">������</td>
				<td><?=format_datetime($fetch->co_regist_date)?></td>
			</tr>
			<tr>
				<td colspan=2><textarea cols=60 rows=25 readonly><?=htmlspecialchars($fetch->co_text)?></textarea></td>
			</tr>
		</table>
	<input type="button" value="���Ĥ��롡" onclick="window.close()">
	</center>
</form>
</body>
</html>