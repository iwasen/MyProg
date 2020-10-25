<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

if ($pro_enq_no != '') {
	$owner_id = $_SESSION['ss_owner_id'];
	$sql = "SELECT mep_enquete_id,mep_enquete2_id,mep_search_id,en_status"
			. " FROM t_pro_enquete JOIN t_enquete ON mep_enquete_id=en_enquete_id"
			. " WHERE mep_marketer_id=$owner_id AND mep_pro_enq_no=$pro_enq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->en_status <= 1) {
			db_begin_trans();
			$sql = "DELETE FROM t_pro_enquete WHERE mep_marketer_id=$owner_id AND mep_pro_enq_no=$pro_enq_no";
			db_exec($sql);
			if ($fetch->mep_enquete_id) {
				delete_enquete($fetch->mep_enquete_id);

				$sql = "DELETE FROM t_enq_res_cond WHERE erc_enquete_id=$fetch->mep_enquete_id";
				db_exec($sql);
			}
			if ($fetch->mep_enquete2_id) {
				delete_enquete($fetch->mep_enquete2_id);

				$sql = "DELETE FROM t_enq_res_cond WHERE erc_enquete_id=$fetch->mep_enquete2_id";
				db_exec($sql);
			}
			if ($fetch->search_id) {
				$sql = "DELETE FROM t_search WHERE sr_search_id=$fetch->mep_search_id";
				db_exec($sql);
			}
			db_commit_trans();
		} else {
			$sql = "UPDATE t_enquete SET en_status=9 WHERE en_enquete_id=$fetch->mep_enquete_id";
			db_exec($sql);
		}
	}
}
?>
<? marketer_header('£Ð£ò£ï¥¢¥ó¥±¡¼¥È', PG_NULL) ?>
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
		<center>
			<table width="500" border="0" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						
						ºï½ü¤ò´°Î»¤·¤Þ¤·¤¿
						
					</td>
				</tr>
				<tr>
					<form method="" action="pe_index.php">
					<td width="50%" align="center">
						
						<input type="image" src="images/mkk_bt/back.gif" alt="Ìá¤ë" width="108" height="31">

					</td>
					</form>
				</tr>
			</table>
						<br><br>
			</center>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
