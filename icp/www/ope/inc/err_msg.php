<?
/******************************************************
' System :ICPオペレータページ
' Content:エラーメッセージ表示
'******************************************************/

// エラーメッセージ表示
function disp_err($title, &$msg_ary) {
?>
<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
		<tr>
			<td>■<?=htmlspecialchars($title)?></td>
		</tr>
		</table>

		<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
<?
foreach ($msg_ary as $msg) {
?>
		<tr>
			<td>・<?=htmlspecialchars($msg)?></td>
		</tr>
<?
}
?>
		</table>

		<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
		<tr>
			<td align="center"><a href="javascript:history.back()">前ページに戻る</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?
}
?>