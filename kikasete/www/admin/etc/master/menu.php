<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ޥ������ƥʥ󥹥�˥塼����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

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
			    <td width="33%"><a href="client/list.php">��ȥޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="shokugyou/list.php">���ȥޥ���</a></td>
			    <td><a href="gyoushu/list.php">�ȼ�ޥ���</a></td>
			    <td><a href="shokushu/list.php">����ޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="conveni/list.php">����ӥ˥ޥ���</a></td>
			    <td><a href="super/list.php">�����ѡ��ޥ���</a></td>
			    <td><a href="genre/list.php">��̣������ޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="area/list.php">�ϰ�ޥ���</a></td>
			    <td><a href="city/list.php">�Ի�̾�ޥ���</a></td>
			    <td><a href="family/list.php">��²³���ޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="mail_template/list.php">�����᡼��ƥ�ץ졼��</a></td>
			    <td><a href="blacklist/list.php">�֥�å��ꥹ�ȼ���</a></td>
			    <td><a href="reply_rate/list.php">���󥱡����ֿ�Ψ�ޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="media/list.php">���Υޥ���</a></td>
			    <td><a href="advtype/list.php">���𥿥��ץޥ���</a></td>
			    <td><a href="kinmu_gyoushu/list.php">��̳��ȼ�ޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="enq_template/menu.php">���󥱡��ȥ᡼��ƥ�ץ졼��</a></td>
			    <td><a href="web_template/menu.php">���󥱡�������ʸ�ƥ�ץ졼��</a></td>
				<td><a href="gakunen/list.php">��ǯ�ޥ���</a></td>
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
