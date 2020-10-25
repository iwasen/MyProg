<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content�����ƥ���󹹿�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// �쥳����¸�ߥ����å�
function rec_check() {
	$sql = sprintf("SELECT count(*) FROM m_system WHERE sy_key=%s",
			sql_char($_POST['key']));
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �쥳������������
function rec_insert() {
	$rec['sy_key'] = sql_char($_POST['key']);
	$rec['sy_value'] = sql_char($_POST['value']);
	$rec['sy_caption'] = sql_char($_POST['caption']);
	db_insert('m_system', $rec);
}

// �쥳���ɹ�������
function rec_update() {
	$rec['sy_value'] = sql_char($_POST['value']);
	$rec['sy_caption'] = sql_char($_POST['caption']);
	db_update('m_system', $rec, 'sy_key=' . sql_char($_POST['key']));
}

// �ᥤ�����
$ok = true;
switch ($_POST['next_action']) {
case 'new':
	if (rec_check()) {
		$msg = '���Ϥ��줿���ܥ����ϴ�����Ͽ����Ƥ��ޤ���';
		$ok = false;
	} else {
		rec_insert();
		$msg = '�����ƥ�����������Ͽ���ޤ�����';
	}
	break;
case 'update':
	rec_update();
	$msg = '�����ƥ��������򹹿����ޤ�����';
	break;
}

// �����
if ($ok)
	$ret = "location.href='list.php'";
else
	$ret = "history.back()";
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>icp.net ���ڥ졼�����ڡ���</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>
<body>

<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- ������ƥ�� -->
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td align="center">
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>�������ƥ�ޥ���������λ</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>�����ƥ�ޥ����򹹿����ޤ�����</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../menu.php">�ޥ������ƥʥ󥹥�˥塼�����</a>���á�<a href="list.php">�����ƥ����������������</a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
