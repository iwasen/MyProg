<?
$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
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

<? marketer_menu(M_MYAGENT); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/common_my/title_07.gif" alt="MY SETTING" width="553" height="25" /></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mkk_myagent/title01.gif" alt="Myエージェント" width="130" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> 
<p>Ｍｙアンケートや調査についてのご相談などがございましたら、エージェントへお尋ねください。</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><a href="mkk_contact.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image54','','images/mkk_myagent/bt_01_ov.gif',1)"><img src="images/mkk_myagent/bt_01.gif" alt="エージェントにメッセージを送る " name="Image54" width="268" height="31" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
<tr> 
<td> 
<p>エージェントがあなたにお届けした、メッセージの一覧が表示されます。</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><a href="mkk_mlist.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image55','','images/mkk_myagent/bt_02_ov.gif',1)"><img src="images/mkk_myagent/bt_02.gif" alt="エージェントからのメッセージ一覧 " name="Image55" width="280" height="31" border="0"></a></td>
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

<? marketer_footer(BLUE) ?>
