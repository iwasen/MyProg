<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ޥ������ƥʥ󥹥�˥塼ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");

$event = 'onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)" onMouseDown="onMouseDown(this)"';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onMouseOver(c) {
	c.style.color = "yellow";
}
function onMouseOut(c) {
	c.style.color = "white";
	c.style.border = "outset";
}
function onMouseDown(c) {
	c.style.border = "inset";
}
//-->
</script>
</head>
<body>

<? center_header('�ޥ������ƥʥ�') ?>

<div align="center">
<table border=0 cellspacing=4 cellpadding=3 width="100%" class="small">
	<tr class="submenu">
		<td colspan=4>���ޥ����ơ��֥�</td>
	</tr>
	<tr>
		<a href="system/list.php"><td width="25%" class="menuitem" <?=$event?>>�����ƥ��������ޥ���</td></a>
		<a href="staff/list.php"><td width="25%" class="menuitem" <?=$event?>>��̳�ɥ����åեޥ���</td></a>
		<a href="user/list.php"><td width="25%" class="menuitem" <?=$event?>>�桼������</td></a>
		<a href="cancel_result/list.php"><td width="25%" class="menuitem" <?=$event?>>�᡼����ɲ����ͳ�ޥ���</td></a>
	</tr>
	<tr>
		<a href="channel/list.php"><td width="25%" class="menuitem" <?=$event?>>�������ͥ�ޥ���</td></a>
		<a href="dealer/list.php"><td width="25%" class="menuitem" <?=$event?>>����Ź�ޥ���</td></a>
		<a href="upload/list.php"><td width="25%" class="menuitem" <?=$event?>>���åץ���ô���ޥ���</td></a>
		<td></td>
	</tr>
	<tr>
		<a href="car_category/list.php"><td width="25%" class="menuitem" <?=$event?>>�ּ參�ƥ���ޥ���</td></a>
		<a href="car_model/list.php"><td width="25%" class="menuitem" <?=$event?>>�ּ�ޥ���</td></a>
		<a href="tantousha/list.php"><td width="25%" class="menuitem" <?=$event?>>ô���ԥޥ���</td></a>
		<td></td>
	</tr>
	<tr>
		<a href="greeting/list.php"><td width="25%" class="menuitem" <?=$event?>>����ΰ���ʸ�ޥ���</td></a>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan=4><br></td>
	</tr>
	<tr class="submenu">
		<td colspan=4>���᡼��ƥ�ץ졼��</td>
	</tr>
	<tr>
		<a href="mail_template/edit.php?template_id=onetoone"><td width="25%" class="menuitem" <?=$event?>>OneToOne�᡼��ƥ�ץ졼��</td></a>
		<a href="mail_template/edit.php?template_id=inquiry"><td width="25%" class="menuitem" <?=$event?>>�䤤��碌�����ƥ�ץ졼��</td></a>
		<a href="dealermail/edit.php"><td width="25%" class="menuitem" <?=$event?>>����Ź�᡼��ƥ�ץ졼��</td></a>
		<td></td>
	</tr>
		<td colspan=4><br></td>
	</tr>
	<tr class="submenu">
		<td colspan=4>������ե����᡼�롿��³��졼�����᡼��</td>
	</tr>
	<tr>
		<a href="follow_mail/list.php"><td width="25%" class="menuitem" <?=$event?>>����ե����᡼����Ͽ</td></a>
		<a href="rel_mail/list.php"><td width="25%" class="menuitem" <?=$event?>>��³��졼�����᡼����Ͽ</td></a>
		<td></td>
		<td></td>
	</tr>
	</tr>
		<td colspan=4><br></td>
	<tr class="submenu">
		<td colspan=4>�����׽���</td>
	</tr>
	<tr>
		<a href="follow_click/list.php"><td width="25%" class="menuitem" <?=$event?>>����ե����ּ��̥���å�����</td></a>
		<a href="week_send/list.php"><td width="25%" class="menuitem" <?=$event?>>�����ۿ�������</td></a>
		<td></td>
		<td></td>
	</tr>
</table>
</div>

<? center_footer() ?>

</body>
</html>
