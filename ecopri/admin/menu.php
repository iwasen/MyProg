<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:�ȥåץ�˥塼����
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('ecopri', '', 'xxx��������Club ������˥塼', BACK_LOGOFF);

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
				<tr><th class="member" colspan=3 align="left">������������</th></tr>
				<tr>
					<td width="33%"><a href="member/personal/find.php">����Ŀ͸���</a></td>
					<td width="33%"><a href="member/search/condition.php">���������</a></td>
					<td><a href="member/input/list.php">����ǡ������Ͼ���</a></td>
				</tr>
				<tr>
					<td><a href="member/msg/menu.php">����ȥåץڡ�������</a></td>
					<td><a href="member/all_mail/list.php">���ۿ��᡼������</a></td>
					<td width="33%"><a href="member/close_mail/list.php">��λ��¥�᡼���ۿ�����</a></td>
				</tr>
				<tr>
					<td width="33%"><a href="member/login/list.php">�������������</a></td>
					<td width="33%"><a href="member/taikai_list/list.php">���ԥꥹ��</a></td>
					<td><a href=""><br></a></td>
<!--
					<td><a href=""><a href="member/ganbalist/list.php">����Хꥹ�����Ѿ���</a></a></td>
-->				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="rival" colspan=3 align="left">���饤�Х��������</a></th></tr>
				<tr>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				<tr>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="point" colspan=3 align="left">���ݥ���ȴ���</th></tr>
				<tr>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				<tr>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="master" colspan=3 align="left">���ޥ�������</th></tr>
				<tr>
					<td width="33%"><a href="master/area/menu.php">�ϰ�ޥ���</a></td>
					<td width="33%"><a href="master/life/menu.php">�����Ϣ�ޥ���</a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				<tr>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
				</tr>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="etc" colspan=3 align="left">������¾����</th></tr>
					<td width="33%"><a href="etc/message/list.php">Eco�������󤫤�Τ��Τ餻����</a></td>
					<td width="33%"><a href="etc/mail_template/list.php">�����᡼��ƥ�ץ졼��</a></td>
					<td width="33%"><a href="etc/click/list.php">CD-ROM����å�����</a></td>
				</tr>
				<tr>
					<td><a href="etc/present/list.php">�ץ쥼��ȴ���</a></td>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
