<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

if ($my_enq_no != '') {
	$staff_id = $_SESSION['ss_staff_id'];
	$sql = "SELECT me_enquete_id,me_search_id,en_status"
			. " FROM t_my_enquete JOIN t_enquete ON me_enquete_id=en_enquete_id"
			. " WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->en_status <= 1) {
			db_begin_trans();
			$sql = "DELETE FROM t_my_enquete WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
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
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<br><br>
			<center>
			<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						<font size="2" class="honbun2">
						削除を完了しました
						</font>
					</td>
				</tr>
				<tr>
					<form method="" action="myenq.php">
					<td width="50%" align="center">
						<font size="2" class="honbun2">
						<input type="submit" value="　戻る　">
						</font>
					</td>
					</form>
				</tr>
			</table>
			</center>

<? staff_footer() ?>
