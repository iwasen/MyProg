<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/format.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

?>
<? marketer_header('Myパートナー', PG_NULL) ?>


<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> 
<td width="12" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_MYPARTNER); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/mkk_myenq/title01.gif" alt="MY MENU" width="553" height="25"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title01.gif" alt="Myパートナー" width="123" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title02.gif" alt="Myパートナー実施にあたり" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
<tr> 
                <td class="footer_text"><img src="images/common_my/bt_03.gif" width="12" height="11"><a href="mk/service/mk_mymonitor.php" target="_blank">Myパートナーについてはこちら</a>（新しくブラウザが開きます）</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"> </td>
</tr>
<tr> 
<td height="130" bgcolor="#e5eaf0"> 
<div align="center"> 
<table border="0" cellspacing="0" cellpadding="0">
<tr> 
                        <td><a href="pdf/myp_manual_a.pdf" target="_blank"><img src="images/mkk_myenq/mark_pdf.gif" width="37" height="32" border="0"></a></td>
                        <td><a href="pdf/myp_manual_a.pdf" target="_blank" onMouseOver="MM_swapImage('Image541','','images/mkk_myenq/bt_01_ov.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/mkk_myenq/bt_01.gif" alt="設定マニュアル" name="Image541" width="148" height="31" border="0" id="Image541"></a></td>
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
                        <td><a href="pdf/myp_manual_b.pdf" target="_blank"><img src="images/mkk_myenq/mark_pdf.gif" width="37" height="32" border="0"></a></td>
                        <td><a href="pdf/myp_manual_b.pdf" target="_blank" onMouseOver="MM_swapImage('Image67','','images/mp_pjt_list/bt_01_ov.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/mp_pjt_list/bt_01.gif" alt="会議室運営マニュアル" name="Image67" width="148" height="31" border="0"></a></td>
</tr>
<tr> 
<td>&nbsp;</td>
                        <td class="footer_text">Ver1.3（2005/11/9更新）</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
                        <td class="footer_text">Ver1.2（2005/11/9更新）</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="1" height="1"></td>
                        <td colspan="4" class="footer_text">※マニュアルは、右クリックで「対象をファイルに保存」を選んでください。</td>
</tr>
<tr> 
<td colspan="5"><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><a href="myp_price.php" onMouseOver="MM_swapImage('Image551','','images/mkk_myenq/bt_02_ov.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/mkk_myenq/bt_02.gif" alt="ご利用料金について" name="Image551" width="148" height="31" border="0" id="Image551"></a></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><a href="mkk_contact.php" onMouseOver="MM_swapImage('Image561','','images/mkk_myenq/bt_03_ov.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="images/mkk_myenq/bt_03.gif" alt="エージェントに相談" name="Image561" width="148" height="31" border="0" id="Image561"></a></td>
</tr>
</table>
</div>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title03.gif" alt="Myパートナーの設定・実施" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td><a href="mp_pjt_new2.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image68','','images/mp_pjt_list/bt_02_ov.gif',1)"><img src="images/mp_pjt_list/bt_02.gif" alt="会議室の新規登録" name="Image68" width="201" height="40" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title04.gif" alt="会議室一覧" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="553"  border="0" cellpadding="5" cellspacing="1" >
<tr class="description"> 
<td bgcolor="#e5eaf0">ＩＤ</td>
<td bgcolor="#e5eaf0">会議室名</td>
<td bgcolor="#e5eaf0">詳細設定</td>
<td bgcolor="#e5eaf0">期間</td>
<td bgcolor="#e5eaf0">メンバー数</td>
<td bgcolor="#e5eaf0">ステータス</td>
</tr>


<?
$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT ppj_pjt_id,ppj_room_name,ppj_recruit_start,ppj_room_end,ppj_member_num,ppj_status,ppj_icp_member_num"
		. " FROM t_mp_pjt"
		. " WHERE ppj_marketer_id=$owner_id"
		. " ORDER BY ppj_pjt_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;

	if ($fetch->ppj_icp_member_num == null) {
		$member_num_status = '目標';
		$member_num = $fetch->ppj_member_num;
	} else {
		$member_num_status = '確定';
		$member_num = $fetch->ppj_icp_member_num;
	}
?>
<tr class="description"> 
<td bgcolor="#FFFFFF"><a href="mp_pjt_new2.php?pjt_id=<?=$pjt_id?>"><?=$pjt_id?></a></td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($fetch->ppj_room_name)?></td>
<td bgcolor="#FFFFFF" align="center"><input type="button" name="" value="詳細設定" onClick="location.href='mp_pjt.php?pjt_id=<?=$pjt_id?>'"></td>
<td align="center" bgcolor="#FFFFFF"><?=format_date($fetch->ppj_recruit_start)?>〜<?=format_date($fetch->ppj_room_end)?></td>
<td align="center" bgcolor="#FFFFFF"><?=$member_num?><br>（<?=$member_num_status?>）</td>
<?
 if ($fetch->ppj_status >= MPS_ROOM_JISSHI) {
?>
<td align="center" bgcolor="#FFFFFF"><a href="mp_room.php?pjt_id=<?=$pjt_id?>" target="room"><?=decode_mypartner_status($fetch->ppj_status)?></a></td>
<?
 } else {
?>
<td align="center" bgcolor="#FFFFFF"><?=decode_mypartner_status($fetch->ppj_status)?></td>
<?
	}
?>
</tr>
<?
}
?>

</table>
</td>
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
