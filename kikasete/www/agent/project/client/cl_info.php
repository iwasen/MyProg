<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:��Ⱦ���ɽ��
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/decode.php");
include("$inc/format.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ����åޥ�����Ͽ', '��Ⱦ���ɽ��', BACK_TOP);

$sql = "SELECT * FROM m_client WHERE cl_client_id=$cl_id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('���ID������', __FILE__);
$fetch = pg_fetch_object($result, 0);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<form>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����Ⱦ���</td>
	</tr>
	<tr>
		<td class="m1" width="30%">���ID</td>
		<td class="n1"><?=$fetch->cl_client_id?></td>
	</tr>
	<tr>
		<td class="m1">���̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->cl_name)?></td>
	</tr>
	<tr>
		<td class="m1">���̾(����)</td>
		<td class="n1"><?=htmlspecialchars($fetch->cl_name_kana)?></td>
	</tr>
	<tr>
		<td class="m1">͹���ֹ�</td>
		<td class="n1"><?=format_zip($fetch->cl_zip_code)?></td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<?=decode_area($fetch->cl_address1)?><br>
			<?=htmlspecialchars($fetch->cl_address2)?><br>
			<?=htmlspecialchars($fetch->cl_address3)?><br>
			<?=htmlspecialchars($fetch->cl_address4)?>
		</td>
	</tr>
	<tr>
		<td class="m1">�ȼ�</td>
		<td class="n1"><?=decode_gyoushu($fetch->cl_gyoushu_cd)?></td>
	</tr>
</table><br>
<div align="center">
	<input type="button" value="���Ĥ��롡" onclick='window.close()'>
</div>
</form>

</body>
</html>
