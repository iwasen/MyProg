<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ץ���������Ͽ��λ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// �������ե�����򥷥�ܥ�å���󥯤ޤ��ϥ��ԡ�
function make_src_file($method, $pjt_cd) {
	global $top;

	$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . "/$top/..");

	switch ($method) {
	case 'link':
		system("ln -s pjt $path/$pjt_cd");
		break;
	case 'copy':
		system("cp -R $path/pjt $path/$pjt_cd");
		break;
	}
}

// �ץ������ȥ����ɽ�ʣ�����å�
$sql = "SELECT COUNT(*) FROM t_pjt WHERE pj_pjt_cd=" . sql_char($_POST['pjt_cd']);
if (db_fetch1($sql) > 0)
	$msg[] = '�ץ������ȥ����ɡ�' . htmlspecialchars($_POST['pjt_cd']) . '���ϴ�����Ͽ����Ƥ��ޤ���';
else {
	// �������ե���������
	make_src_file($_POST['src_file'], $_POST['pjt_cd']);

	db_begin_trans();

	// �ץ���������Ͽ
	$rec['pj_status'] = sql_number($g_pjt_status['��ư��']);
	$rec['pj_pjt_cd'] = sql_char($_POST['pjt_cd']);
	$rec['pj_name'] = sql_char($_POST['pj_name']);
	$rec['pj_description'] = sql_char($_POST['description']);
	$rec['pj_operator_id'] = sql_number($g_operator_id);
	db_insert('t_pjt', $rec);
	$pjt_id = get_current_seq('t_pjt', 'pj_pjt_id');

	// �ץ������ȥ�����Ͽ
	$rec['pm_status'] = sql_number(1);
	$rec['pm_pjt_id'] = sql_number($pjt_id);
	$rec['pm_operator_flag'] = sql_number(1);
	$rec['pm_staff_auth'] = sql_char(operator_staff_auth());
	$rec['pm_mail_addr'] = sql_char($_POST['mail_addr']);
	$rec['pm_password'] = sql_char($_POST['password']);
	$rec['pm_org_id'] = sql_number($_POST['org_id']);
	$rec['pm_nickname'] = sql_char($_POST['nickname']);
	$rec['pm_name1'] = sql_char($_POST['op_name1']);
	$rec['pm_name2'] = sql_char($_POST['op_name2']);
	$rec['pm_name1_kana'] = sql_char($_POST['op_name1_kana']);
	$rec['pm_name2_kana'] = sql_char($_POST['op_name2_kana']);
	db_insert('t_pjt_member', $rec);

	db_commit_trans();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
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
					<td>�������ץ���������Ͽ��λ</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>�����ץ������ȡ�<?=htmlspecialchars($_POST['pj_name'])?>������Ͽ���ޤ�����</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../top.php">�ȥåץڡ��������</a>���á�<a href="list.php">�ץ������Ȱ��������</a>
					</td>
				</tr>
				</table>
<?
} else
	disp_err('�����ץ���������Ͽ���顼', $msg);
?>
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
