<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:事務局用ヘッダーフッタ
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
			<font color="#2b2b63"><b>正しく入力されていない項目があるようです。</b></font><hr><br>
			<?=disp_msg($msg, '　・', '<br><br>')?><hr>
		</td>
	</tr>
	<tr>
		<td align="center" colspan=2>
			<form method="post" action="<?=$back?>">
<?
	if ($back) {
?>
				<input type="submit" value="　戻る　">
<?
	} else {
?>
				<input type="button" value="　戻る　" onclick="history.back()">
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