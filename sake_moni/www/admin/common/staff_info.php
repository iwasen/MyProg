<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�����åվ���ɽ��
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('staff', '�����åմ���', '�����åվ���ɽ��', BACK_CLOSE);

if ($staff_id != '')
	$where = "st_staff_id=$staff_id";
elseif ($mail_addr != '')
	$where = 'st_mail_addr=' . sql_char(strtolower($mail_addr)) . ' AND st_status<>9';

if ($where) {
	$sql = "SELECT * FROM t_staff WHERE $where";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<?
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
?>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">�����ܾ���</td>
		<td align="right"><input type="button" value="���Ĥ��롡" onclick="window.close()"></td>
	</tr>
	<tr>
		<td class="m2" width="20%">̾���ʴ�����</td>
		<td class="n2"><?=htmlspecialchars("$fetch->st_name1 $fetch->st_name2")?></td>
	</tr>
	<tr>
		<td class="m2">̾���ʥ��ʡ�</td>
		<td class="n2"><?=htmlspecialchars("$fetch->st_name1_kana $fetch->st_name2_kana")?></td>
	</tr>
	<tr>
		<td class="m2">��ǯ����</td>
		<td class="n2"><?=format_date($fetch->st_birthday)?></td>
	</tr>
	<tr>
		<td class="m2">����</td>
		<td class="n2"><?=decode_sex($fetch->st_sex)?></td>
	</tr>
	<tr>
		<td class="m2">�᡼�륢�ɥ쥹</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>����̳�����</td>
	</tr>
	<tr>
		<td class="m2">��̳��͹���ֹ�</td>
		<td class="n2"><?=format_zip($fetch->st_kinmu_zip)?></td>
	</tr>
	<tr>
		<td class="m2">��̳���ϰ�</td>
		<td class="n2"><?=decode_area($fetch->st_kinmu_area)?></td>
	</tr>
	<tr>
		<td class="m2">��̳�轻�꣱</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_kinmu_addr1)?></td>
	</tr>
	<tr>
		<td class="m2">��̳�轻�ꣲ</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_kinmu_addr2)?></td>
	</tr>
	<tr>
		<td class="m2">��̳�轻�ꣳ</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_kinmu_addr3)?></td>
	</tr>
	<tr>
		<td class="m2">��̳�������ֹ�</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_kinmu_tel)?></td>
	</tr>
	<tr>
		<td class="m2">��̳��FAX�ֹ�</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_kinmu_fax)?></td>
	</tr>
	<tr>
		<td class="m2">���̾</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_kinmu_name)?></td>
	</tr>
	<tr>
		<td class="m2">����̾</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_kinmu_unit)?></td>
	</tr>
	<tr>
		<td class="m2">��</td>
		<td class="n2"><?=htmlspecialchars($fetch->st_kinmu_post)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m2">����</td>
		<td class="n2"><?=decode_staff_status($fetch->st_status)?></td>
	</tr>
	<tr>
		<td class="m2">��Ͽ����</td>
		<td class="n2"><?=format_datetime($fetch->st_regist_date)?></td>
	</tr>
	<tr>
		<td class="m2">�ǿ���������</td>
		<td class="n2"><?=format_datetime($fetch->st_update_date)?></td>
	</tr>
</table>
<?
} else {
?>
<p class="msg">�������륹���åդ���Ͽ����Ƥ��ޤ���</p>
<?
}
?>
<br>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</div>

<? page_footer() ?>
</body>
</html>
