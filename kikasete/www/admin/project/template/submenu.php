<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�ޥ�����Ͽ����������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

set_global2('project', '�ץ������ȴ����åޥ�����Ͽ', '�ޥ�����Ͽ��˥塼', BACK_TOP);

define('EV', 'onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)" onMouseDown="onMouseDown(this)" onMouseUp="onMouseUp(this)"');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
<script type="text/javascript">
<!--
function onMouseOver(c) {
	c.style.color = "orange";
}
function onMouseOut(c) {
	c.style.color = "#4682B4";
	c.style.border = "3 outset";
}
function onMouseDown(c) {
	c.style.border = "3 inset";
}
function onMouseUp(c) {
	c.style.border = "3 outset";
}
//-->
</script>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

			<table border=0 cellpadding=8 cellspacing=0 width="40%">
				<tr>
					<td class="m0" colspan=2 align="left"><b>���ƥ�ץ졼����Ͽ������</b></td>
				</tr>
				<tr>
					<td align="center" onclick="location.href='notice_mail/list.php'">
						<span class="menubtn" <?=EV?>>���Υ᡼�� ��Ͽ</span>
					</td>
				</tr>
				<tr>
					<td align="center" onclick="location.href='enquete/list.php'">
						<span class="menubtn" <?=EV?>>���󥱡��ȼ���ʸ ��Ͽ</span>
					</td>
				</tr>
				<tr>
					<td align="center" onclick="location.href='search/list.php'">
						<span class="menubtn" <?=EV?>>�оݾ�� ��Ͽ</span>
					</td>
				</tr>
			</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
