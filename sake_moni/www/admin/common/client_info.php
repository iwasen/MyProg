<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:���饤����Ⱦ���ɽ��
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
set_global('client', '���饤����ȴ���', '���饤����Ⱦ���ɽ��', BACK_CLOSE);

if ($client_id != '')
	$where = "cl_client_id=$client_id";
elseif ($mail_addr != '')
	$where = 'cl_mail_addr=' . sql_char(strtolower($mail_addr)) . ' AND cl_status<>9';

if ($where) {
	$sql = "SELECT * FROM m_client WHERE $where";
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
		<td class="n2"><?=htmlspecialchars("$fetch->cl_name1 $fetch->cl_name2")?></td>
	</tr>
	<tr>
		<td class="m2">�᡼�륢�ɥ쥹</td>
		<td class="n2"><?=htmlspecialchars($fetch->cl_mail_addr)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>������¾����</td>
	</tr>
	<tr>
		<td class="m2">����</td>
		<td class="n2"><?=decode_staff_status($fetch->cl_status)?></td>
	</tr>
	<tr>
		<td class="m2">��Ͽ����</td>
		<td class="n2"><?=format_datetime($fetch->cl_regist_date)?></td>
	</tr>
	<tr>
		<td class="m2">�ǿ���������</td>
		<td class="n2"><?=format_datetime($fetch->cl_update_date)?></td>
	</tr>
</table>
<?
} else {
?>
<p class="msg">�������륯�饤����Ȥ���Ͽ����Ƥ��ޤ���</p>
<?
}
?>
<br>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</div>

<? page_footer() ?>
</body>
</html>
