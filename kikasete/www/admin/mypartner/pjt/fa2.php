<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ�������������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ�������������', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--

//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������ˤĤ��Ƥβ�ļ���R��ǧ�Ѥߡˡ�999��</td>
	</tr>
</table>

</form>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>�����׷��</td>
	</tr>
	<tr>
		<td class="n1">
			<table border="1" cellspacing="0" cellpadding="0" bgcolor="#ffffff" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr>
					<td align="center" colspan=4>
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2" class="honbun2">�ѣ�(����ʸ)������</font>
								</td>
								<td width="550">
								<font size="2" class="honbun2">���Τ��ʤ��Υޥ��֡���Ϥʤ�Ǥ�����<br />
�ɤ�ʻ��Ǥ�빽�Ǥ��ΤǤ���ͳ�ˤ���������������<br />
ʣ���������ϲվ�񤭤Ǥ��񤭤���������</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th width="6%"><font size="2" class="honbun2"><nobr>��������Ϣ��</nobr></font></th>
					<th width="6%"><font size="2" class="honbun2"><nobr>����</nobr></font></th>
					<th><font size="2" class="honbun2"><nobr>����</nobr></font></th>
				</tr>
			<tr>
				<td align="center"><font size="2" class="honbun2">1</font></td>
				<td align="center"><font size="2" class="honbun2">����</font></td>
				<td><font size="2" class="honbun2">����ޡ�����ƥꥢ����<br />
 <br />
 <br />
 ������������������������������������������������������������������</font></td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<br>
</form>
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
