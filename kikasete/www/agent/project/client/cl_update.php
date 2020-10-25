<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:��Ⱦ��󹹿�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ����åޥ�����Ͽ', '��Ⱦ��󹹿�', BACK_TOP);

if ($back_url == '')
	$back_url = 'list.php';

switch ($next_action) {
case 'new':
	$sql = "SELECT cl_client_id FROM m_client WHERE cl_name='$cl_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = sprintf("INSERT INTO m_client (cl_name, cl_name_kana, cl_zip_code, cl_address1, cl_address2, cl_address3, cl_address4, cl_gyoushu_cd)"
			. "VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
			sql_char(trim($cl_name)),
			sql_char(trim($cl_name_kana)),
			sql_char($cl_zip1.$cl_zip2),
			sql_number($cl_address1),
			sql_char(trim($cl_address2)),
			sql_char(trim($cl_address3)),
			sql_char(trim($cl_address4)),
			sql_number($cl_gyoushu_cd));
		db_exec($sql);
		$msg = '��Ⱦ������Ͽ���ޤ�����';
		$back = "location.href='$back_url'";
	} else {
		$msg = '���Ϥ����״��̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$back = 'history.back()';
	}
	$title = "��Ⱦ��󿷵���Ͽ";
	break;
case 'update':
	$sql = "SELECT cl_client_id FROM m_client WHERE cl_name='$cl_name' AND cl_client_id<>$cl_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_client SET"
			. " cl_name=" . sql_char(trim($cl_name))
			. ",cl_name_kana=" . sql_char(trim($cl_name_kana))
			. ",cl_zip_code=" . sql_char($cl_zip1.$cl_zip2)
			. ",cl_address1=" . sql_number($cl_address1)
			. ",cl_address2=" . sql_char(trim($cl_address2))
			. ",cl_address3=" . sql_char(trim($cl_address3))
			. ",cl_address4=" . sql_char(trim($cl_address4))
			. ",cl_gyoushu_cd=" . sql_number($cl_gyoushu_cd)
			. " WHERE cl_client_id=$cl_id";
		db_exec($sql);
		$title = "�����Ͽ���󹹿�";
		$msg = '��Ⱦ���򹹿����ޤ�����';
		$back = "location.href='$back_url'";
	} else {
		$title = "�����Ͽ���󹹿�";
		$msg = '���Ϥ������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$back = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_client WHERE cl_client_id=$cl_id";
	db_exec($sql);
	$title = "�����Ͽ���󹹿�";
	$msg = '��Ⱦ���������ޤ�����';
	$back = "location.href='$back_url'";
	break;
default:
	redirect($back_url);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m4" align="center"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br><br>
			<p class="msg"><?=$msg?></p>
			<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
