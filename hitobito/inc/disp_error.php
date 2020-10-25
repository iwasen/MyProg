<?php
/**
 * ひとびと･net 共通ファイル
 *
 * エラーメッセージ表示処理
 *
 *
 * @package
 * @author
 * @version
 */

function disp_error($header, $msg) {
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>

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
			<form>
				<input type="button" value="　戻る　" onclick="history.back()">
			</form>
		</td>
	</tr>
</table>

<?php $header->page_footer(); ?>

</body>
</html>
<?php
	exit;
}
?>