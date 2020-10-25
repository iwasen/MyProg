<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

switch ($type) {
case 'send':
	$msg = '送信';
	break;
case 'recv':
	$msg = '受信';
	break;
default:
	redirect('enq.php');
}
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
		<td width="400" valign="top"><font size="3" class="honbun1">≪モニタープロファイルのダウンロード≫</font><br><br></td>
	</tr>
</table>
<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="680"><img src="images/shim.gif" width="1" height="9" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="680" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<font size="3" class="honbun1">
						アンケートを<?=$msg?>したモニターのプロファイルのダウンロードです。<br><br>
						</font>
					</td>
				</tr>
			</table>
			<br><br>
			<center>
			<table width="500" border="0" cellspacing="0" cellpadding="10" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td align="center">
						<form method="post" action="enq_csv1.php">
						<input type="hidden" name="type" value="<?=$type?>">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<input type="submit" value="ファイルのダウンロード">
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
