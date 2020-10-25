<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<br>
			<table width="650" border="0" cellspacing="0" cellpadding="" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						<span style="font-weight:bold;">■複製元のアンケートを選択してください。</span>
					</td>
				</tr>
			</table>
			<form>
			<table width="550" border="1" cellspacing="0" cellpadding="3" bordercolor="#cococo" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td bgcolor="#eeeeee">名称</td>
					<td bgcolor="#eeeeee">状態</td>
					<td bgcolor="#eeeeee">複製</td>
				</tr>
<?
$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT mep_pro_enq_no,en_status,en_enq_type,en_title"
		. " FROM t_pro_enquete LEFT JOIN t_enquete ON mep_enquete_id=en_enquete_id"
		. " WHERE mep_marketer_id=$owner_id AND en_status<>9"
		. " ORDER BY mep_pro_enq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$title = ($fetch->en_title == '') ? 'タイトル未設定' : htmlspecialchars($fetch->en_title);
	$pro_enq_no = $fetch->mep_pro_enq_no;
?>
				<tr>
					<td width="60%">
					
					<a href="pe_myenq1.php?pro_enq_no=<?=$pro_enq_no?>"><?=$title?></a><br>
					
					<?=decode_enq_type($fetch->en_enq_type)?>
					</td>
					<td width="20%" align="center">
					<?=decode_enq_status($fetch->en_status)?>
					</td>
					<td width="20%" align="center" valign="middle">
					<nobr>
					<input type="button" value="　選択　" onclick="location.href='pe_copy2.php?pro_enq_no=<?=$pro_enq_no?>'">
					</nobr>
					</td>
				</tr>
<?
}
?>
			</table>
			<br>
			<table border="0" cellspacing="0" cellpadding="3" width="550">
				<tr>
					<td align="center" colspan=3>
						<input type="button" value="　戻　る　" onclick="history.back()">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
