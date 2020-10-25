<?
/******************************************************
' System :お酒と買物のアンケートモニタースタッフページ
' Content:エラー表示処理
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
				<font color="#2b2b63"><b>正しく入力されていない項目があるようです。</b></font><hr><br>
				<font class="honbun1"><?=disp_msg($msg, '　・', '<br><br>')?></font><hr>
				<div align="center">
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
				</div>
				<? if ($hidden) disp_msg($hidden) ?>
			</form>
		</td>
	</tr>
</table>
<?
}
?>