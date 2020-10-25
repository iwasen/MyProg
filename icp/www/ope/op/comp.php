<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:���ڥ졼����Ͽ��λ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// �᡼�륢�ɥ쥹��ʣ�����å�
function check_mail_addr($mail_addr, $operator_id = 0) {
	$sql = "SELECT COUNT(*) FROM t_operator WHERE op_operator_id<>$operator_id AND op_mail_addr=" . sql_char(strtolower($mail_addr));
	return db_fetch1($sql) == 0;
}

// ���ڥ졼����Ͽ
function regist_operator() {
	global $g_operator_id;

	$rec['op_status'] = sql_number($_POST['status']);
	$rec['op_admin_auth'] = sql_number($_POST['admin_auth'], 0);
	$rec['op_mail_addr'] = sql_char(strtolower($_POST['mail_addr']));
	$rec['op_password'] = sql_char($_POST['password']);
	$rec['op_org_id'] = sql_number($_POST['org_id']);
	$rec['op_name1'] = sql_char($_POST['name1']);
	$rec['op_name2'] = sql_char($_POST['name2']);
	$rec['op_name1_kana'] = sql_char($_POST['name1_kana']);
	$rec['op_name2_kana'] = sql_char($_POST['name2_kana']);
	$rec['op_nickname'] = sql_char($_POST['nickname']);
	$rec['op_regist_operator_id'] = sql_number($g_operator_id);
	db_insert('t_operator', $rec);

	return get_current_seq('t_operator', 'op_operator_id');
}

// ���ڥ졼������
function update_operator() {
	$operator_id = $_POST['operator_id'];

	db_begin_trans();

	$rec['op_status'] = sql_number($_POST['status']);
	$rec['op_admin_auth'] = sql_number($_POST['admin_auth'], 0);
	$rec['op_mail_addr'] = sql_char(strtolower($_POST['mail_addr']));
	$rec['op_password'] = sql_char($_POST['password']);
	$rec['op_org_id'] = sql_number($_POST['org_id']);
	$rec['op_name1'] = sql_char($_POST['name1']);
	$rec['op_name2'] = sql_char($_POST['name2']);
	$rec['op_name1_kana'] = sql_char($_POST['name1_kana']);
	$rec['op_name2_kana'] = sql_char($_POST['name2_kana']);
	$rec['op_nickname'] = sql_char($_POST['nickname']);
	db_update('t_operator', $rec, sprintf("op_operator_id=%s", sql_number($operator_id)));

	// �ץ������ȥ��о���ι���
	if ($_POST['pjt_update']) {
		$rec['pm_mail_addr'] = sql_char(strtolower($_POST['mail_addr']));
		$rec['pm_password'] = sql_char($_POST['password']);
		$rec['pm_org_id'] = sql_number($_POST['org_id']);
		$rec['pm_name1'] = sql_char($_POST['name1']);
		$rec['pm_name2'] = sql_char($_POST['name2']);
		$rec['pm_name1_kana'] = sql_char($_POST['name1_kana']);
		$rec['pm_name2_kana'] = sql_char($_POST['name2_kana']);
		$rec['pm_nickname'] = sql_char($_POST['nickname']);
		db_update('t_pjt_member', $rec, sprintf("pm_pjt_id IN (SELECT pj_pjt_id FROM t_pjt WHERE pj_operator_id=%s) AND pm_operator_flag=1", sql_number($operator_id)));
	}

	db_commit_trans();

	return $operator_id;
}

switch ($_POST['proc']) {
case 'regist':
	if (check_mail_addr($_POST['mail_addr']))
		$operator_id = regist_operator();
	else
		$msg[] = '�᡼�륢�ɥ쥹��' . htmlspecialchars($_POST['mail_addr']) . '���ϴ�����Ͽ����Ƥ��ޤ���';
	$target = '�������ڥ졼����';
	$action = '��Ͽ';
	break;
case 'update':
	if (check_mail_addr($_POST['mail_addr'], $_POST['operator_id']))
		$operator_id = update_operator();
	else
		$msg[] = '�᡼�륢�ɥ쥹��' . htmlspecialchars($_POST['mail_addr']) . '���ϴ�����Ͽ����Ƥ��ޤ���';
	$target = '���ڥ졼����';
	$action = '����';
	break;
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
					<td>��<?=$target?><?=$action?>��λ</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td><?=$target?>��<?=htmlspecialchars($_POST['name1'])?>��<?=htmlspecialchars($_POST['name2'])?>�͡�<?=$operator_id?>�ˡ���<?=$action?>���ޤ�����</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center">
						<a href="../top.php">�ȥåץڡ��������</a>���á�
						<a href="list.php">���ڥ졼�����������</a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
<?
} else
	disp_err("{$target}{$action}���顼", $msg);
?>

	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>

</html>
