<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥��������åեڡ���
' Content:���顼ɽ������
'******************************************************/

function error_msg($msg, $back=false, $hidden=false) {
?>
<table width="700" border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td width="700"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<form method="post" action="<?=$back?>">
				<font color="#2b2b63"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���</b></font><hr><br>
				<font class="honbun1"><?=disp_msg($msg, '����', '<br><br>')?></font><hr>
				<div align="center">
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
				</div>
				<? if ($hidden) disp_msg($hidden) ?>
			</form>
		</td>
	</tr>
</table>
<?
}
?>