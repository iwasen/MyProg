<?
$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('�������ơ�net �ͥåȥꥵ�����˥ץ�Υ���������Ȥ�', PG_NULL) ?>

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

<? marketer_menu(M_ASSISTANT); ?>

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
<td><img src="images/mkk_assistant/title01.gif" alt="����������Ȥ�����" width="200" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> 
<p>����������Ȥ�̾���ꤷ�Ƥ����������Ȥ��Ǥ��ޤ���<br>
����������Ȥϰ����ε�ǽ�򤢤ʤ�������������¹ԤǤ��ޤ���<br>
����������Ȥ��Ǥ����Ȥϲ������̤�Ȥʤ�ޤ��� </p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20" /></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center"><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center"> 
<table width="350" border="0" cellspacing="0" cellpadding="0" class="nor_text">
<tr> 
<td> ���ͣ����󥱡��ȡ��У�異�󥱡��Ȥκ��������� <br>
<br>
���������߼»ܤΥ��󥱡��Ⱦܺٱ�������������� <br>
<br>
������������Ȥؤ��䤤��碌 <br>
<br>
���ͣ��ѡ��ȥʡ��μ»� <br>
<br>
���ͣ������������ꡦ���������� </td>
</tr>
</table>
</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center"><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20" /></td>
</tr>

<?
$marketer_id = $_SESSION['ss_marketer_id'];
$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_parent_id=$marketer_id AND mr_status<>9 AND mr_type=4";
$result = db_exec($sql);
$nrow = pg_numrows($result);

if($nrow>=1) {
	$alt1 = "���������";
	$img1 = "bt07";
} else {
	$alt1 = "��Ͽ";
	$img1 = "bt01";
}

if($nrow>=2) {
	$alt2 = "���������";
	$img2 = "bt08";
} else {
	$alt2 = "��Ͽ";
	$img2 = "bt02";
}
?>

<tr> 
<td align="center"><a href="mkk_upd_assistant.php?assistant=1" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image54','','images/mkk_assistant/<?=$img1 ?>_ov.gif',1)"><img src="images/mkk_assistant/<?=$img1 ?>.gif" alt="�֥����������1�פ�<?=$alt1 ?> " name="Image54" width="220" height="31" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td align="center"><a href="mkk_upd_assistant.php?assistant=2" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image55','','images/mkk_assistant/<?=$img2 ?>_ov.gif',1)"><img src="images/mkk_assistant/<?=$img2 ?>.gif" alt="�֥����������2�פ�<?=$alt2 ?> " name="Image55" width="220" height="31" border="0"></a></td>
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

<? marketer_footer() ?>
