<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
?>
<? staff_header('現在実施中のアンケート一覧') ?>

<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
			<form method="" action="enq.php">
			<table width="600" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><font size="3" class="honbun1"><b>■ 実施中のアンケート</b></font></td>
					<td align="right"><input type="button" value="　戻る　" onclick="location.href='enq.php'"></td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
<!-- 2003.12.18 井上　ID追加 -->
					<td width="15%" align="left" bgcolor="#eeeeee"><font size="2" class="honbun2">ＩＤ</font></td>
					<td width="50%" bgcolor="#eeeeee"><font size="2" class="honbun2">アンケートタイトル</font></td>
					<td width="15%" bgcolor="#eeeeee"><font size="2" class="honbun2">種類</font></td>
					<td width="20%" bgcolor="#eeeeee"><font size="2" class="honbun2">状態</font></td>
				</tr>
<?
$staff_id = $_SESSION['ss_staff_id'];

// 実施中のアンケート一覧取得
$sql = "SELECT me_enquete_id,me_my_enq_no,me_make_step,en_status,en_enq_type,en_title"
		. " FROM t_my_enquete JOIN t_enquete ON me_enquete_id=en_enquete_id"
		. " WHERE me_staff_id=$staff_id AND en_status=5"
		. " ORDER BY me_my_enq_no DESC";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$title = htmlspecialchars($fetch->en_title);
	$my_enq_no = $fetch->me_my_enq_no;
?>
				<tr>
<!-- 2003.12.18 井上　ID追加 -->
					<td width="15%"><font style="font-size:10px"><?=$fetch->me_enquete_id?></font></td>
					<td width="50%"><font size="2" class="honbun2"><a href="enqdet1.php?my_enq_no=<?=$my_enq_no?>"><?=$title?></a></font></td>
					<td width="15%"><font size="2" class="honbun2"><?=decode_enq_type2($fetch->en_enq_type)?></font></td>
					<td width="20%"><font size="2" class="honbun2"><?=decode_enq_status($fetch->en_status)?></font></td>
				</tr>
<?
}
?>
			</table>
			<br>
			</form>

<? staff_footer() ?>
