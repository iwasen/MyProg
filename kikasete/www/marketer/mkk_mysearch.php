<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_disp.php");
?>
<? marketer_header('�������ơ�net �ͥåȥꥵ�����˥ץ�Υ���������Ȥ�', PG_NULL) ?>


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

<? marketer_menu(M_MYSEARCH); ?>

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
<td><img src="images/mkk_mysearch/title_01.gif" alt="My������" width="123" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="images/mkk_mysearch/title_02.gif" alt="My�������ˤĤ���" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"> </td>
</tr>
<tr> 
<td> 
<p>�֤���ʾ��ǥ��󥱡��Ȥ��Ƥߤ������ɡ������ͤ�����������<br>
��My���󥱡��ȤǤ���Ϳ��ˤ⤦�ʤä����������ס�����<br>
<br>
����ʻ�����󸡺����ʤ��Ƥ⡢���Ρ�My�������٤���Ͽ���Ƥ����С�������Ѳ������˥������򥦥��å��Ǥ��ޤ��� ���������Ȼפ������ߥ󥰤�����ޤ����顢���ҥ���������Ȥ˰��������Ʋ�������<br>
</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
<tr> 
<td><img src="images/mkk_mysearch/title_03.gif" alt="My����������Ͽ" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td> 
<p> <? disp_marketer_name() ?>����ˡ�����Ͽĺ���Ƥ���°���Υ�˥������Ǥ���</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="553"  border="0" cellpadding="5" cellspacing="1" >
<tr class="description"> 
<td width="10%" bgcolor="#e5eaf0">No</td>
<td width="70%" bgcolor="#e5eaf0">�����ȥ�</td>
<td width="20%" bgcolor="#e5eaf0">��˥�����</td>
</tr>

<?
$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT ms_search_no,ms_title,ms_monitor_num FROM t_my_search WHERE ms_marketer_id=$owner_id ORDER BY ms_search_no";
$result = db_exec($sql);
for ($i = 0, $search_no = 1; $search_no <= 3; $search_no++) {
	$title = '��������Ͽ�Ǥ��ޤ���';
	$monitor_num = 0;

	if (pg_numrows($result) > $i) {
		$fetch = pg_fetch_object($result, $i);
		if ($fetch->ms_search_no == $search_no) {
			$title = htmlspecialchars($fetch->ms_title);
			$monitor_num = number_format($fetch->ms_monitor_num);
			$i++;
		}
	}
?>

<tr class="description"> 
<td bgcolor="#FFFFFF"><b><?=$search_no?>.</b></td>
<td bgcolor="#FFFFFF"><a href="mkk_searchupd.php?search_no=<?=$search_no?>"><?=$title?></a></td>
<td align="center" bgcolor="#FFFFFF"><?=$monitor_num?></td>
</tr>
<?
}
?>

</table>
</td>
</tr>
<tr> 
<td class="footer_text">�� �����ѹ��������ϡ������ȥ�򥯥�å����Ƥ���������</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td><img src="images/mkk_mysearch/title_04.gif" alt="����ץ�" width="161" height="21"></td>
</tr>
<tr> 
<td> 
<p>���󥱡��Ȥ��ֿ�Ψ��ʿ��Ū�ʣ�����Ȥ������</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td>
<p>�� �������󥱡��� �������ͤ��ܰ¤Ǥ��ʣ�������ץ������<br>
<br>
�� ���̥��󥱡��� �������ͤ��ܰ¤Ǥ��ʣ���������ץ������<br>
<br>
�� �絬�ϥ��󥱡��� ���������ͤ��ܰ¤Ǥ��ʣ�����������ץ������<br>
</p>
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

<? marketer_footer() ?>
