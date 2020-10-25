<?
/******************************************************
' System :ICP会議室ページ
' Content:エラーメッセージ表示
'******************************************************/

// エラーメッセージ表示
function disp_err($title, &$msg_ary) {
?>
<table border="0" cellspacing="1" cellpadding="0" bgcolor="#e24b36" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#f7c8c1" align="center" width="100%">
		<tr>
			<td class="text14b">■<?=htmlspecialchars($title)?></td>
		</tr>
		<tr>
			<td class="text12">
				<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="100%" class="text12">
				<tr>
					<td>&nbsp;</td>
				</tr>
<?
foreach ($msg_ary as $msg) {
?>
				<tr>
					<td>・<?=htmlspecialchars($msg)?></td>
				</tr>
<?
}
?>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="center"><input type="button" value="　戻る　" class="button" onclick="history.back()"></td>
				</tr>
				</table></td>
		</tr>
		</table></td>
</tr>
</table>
<?
}
?>