<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/my_search.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ������說�饹����
$search = new search_class;

// ����ID����
$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT ms_title,ms_search_id FROM t_my_search WHERE ms_marketer_id=$owner_id AND ms_search_no=$search_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$title = $fetch->ms_title;
	$age_cd = explode(',', $search->age_cd);

	if (!$clear)
		$search->read_db($fetch->ms_search_id);
}

if (isset($type))
	$search->type = $type;
elseif ($search->type == '')
	$search->type = '5';
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
<form method="post" action="mkk_search.php">
<input type="hidden" name="search_no" value="<?=$search_no?>">
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
<td> 
<p>�����������ֹ����ܥ���פ򲡤��ȡ���Ͽ�ξ��˹礦��˥�������ɽ������ޤ��� </p>
<p>��2006ǯ8��1�����ɲä��줿������°���ˤĤ��Ƥϡ�<a href="mkk_monitor.php">��˥����ץ�ե�����</a>�򤴻��Ȥ���������</p></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="553" border="0" cellspacing="1" cellpadding="5">
<tr> 
<td width="20%" bgcolor="#e5eaf0">�оݥ��󥱡���</td>
<td width="80%" bgcolor="#FFFFFF"> 
<input type="radio" name="type" <?=value_checked('5', $search->type)?> onclick="javascript:location.href='mkk_searchupd.php?search_no=<?=$search_no?>&type=5'">My���󥱡���&nbsp;
<input type="radio" name="type" <?=value_checked('6', $search->type)?> onclick="javascript:location.href='mkk_searchupd.php?search_no=<?=$search_no?>&type=6'">Pro���󥱡���
<br>���оݥ��󥱡��Ȥˤ���оݿͿ���������郎�Ѥ��ޤ���
</td>
</tr>
<tr> 
<td width="20%" bgcolor="#e5eaf0">�����ȥ�</td>
<td width="80%" bgcolor="#FFFFFF"> 
<input type="text" name="title" size=40 maxlength=50 <?=value($title)?>>
</td>
</tr>
<tr> 
<td width="20%" bgcolor="#e5eaf0">����</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td width="50%" valign="top"> 
<input type="checkbox" name="sex[]" <?=value_checked('1', $search->sex)?>>
����</td>
<td width="50%" valign="top"> 
<input type="checkbox" name="sex[]" <?=value_checked('2', $search->sex)?>>
����</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td width="20%" bgcolor="#e5eaf0">ǯ��</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr>
<td>
<?
$sql = "SELECT ae_age_cd,ae_age_text FROM m_age ORDER BY ae_age_cd";
checkbox_common($sql, 'age_cd', $search->age_cd, 2);
?></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td width="20%" height="34" bgcolor="#e5eaf0">̤����</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td valign="top"> 
<input type="checkbox" name="mikikon[]" <?=value_checked('1', $search->mikikon)?>>
̤��</td>
<td valign="top"> 
<input type="checkbox" name="mikikon[]" <?=value_checked('2', $search->mikikon)?>>
����</td>
<td valign="top"> 
<input type="checkbox" name="mikikon[]" <?=value_checked('3', $search->mikikon)?>>
����¾</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td width="20%" bgcolor="#e5eaf0">�ｻ�ϰ�</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td>
<?
$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
checkbox_common($sql, 'jitaku_area', $search->jitaku_area, 4);
?>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td width="20%" bgcolor="#e5eaf0">����</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td>
<?
$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
checkbox_common($sql, 'shokugyou', $search->shokugyou, 2);
?>
</td>
</tr>
</table>
</td>
</tr>
<?
if ($search->type == 5) {
?>
<tr> 
<td bgcolor="#e5eaf0">�ȼ�</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td>
<?
$sql = "SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=1 ORDER BY gs_order";
checkbox_common($sql, 'gyoushu', $search->gyoushu, 2);
?>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0">����</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td>
<?
$sql = "SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order";
checkbox_common($sql, 'shokushu', $search->shokushu, 2);
?>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td width="20%" bgcolor="#e5eaf0">����ӥ�</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td>
<?
$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
checkbox_common($sql, 'conveni', $search->conveni, 2);
?>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td width="20%" bgcolor="#e5eaf0">�����ѡ�</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td>
<?
$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
checkbox_common($sql, 'super', $search->super, 2);
?>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td width="20%" bgcolor="#e5eaf0">��̣������</td>
<td width="80%" bgcolor="#FFFFFF"> 
<table border="0" width="100%">
<tr> 
<td>
<?
$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
checkbox_common($sql, 'genre', $search->genre, 2);
?>
</td>
</tr>
</table>
</td>
</tr>
<?
}
?>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td> 
<div align="center"> 
<table border="0" cellspacing="0" cellpadding="0">
<tr> 
<td> 
<input name="image" type="image" src="images/mkk_mysearch/bt01.gif" alt="�������Ƹ���" width="148" height="31">
</td>
<td><img src="images/common/spacer.gif" width="20" height="10"></td>
<td> 
<a href="mkk_searchupd.php?search_no=<?=$search_no?>&type=<?=$search->type?>&clear=1"><img src="images/mkk_mysearch/bt02.gif" alt="���ꥢ" width="148" height="31" border="0" ></a>
</td>
</tr>
</table>
</div>
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
</form>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer() ?>
