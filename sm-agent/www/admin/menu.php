<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�ȥåץ�˥塼����
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
include("$inc/common.php");

set_global('�᡼���ۿ������ӥ��������ԥ�˥塼', BACK_NONE);

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
				<tr><th class="admin" colspan=3 align="left">���ޥ������ƥʥ�</th></tr>
				<tr>
					<td width="33%"><a href="system/list.php">�����ƥ�ޥ���</a></td>
					<td width="33%"><a href="user/list.php">�桼���ޥ���</a></td>
					<td width="33%"></td>
				</tr>
			</table>
		</td>
	</tr>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
