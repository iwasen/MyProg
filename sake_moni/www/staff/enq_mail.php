<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/mye_check.php");

$sql = "SELECT en_mail_contents FROM t_enquete WHERE en_enquete_id=$enquete_id";
$contents = db_fetch1($sql);
$staff_id = $_SESSION['ss_staff_id'];
$sql = "SELECT st_mail_addr,st_name1 FROM t_staff WHERE st_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$contents = str_replace('%MAIL_ADDR%', $fetch->st_mail_addr, $contents);
	$contents = str_replace('%MONITOR_NAME%', $fetch->st_name1, $contents);
}
?>
<? staff_header('メールコンテンツ') ?>

<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">≪メールコンテンツ≫</font><br><br></td>
	</tr>
</table>
<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="680"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="680" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="300">
						<font size="3" class="honbun1">
						発信されたメールコンテンツです。
						</font>
					</td>
					<td width="200">
						<a href="javascript:history.back()"><font size="3" class="honbun1">アンケート詳細表示に戻る</font></a>
					</td>
				</tr>
			</table>
			<br>
			<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td><pre><?=$contents?></pre>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? staff_footer() ?>
