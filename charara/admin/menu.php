<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:�ȥåץ�˥塼����
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('charara', '', '������Factory ������˥塼', BACK_LOGOFF);

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
				<tr><th class="nigaoe_m" colspan=3 align="left">�����鳨�������</th></tr>
				<tr>
					<td width="33%"><a href="nigaoe/order_list/list.php">���鳨���եꥹ��</a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="staff_m" colspan=3 align="left">�������åվ������</a></th></tr>
				<tr>
					<td width="33%"><a href="staff/check/list.php">���鳨�����å��ꥹ��</a></td>
					<td width="33%"><a href="staff/list/list.php">�����å���Ͽ�ꥹ��</a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="user_m" colspan=3 align="left">���桼���������</th></tr>
				<tr>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="mail_m" colspan=3 align="left">���᡼���ۿ�����</th></tr>
				<tr>
					<td width="33%"><a href="mail/mail_template/list.php">�����᡼��ƥ�ץ졼��</a></td>
					<td width="33%"><a href="mail/send_mail/list.php">�ۿ��᡼������</a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="master_m" colspan=3 align="left">���ޥ�������</th></tr>
				<tr>
					<td width="33%"><a href="master/nigaoe/menu.php">���鳨��Ϣ�ޥ���</a></td>
					<td width="33%"><a href="master/staff/menu.php">�����åմ�Ϣ�ޥ���</a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="etc_m" colspan=3 align="left">������¾����</th></tr>
				<tr>
					<td width="33%"><a href="etc/server/menu.php">�������̱������</a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
