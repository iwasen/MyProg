<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ȥåץ�˥塼����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('menu', '', '�������ʪ�Υ��󥱡��ȥ�˥��� ��̳�ɥ�˥塼', BACK_LOGOFF);

// ���������
set_return_page('active.php');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>
<table align="center" border=0 cellpadding=8 cellspacing=0 width="90%">
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="monitor" colspan=3 align="left">����˥�������</th></tr>
				<tr>
					<td width="33%"><a href="monitor/personal/find.php">��˥����Ŀ͸���</a></td>
					<td width="33%"><a href="monitor/mail/menu.php">������᡼���ۿ�</a></td>
					<td width="33%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="staff" colspan=3 align="left">�������åմ���</a></th></tr>
				<tr>
					<td width="33%"><a href="staff/personal/find.php">�����åոĿ͸���</a></td>
					<td width="33%"><a href="staff/login/list.php">����������</a></td>
					<td width="33%"><a href="staff/myenq/list.php">My���󥱡��ȴ���</a></td>
				</tr>
				<tr>
          <td><a href="staff/regist/new.php">�����å���Ͽ</a></td>
          <td><a href="staff/csv/list.php">CSV���������</a></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="client" colspan=3 align="left">�����饤����ȴ���</a></th></tr>
				<tr>
          <td width="33%"><a href="client/regist/list.php">���饤�������Ͽ</a></td>
					<td width="33%"><a href="client/login/list.php">����������</a></td>
					<td width="33%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="etc" colspan=3 align="left">������¾����</th></tr>
					<td width="33%"><a href="etc/point/list.php ">�ݥ���Ⱦ������</a></td>
					<td width="33%"><a href="etc/send_all/list.php">���ۿ�</a></td>
					<td width="33%"><a href="etc/report/list.php">Ĵ����̥��åץ���</a></td>
				</tr>
				<tr>
					<td><a href="etc/master/menu.php">�ޥ������ƥʥ�</a></td>
					<td><a href="etc/batch_log/list.php">�Хå���</a></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
