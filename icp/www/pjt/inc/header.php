<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:���̥إå����եå�
'******************************************************/

define('COPYRIGHT', 'Copyright &copy; 2004 xx xxxxx Inc. All Rights Reserved.');

// �إå�
function pjt_header($link = 'logoff') {
	global $top, $g_member_id;
?>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="header">
<tr>
<?
	if ($link == 'login') {
?>
	<td width="551" class="pjt" align="center">icp.net</td>
	<td width="49"><img src="<?=$top?>/img/title_2.gif" width="49" height="50" border="0"></td>
	<td width="250" class="name">�褦����<br>�����󤷤Ƥ�������</td>
<?
	} else {
?>
	<td width="551" class="pjt"><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>��<?=htmlspecialchars($_SESSION[SCD]['pjt_cd'])?>��</td>
	<td width="49"><img src="<?=$top?>/img/title_2.gif" width="49" height="50" border="0"></td>
	<td width="170" class="name" valign="bottom">�褦����<br><?=htmlspecialchars($_SESSION[SCD]['org_name'])?><br><?=htmlspecialchars($_SESSION[SCD]['member_name'])?>����</td>
	<td width="80" class="name" align="right">
<?
	if ($link == 'logoff') {
?>
		<a href="<?=$top?>/login/logoff.php" title="������">������</a>
<?
 } elseif ($link == 'close') {
?>
		<a href="javascript:window.close()" title="���Υ�����ɥ����Ĥ���">�Ĥ���</a>
<?
	}
?>
	</td>
<?
}
?>
</tr>
</table>
<?
}

// �եå�
function pjt_footer() {
?>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="copyright">
<tr>
	<td align="center"><?=COPYRIGHT?></td>
</tr>
</table>
<?
}
?>