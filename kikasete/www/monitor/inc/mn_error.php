<?
function error_msg($msg, $back = 'history.back()') {
?>
<table border="0" cellpadding="15" cellspacing="0" width="100%" bgcolor="white">
	<tr>
		<td>
			<table border="0" cellpadding="3" cellspacing="2" width="100%">
				<tr>
					<td>
						<form method="post" action="<?=$back?>">
						<font color="#2b2b63"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���</b></font>
						<hr>
						<font size="2"><br><?=disp_msg($msg, '��', '<br><br>')?><br></font>
						<center>
							<hr>
							<input type="button" value="���ᡡ�롡" onclick="<?=$back?>">
						</center>
						</form>
					</td>
				</tr>
			</table><br>
			<br>
		</td>
	</tr>
</table>
<?
}
?>