<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�����᡼��ƥ�ץ졼�ȹ�������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

$rec['mt_subject'] = sql_char($_POST['subject']);
$rec['mt_from'] = sql_char($_POST['from']);
$rec['mt_cc'] = sql_char($_POST['cc']);
$rec['mt_bcc'] = sql_char($_POST['bcc']);
$rec['mt_reply_to'] = sql_char($_POST['reply_to']);
$rec['mt_body'] = sql_char($_POST['body']);
db_update('m_mail_template', $rec, "mt_template_id='{$_POST['template_id']}'");
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
			<td>
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>�������᡼��ƥ�ץ졼�ȹ�����λ</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>�����᡼��ƥ�ץ졼�Ȥ򹹿����ޤ�����</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../menu.php">�ޥ������ƥʥ󥹥�˥塼�����</a>���á�<a href="list.php">�����᡼��ƥ�ץ졼�Ȱ��������</a>
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
