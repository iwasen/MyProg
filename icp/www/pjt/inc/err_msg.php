<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:���顼��å�����ɽ��
'******************************************************/

// �������ɽ��
function disp_result(&$msg_ary) {
	echo $msg_ary ? '���顼' : '��λ';
}

// ���顼��å�����ɽ��
function disp_err($title, &$msg_ary) {
?>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="760" class="contents">
		<tr>
			<td>��<?=htmlspecialchars($title)?></td>
		</tr>
		</table>

		<table border="0" cellspacing="2" cellpadding="3" width="760" class="contents">
<?
foreach ($msg_ary as $msg) {
?>
		<tr>
			<td>��<?=htmlspecialchars($msg)?></td>
		</tr>
<?
}
?>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><a href="javascript:history.back()" title="���ڡ��������">���ڡ��������</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<?
}
?>