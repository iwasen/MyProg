<?
$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
?>
<? staff_header('回答結果ファイルのダウンロード') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1"></font><br><br></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/shim.gif" width="1" height="9" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="700" border="0" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						<font size="3" class="honbun1">
						アンケート結果ファイルのダウンロードです。<br><br>
						</font>
					</td>
				</tr>
			</table>
			<br><br>
			<center>
			<table width="500" border="0" cellspacing="0" cellpadding="10" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td align="center">
						<form method="" action="enq_csv3.php">
						<input type="hidden" name="type" value="<?=$type?>">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<input type="submit" value="回答結果ファイルのダウンロード">
						</form>
					</td>
				</tr>
			</table>
			</center>
			<br><br>
		</td>
	</tr>
</table>

<? staff_footer() ?>
