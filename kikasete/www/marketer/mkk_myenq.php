<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション変数削除
unset($_SESSION['ss_my_enq']);
session_unregister('ss_my_enq');
?>
<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_NULL) ?>


<script language=javascript>
<!--
function gotoPage(url, my_enq_no) {
	var f = document.form1;
	f.action = url;
	f.my_enq_no.value = my_enq_no;
	f.submit();
}
//-->
</script>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"></td>
<td width="12" rowspan="2"><img src="images/common/spacer.gif" width="18" height="10"></td>
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_MYENQ); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/mkk_myenq/title01.gif" alt="MY MENU" width="553" height="25"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mkk_myenq/title02.gif" alt="Myアンケート" width="114" height="28"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td align=right>
<?
if (check_permission(PMR_MY_ENQUETE)) {
?>
<a href="mkk_myenq_make.php?my_enq_no=" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image63','','images/mkk_myenq/bt_04_ov.gif',1)"><img src="images/mkk_myenq/bt_04.gif" alt="Myアンケート新規作成" name="Image63" width="201" height="40" border="0"><br />
</a><a href="pdf/myenq_manual_5_3.pdf" target="_blank" onmouseover="MM_swapImage('Image54','','images/mkk_myenq/bt_01_ov.gif',1)" onmouseout="MM_swapImgRestore()"></a></td>
<?
}
?>
<br />
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="100%"  border="0" cellpadding="5" cellspacing="1" >
<form name="form1" method="post">
<input type="hidden" name="my_enq_no">
<tr class="description"> 
<td width="5%" height="14" bgcolor="#e5eaf0">ID</td>
<td width="40%" height="14" bgcolor="#e5eaf0">アンケートタイトル</td>
<td colspan="2" bgcolor="#e5eaf0">&nbsp;</td>
</tr>

<?
$marketer_id = $_SESSION['ss_marketer_id'];
$owner_id = $_SESSION['ss_owner_id'];

// 引き継がれたマーケターが自分が作成したMyアンケートのみを見れるようにする処置
$where = "WHERE me_marketer_id=$owner_id AND en_status<>9";
if ($_SESSION['ss_marketer_type'] == MK_PAY && $marketer_id != $owner_id)
	$where .= " AND me_creator_id=$marketer_id";

// Myアンケート一覧取得
$sql = "SELECT me_enquete_id,me_my_enq_no,me_make_step,en_status,en_enq_type,en_title"
		. " FROM t_my_enquete LEFT JOIN t_enquete ON me_enquete_id=en_enquete_id"
		. " $where"
		. " ORDER BY me_my_enq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$title = ($fetch->en_title == '') ? 'タイトル未設定' : htmlspecialchars($fetch->en_title);
	$my_enq_no = $fetch->me_my_enq_no;
?>

<tr class="description">
<!-- 2003.12.18 井上　ID追加 -->
<td height="15" bgcolor="#FFFFFF"><?=$fetch->me_enquete_id?></td>
<td height="15" bgcolor="#FFFFFF">
<a href="mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>&show=1"><?=$title?></a><br></td>
<td width="15%" height="15" bgcolor="#FFFFFF" align="center">
<?=decode_enq_status($fetch->en_status)?></td>
<td width="25%" bgcolor="#FFFFFF" align="center">
<nobr>
<?
	$btn = false;

	if (($fetch->en_status <= 1) && (check_permission(PMR_MY_ENQUETE))) {
		$btn = true;
?>
<input type="button" value="作成続行" onClick="gotoPage('mkk_myenq_make.php', <?=$my_enq_no?>)">
<?
	}
	if (($fetch->en_status == 1) && (check_permission(PMR_MY_ENQUETE))) {
		$btn = true;
?>
<input type="button" value="申請" onClick="gotoPage('mkk_myenq_app1.php', <?=$my_enq_no?>)">
<?
	}
	if (($fetch->en_status == 8) && (check_permission(PMR_MY_ENQUETE))) {
		$btn = true;
?>
<input type="button" value="修正" onClick="gotoPage('mkk_myenq_make.php', <?=$my_enq_no?>)">
<input type="button" value="再申請" onClick="gotoPage('mkk_myenq_app1.php', <?=$my_enq_no?>)">
<?
	}
	if (($fetch->en_status <= 4) && (check_permission(PMR_MY_ENQUETE))) {
		$btn = true;
?>
<input type="button" value="削除" onClick="gotoPage('mkk_myenq_erase.php', <?=$my_enq_no?>)">
<?
	}
	if (($fetch->en_status == 5 || $fetch->en_status == 6 || $fetch->en_status == 7) && (check_permission(PMR_ENQ_RESULT))) {
		$btn = true;
?>
<a href="mkk_enqdet1.php?my_enq_no=<?=$my_enq_no?>">結果</a>
<?
	}
	if (!$btn) {
?>
								−
<?
	}
?>
</nobr></td>
</tr>
<?
}
?>
</form>
</table></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->


<? marketer_footer (BLUE) ?>
