<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

if ($my_enq_no != '') {
	$owner_id = $_SESSION['ss_owner_id'];
	$sql = "SELECT me_enquete_id,me_search_id,en_status"
			. " FROM t_my_enquete JOIN t_enquete ON me_enquete_id=en_enquete_id"
			. " WHERE me_marketer_id=$owner_id AND me_my_enq_no=$my_enq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->en_status <= 1) {
			db_begin_trans();
			$sql = "DELETE FROM t_my_enquete WHERE me_marketer_id=$owner_id AND me_my_enq_no=$my_enq_no";
			db_exec($sql);
			if ($fetch->me_enquete_id) {
				$sql = "DELETE FROM t_enquete WHERE en_enquete_id=$fetch->me_enquete_id";
				db_exec($sql);
				$sql = "DELETE FROM t_enq_question WHERE eq_enquete_id=$fetch->me_enquete_id";
				db_exec($sql);
				$sql = "DELETE FROM t_enq_select WHERE es_enquete_id=$fetch->me_enquete_id";
				db_exec($sql);
			}
			if ($fetch->search_id) {
				$sql = "DELETE FROM t_search WHERE sr_search_id=$fetch->me_search_id";
				db_exec($sql);
			}
			db_commit_trans();
		} else {
			$sql = "UPDATE t_enquete SET en_status=9 WHERE en_enquete_id=$fetch->me_enquete_id";
			db_exec($sql);
		}
	}
}
?>
<? marketer_header('£Í£ù¥¢¥ó¥±¡¼¥È', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
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
					<form method="" action="mkk_myenq.php">
					<td width="50%" align="center">
						
						<input name="image2" type="image" src="images/mkk_achange/bt01.gif" alt="Ìá¤ë" name="image2" width="108" height="31" border="0">
						
					</td>
					</form>
				</tr>
			</table>
			</center>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
