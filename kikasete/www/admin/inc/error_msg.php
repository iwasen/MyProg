<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��̳���ѥإå����եå�
'******************************************************/

function error_msg($msg, $back=false) {
?>
<table width="100%" border="0" cellspacing="2" cellpadding="0" class="small">
	<tr>
		<td rowspan="2" width="20"></td>
		<td></td>
	</tr>
	<tr>
		<td>
			<font color="#2b2b63"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���</b></font><hr><br>
			<?=disp_msg($msg, '����', '<br><br>')?><hr>
		</td>
	</tr>
	<tr>
		<td align="center" colspan=2>
			<form method="post" action="<?=$back?>">
<?
	if ($back) {
?>
				<input type="submit" value="����롡">
<?
	} else {
?>
				<input type="button" value="����롡" onclick="history.back()">
<?
	}
?>
			</form>
		</td>
	</tr>
</table>
<?
}
?>