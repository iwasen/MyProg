<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ����ꥯ�롼�ȥ���֥��ơ������ѹ�
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ��ꥯ�롼�ȥ���֥��ơ������ѹ�', BACK_TOP);

$myp = new my_partner_class;
$myp->read_db($pjt_id);

switch ($myp->status) {
case MPS_REC_JUNBI:
	$myp->status = MPS_REC_KANRYOU;
	$msg = '�ꥯ�롼�ȥ���֤Υ��ơ������������λ���֤����ꤷ�ޤ�����';
	break;
case MPS_REC_KANRYOU:
	$myp->status = MPS_REC_JUNBI;
	$msg = '�ꥯ�롼�ȥ���֤Υ��ơ��������������ᤷ�ޤ�����';
	break;
}
$myp->write_db();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg"><?=$msg?></p>
			<p><input type="button" value="����롡" onclick="location.href='r_job.php?pjt_id=<?=$pjt_id?>'"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
