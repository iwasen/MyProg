<?
/******************************************************
' System :Eco-footprint �����ѥڡ���
' Content:�ޥ������ƥʥ󥹡ý����Ϣ�ޥ���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('master', '�ޥ�������', '�����Ϣ�ޥ���', BACK_TOP);
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
				<tr><th colspan=3 align="left" style="color=white;background-color=#4682B4">�������Ϣ�ޥ������ƥʥ󥹥�˥塼</th></tr>
			  <tr>
			    <td width="33%"><a href="family_ctg/list.php">��²���ƥ���ޥ���</a></td>
			    <td width="33%"><a href="family_rel/list.php">³���ޥ���</a></td>
			    <td width="33%"><a href="keitai/list.php">������֥ޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="sozai/list.php">�����Ǻ�ޥ���</a></td>
			    <td><a href="space/list.php">�������ѥޥ���</a></td>
			    <td><a href="room/list.php">�ּ��ޥ���</a></td>
			  </tr>
			  <tr>
			    <td><a href="chikunen/list.php">����ǯ���ޥ���</a></td>
			    <td><a href="boiler/list.php">����勵���ץޥ���</a></td>
			    <td><a href="gas_kind/list.php">������ޥ���</a></td>
			  <tr>
			    <td><a href="gas_type/list.php">���������ץޥ���</a></td>
			    <td></td>
			    <td></td>
			  </tr>
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
