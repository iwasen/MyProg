<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_subr.php");

$owner_id = $_SESSION['ss_owner_id'];
?>
<? marketer_header('����������Ȥإ�å�����������', PG_CONTACT) ?>

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
<td> 
<p>�������ơ�net �ؤΤ��䤤��碌�ϡ����Ҥ��ʤ�����°����������Ȥˤ����̤���������</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20" /></td>
</tr>
<tr> 
<td> 
<form method="post" name="form1" action="mkk_messconfirm.php">
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td bgcolor="#dadada"> 
<table width="553" border="0" cellspacing="1" cellpadding="5" class="nor_text">
<tr> 
<td bgcolor="#e5eaf0">�����ȥ�</td>
</tr>
<tr> 
<td bgcolor="#FFFFFF"> 
<input type="text" size="54" name="title" value="����ˡ����η�ˤĤ���">
</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">��å�������ʸ</td>
</tr>
<tr> 
<td bgcolor="#FFFFFF"> 
<textarea rows="15" cols="60" wrap="hard" name="text"></textarea>
</td>
</tr>

<?
// ̾���ȥ᡼�륢�ɥ쥹����
$fetch = get_owner_info('mr_name1,mr_name2,mr_mail_addr');
$marketer_name = htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2");
$mail_addr = htmlspecialchars($fetch->mr_mail_addr);
?>

<tr> 
<td bgcolor="#e5eaf0"> ��̾��</td>
</tr>
<tr> 
<td bgcolor="#FFFFFF"><?=$marketer_name?></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">�᡼�륢�ɥ쥹</td>
</tr>
<tr> 
<td bgcolor="#FFFFFF"><?=$mail_addr?> </td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td align="center"> 
<table border="0" cellspacing="0" cellpadding="0">
<tr> 
<td> 
<input name="image2" type="image" src="images/contactus/bt_01.gif" alt="�Ρ�ǧ" width="108" height="31">
</td>
<td><img src="images/common/spacer.gif" width="20" height="10"></td>
<td> 
<a href="javascript:document.form1.reset();"><img name="image3" src="images/mkk_assistant/bt05.gif" alt="���ꥢ" width="108" height="31"></a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</td>
</tr>
<tr> 
<td align="right" class="footer_text">�� ��å�����������塢��°����������Ȥ��餴Ϣ�����Ƥ��������ޤ���</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20" /></td>
</tr>
<tr> 
<td class="right_menu_title">������°����������Ȥ����ä���å���������</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> 
<p>�����ȥ�򥯥�å�����ȡ����˥���������Ȥ�����������å������ξܺ٤�ߤ뤳�Ȥ��Ǥ��ޤ���</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td>
<form method="post" name="form2" action="mkk_messdelm.php">
<table width="553" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td bgcolor="#dadada"> 
<table width="553" border="0" cellspacing="1" cellpadding="5" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="65%">�����ȥ�</td>
<td width="25%">��������</td>
<td width="10%">���</td>
</tr>

<?
// ����������Ȥ���Υ�å�������������
$sql = "SELECT co_seq_no,co_regist_date,co_title"
		. " FROM t_communication"
		. " WHERE co_marketer_id=$owner_id AND co_status=0 AND co_direction=1 ORDER BY co_regist_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->co_seq_no;
	$title = htmlspecialchars($fetch->co_title);
	$regist_date = format_date($fetch->co_regist_date);
?>
<tr bgcolor="#FFFFFF"> 
<td width="65%"><a href="mkk_pastmess1.php?seq_no=<?=$seq_no?>"><?=$title?></a></td>
<td width="25%" align="right"><?=$regist_date?></td>
<td width="10%"><input type="checkbox" name="delete[]" value="<?=$seq_no?>"></td>
</tr>
<?
}
?>

</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td align="center"> 
<input name="image" type="image" src="images/mkk_assistant/bt03.gif" alt="���" width="108" height="31">
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

<? marketer_footer(BLUE) ?>
