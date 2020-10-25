<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_mye_check.php");

$sql = "SELECT en_mail_contents FROM t_enquete WHERE en_enquete_id=$enquete_id";
$contents = db_fetch1($sql);
$marketer_id = $_SESSION['ss_marketer_id'];
$sql = "SELECT mr_mail_addr,mr_name1 FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$contents = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $contents);
	$contents = str_replace('%MONITOR_NAME%', $fetch->mr_name1, $contents);
}
?>
<? marketer_header('メールコンテンツ', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		  <td width="400" valign="top">&lt;&lt;メールコンテン&gt;&gt;<br>
            <br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="768" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="300">
						
						発信されたメールコンテンツです。
						
					</td>
					<td width="200">
						<a href="javascript:history.back()">アンケート詳細表示に戻る</a>
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
        <tr> 
          <td><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
        </tr>
      </table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<? marketer_footer() ?>
