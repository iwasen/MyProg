<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

$sql = "SELECT en_enquete_id,en_status"
		. " FROM t_my_enquete"
		. " JOIN t_enquete ON en_enquete_id=me_enquete_id"
		. " WHERE me_staff_id={$_SESSION['ss_staff_id']} AND me_my_enq_no=$my_enq_no";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	switch ($fetch->en_status) {
	case 0:
		$status = 1;
		$msg = 'アンケートのステータスを作成完了状態に設定しました。';
		break;
	case 1:
		$status = 0;
		$msg = 'アンケートのステータスを作成中に戻しました。';
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
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><font size="2" class="honbun2"><?=$msg?></font></td>
				</tr>
			</table>
			<br>
			<input type="button" value="　戻る　" onclick="location.href='myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			</form>
			</div>
		</td>
	</tr>
</table>

<? staff_footer() ?>
