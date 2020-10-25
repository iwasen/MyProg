<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
?>
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<table width="650" border="0" cellspacing="0" cellpadding="" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						<font size="3" class="honbun1">■複製元のアンケートを選択してください。</font>
					</td>
				</tr>
			</table>
			<form>
			<table width="650" border="1" cellspacing="0" cellpadding="3" bordercolor="#cococo" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td bgcolor="#eeeeee"><font size="2" class="honbun2">名称</font></td>
					<td bgcolor="#eeeeee"><font size="2" class="honbun2">状態</font></td>
					<td bgcolor="#eeeeee"><font size="2" class="honbun2">複製</font></td>
				</tr>
<?
$staff_id = $_SESSION['ss_staff_id'];
$sql = "SELECT me_my_enq_no,me_make_step,en_status,en_enq_type,en_title"
		. " FROM t_my_enquete LEFT JOIN t_enquete ON me_enquete_id=en_enquete_id"
		. " WHERE me_staff_id=$staff_id AND en_status<>9"
		. " ORDER BY me_my_enq_no DESC";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$title = ($fetch->en_title == '') ? 'タイトル未設定' : htmlspecialchars($fetch->en_title);
	$my_enq_no = $fetch->me_my_enq_no;
?>
				<tr>
					<td width="60%">
					<font size="2" class="honbun2">
					<a href="myenq1.php?my_enq_no=<?=$my_enq_no?>"><?=$title?></a>
					</font>
					</td>
					<td width="20%" align="center">
					<font size="2" class="honbun2"><?=decode_enq_status($fetch->en_status)?></font>
					</td>
					<td width="20%" align="center" valign="middle">
					<nobr>
					<input type="button" value="　選択　" onclick="location.href='myenq_copy2.php?my_enq_no=<?=$my_enq_no?>'">
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

<? staff_footer() ?>
