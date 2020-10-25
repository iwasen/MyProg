<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�ޡ���������Ͽ����ɽ��
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
		return '̵���ޡ���������Ͽ��';
		break;
	case 3:
		return 'ͭ���ޡ���������Ͽ��';
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

// ����������ȼ���
function get_assistant($marketer_id) {
	$assistant_ary = array();

	$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_parent_id=$marketer_id AND mr_status<>9 AND mr_type=4 ORDER BY mr_marketer_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$assistant_ary[] = $fetch->mr_marketer_id;
	}

	return $assistant_ary;
}

//�ᥤ�����

set_global('�ޡ���������Ͽ����','');

$sql = "SELECT * FROM t_marketer WHERE mr_marketer_id=$mr_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
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
				<td>���ޡ���������Ͽ����</td>
			</tr>
		</table>
		<table width='450' <?=LIST_TABLE?>>
			<tr>
				<td class="m3s" colspan="2">���ܾ���</td>
			</tr>
			<tr>
				<td width="35%" class="n3">�ޡ�������ID</td>
				<td><?=$fetch->mr_marketer_id?></td>
			</tr>
			<tr>
				<td class="n3">��̾</td>
				<td><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
			</tr>
			<tr>
				<td class="n3">��̾(����)</td>
				<td><?=htmlspecialchars("$fetch->mr_name1_kana $fetch->mr_name2_kana")?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>��ǯ����</nobr></td>
				<td><?=format_date($fetch->mr_birthday)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>����</nobr></td>
				<td><?=decode_sex($fetch->mr_sex)?></td>
			</tr>
			<tr>
				<td class="n3">�᡼�륢�ɥ쥹</td>
				<td><?=htmlspecialchars("$fetch->mr_mail_addr")?></td>
			</tr>
<!--			<tr>
				<td class="n3"><nobr>�ѥ����</nobr></td>
				<td><?=htmlspecialchars($fetch->mr_password)?></td>
			</tr>	-->
			<tr>
				<td class="n3">����</td>
				<td><?=decode_shokugyou($fetch->mr_shokugyou_cd)?></td>
			</tr>
			<tr>
				<td class="n3">�ȼ�</td>
				<td><?=decode_gyoushu($fetch->mr_gyoushu_cd)?></td>
			</tr>
			<tr>
				<td class="n3">����</td>
				<td><?=decode_shokushu($fetch->mr_shokushu_cd)?></td>
			</tr>
		</table><br>
		<table width='450' <?=LIST_TABLE?>>
			<tr>
				<td class="m3s" colspan="3">��̳�����</td>
			</tr>
			<tr>
				<td width=35% class="n3"><nobr>��̳��͹���ֹ�</nobr></td>
				<td><?=format_zip($fetch->mr_kinmu_zip)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>��̳���ϰ�</nobr></td>
				<td><?=decode_area($fetch->mr_kinmu_area)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>��̳�轻��</nobr></td>
				<td><?=htmlspecialchars("$fetch->mr_kinmu_addr1 $fetch->mr_kinmu_addr2 $fetch->mr_kinmu_addr3")?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>��̳�������ֹ�</nobr></td>
				<td><?=$fetch->mr_kinmu_tel?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>��̳��FAX�ֹ�</nobr></td>
				<td><?=$fetch->mr_kimnu_fax?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>���̾</nobr></td>
				<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>����̾</nobr></td>
				<td><?=htmlspecialchars($fetch->mr_kinmu_unit)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>��</nobr></td>
				<td><?=htmlspecialchars($fetch->mr_kinmu_post)?></td>
			</tr>
		</table><br>
		<table width='450' <?=LIST_TABLE?>>
			<tr>
				<td class="m3s" colspan="3">����¾����</td>
			</tr>
			<tr>
				<td width=35% class="n3"><nobr>�����������</nobr></td>
				<td><?=decode_agent($fetch->mr_agent_id)?></td>
			</tr>
<?
$assistant_ary = get_assistant($mr_id);
?>
			<tr>
				<td class="n3"><nobr>����������ȣ�</nobr></td>
				<td><?=decode_marketer($assistant_ary[0])?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>����������ȣ�</nobr></td>
				<td><?=decode_marketer($assistant_ary[1])?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>����</nobr></td>
				<td><?=decode_type($fetch->mr_type)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>����</nobr></td>
				<td><?=decode_status($fetch->mr_status)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>��Ͽ����</nobr></td>
				<td><?=format_date($fetch->mr_regist_date)?></td>
			</tr>
			<tr>
				<td class="n3"><nobr>��������</nobr></td>
				<td><?=format_date($fetch->mr_update_date, '')?></td>
			</tr>
		</table>
	<input type="button" value="���Ĥ��롡" onclick="window.close()">
	</center>
</form>
</body>
</html>