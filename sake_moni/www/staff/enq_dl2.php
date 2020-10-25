<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
?>
<? staff_header('ファイルのダウンロード') ?>

<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1"></font><br><br></td>
	</tr>
</table>
<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="680"><img src="images/shim.gif" width="1" height="9" border="0"></td>
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
								<font size="3" class="honbun1">ファイルを削除しました。</font>
								<br>
								<form method="" action="enqdet1.php">
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

<? staff_footer() ?>
