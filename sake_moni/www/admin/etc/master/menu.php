<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ޥ������ƥʥ󥹥�˥塼����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', '����¾����', '�ޥ������ƥʥ�', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>

<div align="center">
<table border=0 cellpadding=8 cellspacing=0 width="100%">
	<tr>
		<td>
			<table width="90%" align="center" <?=LIST_TABLE?>>
				<tr><th colspan=3 align="left" style="color=white;background-color=#4682B4">���ޥ������ƥʥ󥹥�˥塼</th></tr>
			  <tr>
			    <td width="33%"><a href="system/list.php">�����ƥ�ޥ���</a></td>
			    <td width="33%"><a href="admin/list.php">�����ԥޥ���</a></td>
			    <td width="33%"><a href="client/list.php">���饤����ȥޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="shokugyou/list.php">���ȥޥ���</a></td>
			    <td><a href="area/list.php">�ϰ�ޥ���</a></td>
			    <td><a href="chain/list.php">��������ޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="mail_template/list.php">�����᡼��ƥ�ץ졼��</a></td>
			    <td><a href="enq_template/edit.php?template_id=mye_web">���󥱡��ȥ᡼��ƥ�ץ졼��</a></td>
			    <td><a href="web_template/edit.php?template_id=mye">���󥱡�������ʸ�ƥ�ץ졼��</a></td>
			  </tr>
			  <tr>
			    <td><a href="gyoutai/list.php">���֥ޥ���</a></td>
			    <td><a href="rem_template/edit.php?template_id=mye_rem">��¥�᡼��ƥ�ץ졼��</a></td>
			    <td><br></td>
			  </tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
</div>

<? page_footer() ?>

</body>
</html>
