<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ޡ����󥰥ޥ�����������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// ������Ͽ
function regist_marking() {
	$rec['mk_marking_id'] = sql_number($_POST['marking_id']);
	$rec['mk_name'] = sql_char($_POST['name']);
	$rec['mk_color'] = sql_char($_POST['color']);
	db_insert('m_marking', $rec);
}

// ����
function update_marking() {
	$rec['mk_name'] = sql_char($_POST['name']);
	$rec['mk_color'] = sql_char($_POST['color']);
	db_update('m_marking', $rec, "mk_marking_id='{$_POST['marking_id']}'");
}

// ���
function delete_marking() {
	db_delete('m_marking', "mk_marking_id='{$_POST['marking_id']}'");
}

// ��������ʬ��
switch ($_POST['proc']) {
case 'regist':
	if (!is_numeric($_POST['marking_id']))
		$msg[] = 'ID��Ⱦ�ѿ��������Ϥ��Ƥ���������';
	else {
		$sql = "SELECT COUNT(*) FROM m_marking WHERE mk_marking_id=" . sql_number($_POST['marking_id']);
		if (db_fetch1($sql) != 0)
			$msg[] = '���Ϥ���ID�ϴ�����Ͽ����Ƥ��ޤ���';
		else
			regist_marking();
	}
	$action = '������Ͽ';
	break;
case 'update':
	update_marking();
	$action = '����';
	break;
case 'delete':
	delete_marking();
	$action = '���';
	break;
}
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
<?
if (!$msg) {
?>
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>���ޡ����󥰥ޥ���<?=$action?>��λ</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>�ޡ����󥰥ޥ�����<?=$action?>���ޤ�����</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../menu.php">�ޥ������ƥʥ󥹥�˥塼�����</a>���á�<a href="list.php">�ޡ����󥰥ޥ������������</a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
<?
} else
	disp_err("�ޡ����󥰥ޥ���{$action}���顼", $msg);
?>
	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
