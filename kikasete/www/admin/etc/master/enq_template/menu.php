<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���󥱡��ȥƥ�ץ졼��
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '���󥱡��ȥ᡼��ƥ�ץ졼��', BACK_TOP);
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
				<tr><th colspan=3 align="left" style="color=white;background-color=#4682B4">�����󥱡��ȥ᡼��ƥ�ץ졼������</th></tr>
			  <tr>
			    <td width="33%"><a href="edit.php?template_id=mye_mail">�ͣ����󥱡��ȡʥ᡼���</a></td>
			    <td width="33%"><a href="edit.php?template_id=mye_web">�ͣ����󥱡��ȡ�Web��</a></td>
			    <td width="33%"><a href="edit.php?template_id=pe_web">PRO���󥱡��ȡʥե�����ǥ���̵����</a></td>
			  </tr>
			  <tr>
			    <td width="33%"><a href="edit.php?template_id=jbe_mail">�ץ������ȥ��󥱡��ȡʥ᡼���</a></td>
			    <td width="33%"><a href="edit.php?template_id=jbe_web">�ץ������ȥ��󥱡��ȡ�Web��</a></td>
			    <td width="33%"><a href="edit.php?template_id=pefd_web">PRO���󥱡��ȡʥե�����ǥ���ͭ���</a></td>
			  </tr>
			  <tr>
			    <td width="33%"><a href="edit.php?template_id=hai_mail">�Ϥ��᡼��ʥ᡼���</a></td>
			    <td width="33%"><a href="edit.php?template_id=myp_web">�ͣ��ѡ��ȥʡ���Web��</a></td>
			    <td width="33%"></td>
			  </tr>
			  <tr>
			    <td width="33%"><a href="edit_html.php?template_id=hai_html">�Ϥ��᡼���HTML��</a></td>
			    <td width="33%"></td>
			    <td width="33%"></td>
			  </tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value="����롡" onclick="location.href='../menu.php'">
</div>

<? page_footer() ?>

</body>
</html>
