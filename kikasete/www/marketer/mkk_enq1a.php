<?
$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('������̥ե�����Υ��������', PG_NULL) ?>

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
			<table width="768" border="0" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						
						���󥱡��ȷ�̥ե�����Υ�������ɤǤ���<br><br>
						
					</td>
				</tr>
			</table>
			<br><br>
			<center>
			<table width="500" border="0" cellspacing="0" cellpadding="10" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td align="center">
						<form method="" action="mkk_enq_csv2.php">
						<input type="hidden" name="type" value="<?=$type?>">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<input type="image" src="images/mkk_bt/dl_answer.gif" alt="������̥ե�����Υ��������">
						</form>
					</td>
				</tr>
			</table>
			</center>
			<br><br>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
