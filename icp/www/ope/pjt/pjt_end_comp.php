<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ץ������Ƚ�λ��λ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���ϥѥ�᡼��
$pjt_id = $_POST['pjt_id'];

db_begin_trans();

// ����֤�λ
$rec['jb_status'] = sql_number($g_job_status['��λ']);
db_update('t_job', $rec, sprintf("jb_pjt_id=%s AND jb_status<>%s", sql_number($pjt_id), sql_number($g_job_status['��λ'])));

// �ץ������Ȥ�λ
$rec['pj_status'] = sql_number($g_pjt_status['��λ']);
db_update('t_pjt', $rec, sprintf("pj_pjt_id=%s", sql_number($pjt_id)));

db_commit_trans();
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

		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>���ץ������Ƚ�λ��λ</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>�ץ������ȡ�<?=htmlspecialchars($_POST['pjt_name'])?>����λ���ޤ�����</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../top.php">�ȥåץڡ��������</a>���á�<a href="list.php">�ץ������Ȱ��������</a>
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
