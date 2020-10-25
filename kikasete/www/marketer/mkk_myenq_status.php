<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$sql = "SELECT en_enquete_id,en_status"
		. " FROM t_my_enquete"
		. " JOIN t_enquete ON en_enquete_id=me_enquete_id"
		. " WHERE me_marketer_id={$_SESSION['ss_owner_id']} AND me_my_enq_no=$my_enq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	switch ($fetch->en_status) {
	case 0:
		$status = 1;
		$msg = 'Myアンケートのステータスを作成完了状態に設定しました。';
		break;
	case 1:
		$status = 0;
		$msg = 'Myアンケートのステータスを作成中に戻しました。';
		break;
	}

	$rec['en_status'] = sql_number($status);
	db_update('t_enquete', $rec, "en_enquete_id=$fetch->en_enquete_id");

	// 本来は必要ないがPHPのバージョンによってセッションが残っていると不都合なのでステータスを直しておく
	if (isset($_SESSION['ss_my_enq'])) {
		$myenq = &$_SESSION['ss_my_enq'];
		$myenq->status = $status;
	}
}
?>
<? marketer_header('Myアンケート', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><?=$msg?></td>
				</tr>
			</table>
			<br>
			<a href="mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>

			</form>
			</div>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
