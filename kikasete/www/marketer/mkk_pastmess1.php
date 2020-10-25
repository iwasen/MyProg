<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>

<?
$owner_id = $_SESSION['ss_owner_id'];
if(isset($seq_no) && ctype_digit($seq_no)) {
	$sql = "SELECT co_title,co_text"
		. " FROM t_communication"
		. " WHERE co_seq_no=$seq_no AND co_marketer_id=$owner_id AND co_status=0 AND co_direction=1";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$title = htmlspecialchars($fetch->co_title);
		$text = nl2br($fetch->co_text);
	}
}
?>

<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_NULL) ?>

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

<? marketer_menu(M_CONTACT); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/common_my/title_06.gif" alt="COMMON" width="553" height="25"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/contactus/title_01.gif" alt="contact us" width="164" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> <img src="images/contactus/title_02.gif" alt="メッセージ詳細" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20" /></td>
</tr>
<tr>
<td><p><?=$title?></p></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr> 
<td> 
<form>
<input type="hidden" name="seq_no" value="<?=$seq_no?>">
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td bgcolor="#dadada"> 
<table width="553" border="0" cellspacing="1" cellpadding="5" class="nor_text">
<tr> 
<td height="300" bgcolor="#e5eaf0" valign="top"><?=$text?></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td align="center"> 
<a href="javascript:history.back();"><img src="images/contactus/bt_03.gif" alt="戻 る" width="108" height="31" border="0"></a>
</td>
</tr>
</table>
</form>
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

<? marketer_footer(BLUE); ?>
