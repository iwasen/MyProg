<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('ファイルのダウンロード', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><br><br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="9" border="0"></td>
	</tr>
	<tr>
		<td>
			<center>
			<table width="500" border="1" cellspacing="0" cellpadding="10" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff">
							<tr>
								<td width="100%" align="center"><br>
								ファイルを削除しました。
								<br>
								<form method="" action="mkk_enqdet1.php">
								<input type="hidden" name="enq_cd" value="enq_cd">
								<input type="submit" name="type" value="アンケート詳細表示に戻る">
								</form>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			<br><br>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
