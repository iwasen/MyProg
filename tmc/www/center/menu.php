<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�����ƥ��˥塼
'******************************************************/

include("../inc/center.php");

define('EV', 'onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)" onMouseDown="onMouseDown(this)" onMouseUp="onMouseUp(this)"');

session_start();
$privilege = $_SESSION['s_privilege'];
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>Inbound Navigator plus ��˥塼</title>
<script type="text/javascript">
<!--
function onMouseOver(c) {
	c.style.color = "yellow";
}
function onMouseOut(c) {
	c.style.color = "white";
	c.style.border = "2 outset";
}
function onMouseDown(c) {
	c.style.border = "2 inset";
}
function onMouseUp(c) {
	c.style.border = "2 outset";
}
//-->
</script>
<style type="text/css">
<!--
body
{
	margin-left: 4px;
	margin-right: 0px;
	white-space: nowrap;
	background-color: #FEF0DA
}
.title
{
	color: white;
	background-color: <?=staff_color()?>;
	font-weight: bold;
	white-space: nowrap;
	padding: 2;
	text-align: center;
}
td.menu
{
	border: 2 outset;
	font-size: 85%;
	font-weight: bold;
	padding: 3;
	cursor: hand;
	color: white;
	text-align: center;
	white-space: nowrap;
	background-color: steelblue
}
-->
</style>
</head>
<body>
<table cellspacing=0 cellpadding=1 border=0 width="138">
	<tr>
		<td align="center" bgcolor="#c80000"><font color="white"><b>TOYOTA</b></font></td>
	</tr>
</table>
<hr size=3 noshade width="110%" color="#008080">
<div style="font-weight:bold;color=navy">&nbsp;�����������<br>&nbsp;�������������ӥ�<br></div>
<hr size=3 noshade width="110%" color="#008080">
<table cellspacing=4 cellpadding=0>
<? if ($privilege == PRIV_OPERATOR) {?>
	<tr><td class="title">Operator Portal</td></tr>
	<tr><td height=6></td></tr>
	<tr><a href="newdata/top.php" target="main"><td class="menu" <?=EV?>>�ǿ�����ɽ��</td></a></tr>
	<tr><td>
		<table cellspacing=0 cellpadding=0 width="100%">
			<tr><a href="javascript:document.user.submit()"><td class="menu" <?=EV?>>�桼������ɽ��</td></a></tr>
			<tr><td align="center">
				<form name="user" action="user/input_id.php" target="_blank" style="margin:0">
				<input class="alpha" type="text" name="user_id">
				</form>
			</td></tr>
		</table>
	</td></tr>
	<tr><a href="onetoone/frame1.php?list=1" target="main"><td class="menu" <?=EV?>>OneToOne�᡼��</td></a></tr>
	<tr><a href="inquiry/list1.php" target="main"><td class="menu" <?=EV?>>�䤤��碌�б�</td></a></tr>
	<tr><a href="staff/change.php" target="main"><td class="menu" <?=EV?>>���ڥ졼�������ѹ�</td></a></tr>
<? } elseif ($privilege == PRIV_DHSTAFF) {?>
	<tr><td class="title">DH Staff Portal</td></tr>
	<tr><td height=6></td></tr>
	<tr><a href="newdata/top.php" target="main"><td class="menu" <?=EV?>>�ǿ�����ɽ��</td></a></tr>
	<tr><td>
		<table cellspacing=0 cellpadding=0 width="100%">
			<tr><a href="javascript:document.user.submit()"><td class="menu" <?=EV?>>�桼������ɽ��</td></a></tr>
			<tr><td align="center">
				<form name="user" action="user/input_id.php" target="_blank" style="margin:0">
				<input class="alpha" type="text" name="user_id">
				</form>
			</td></tr>
		</table>
	</td></tr>
	<tr><a href="onetoone/frame1.php?list=1" target="main"><td class="menu" <?=EV?>>OneToOne�᡼��</td></a></tr>
	<tr><a href="inquiry/list1.php" target="main"><td class="menu" <?=EV?>>�䤤��碌�б�</td></a></tr>
	<tr><a href="dealermail/list.php" target="main"><td class="menu" <?=EV?>>����Ź�᡼���ۿ�</td></a></tr>
	<tr><a href="export/list1.php" target="main"><td class="menu" <?=EV?>>�������ݡ���</td></a></tr>
	<tr><a href="message/list.php" target="main"><td class="menu" <?=EV?>>Ϣ�����񤭹���</td></a></tr>
	<tr><a href="staff/change.php" target="main"><td class="menu" <?=EV?>>�����åվ����ѹ�</td></a></tr>
	<tr><a href="master/menu.php" target="main"><td class="menu" <?=EV?>>�ޥ������ƥʥ�</td></a></tr>
	<tr><a href="sm-agent.php" target="main"><td class="menu" <?=EV?>>Sendmail Agent</td></a></tr>
<? } ?>
	<tr><td height=6></td></tr>
	<tr><td><input type="button" value="������" onclick="top.location.href='login/logoff.php'"></td></tr>
</table>
</body>
</html>
