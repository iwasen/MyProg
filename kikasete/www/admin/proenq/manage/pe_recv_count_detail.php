<?
$top = '../..';
$inc = "$top/../inc";
include ("$inc/common.php");
include ("$inc/database.php");
include ("$inc/decode.php");
//jeon_start seq=84
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/enquete.php");
//jeon_end seq=84
include ("$inc/format.php");
include ("$inc/pro_enq_target.php");
$inc = "$top/inc";
include ("$inc/login_check.php");
include ("$inc/header.php");
set_global('proenquete', '�У�異�󥱡��ȴ���', '�ܥ��󥱡��Ȳ������ܺ�', BACK_TOP);
// ���󥱡���ID.������������
//jeon_start seq=84
/*
$sql = "SELECT mep_enquete2_id,en_start_date"
		. " FROM t_pro_enquete"
		. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
s		. " WHERE mep_marketer_id={$_SESSION['ss_owner_id']} AND mep_pro_enq_no=$pro_enq_no";
*/
$sql = "SELECT mep_enquete2_id,en_start_date,mep_search_id"
		. " FROM t_pro_enquete"
		. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
		. " WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
//jeon_end seq=84	
	

$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$enquete_id = $fetch->mep_enquete2_id;
	$start_date = sql_date(format_date($fetch->en_start_date));
//jeon_end seq=84	
//	$search_id = $fetch->mep_search_id;
//jeon_end seq=84		
} else
	redirect("show.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");	

// ���ѥͥåȥ���μ���	2006/02/27 BTI
$pro_enq_target = new pro_enq_target_class;
$pro_enq_target->read_db($enquete_id);
$target_flg = $pro_enq_target->target_flg;
// jeon_start seq=84////////////////
$owner_id = $marketer_id;
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($owner_id, $pro_enq_no);
$pro_enq_age_option = new cell_class;
$pro_enq_age_option->read_db($pro_enq->search_id);
$age_option = $pro_enq_age_option->age_option;
$sex_option = $pro_enq_age_option->sex_option;
$cell_option = $pro_enq_age_option->cell_option;
$search = &$pro_enq->search;
if ($search->age_cd != '')
	$age_type = '1';
elseif ($search->age_from != '' || $search->age_to != '')
	$age_type = '2';
else
	$age_type = '';
if ($sex_option == 1){
	$select = "0 as mn_sex";
	$group = "";
}else {
	$select = "mn_sex";
	$group = "mn_sex,";
}
if ($age_option == 2)//5�й�߻����ޤ�����ץ�����դ��ʤ���5�й�߻�
{
	if ($target_flg == 't')
	{
		$sql = "SELECT $select,ae_age_cd AS ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_enquete_list" 
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id" 
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " JOIN m_age ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to" 
		. " WHERE el_enquete_id=$enquete_id AND an_valid_flag" 
		. " GROUP BY $group ae_age_cd;";
	}
	else
	{
		$sql = "SELECT $select,ae_age_cd AS ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_answer" 
		. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
		. " JOIN m_age ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to" 
		. " WHERE an_enquete_id=$enquete_id AND an_valid_flag" 
		. " GROUP BY $group ae_age_cd;";		
	}
} else if($age_option == 1){
	if ($sex_option != 1){
		$group = "GROUP BY mn_sex";
	}
	if ($target_flg == 't')
	{
		$sql = "SELECT $select, 1 AS ge_age_cd,COUNT(*) AS recv_count"
		. " FROM t_enquete_list"
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " WHERE el_enquete_id=$enquete_id AND an_valid_flag"
		. " $group;";
	}
	else
	{
		$sql = "SELECT $select,1 AS ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_answer" 
		. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
		. " WHERE an_enquete_id=$enquete_id AND an_valid_flag" 
		. " $group;";		
	}		
} else {
	// jeon_end seq=84//////////////////
	if ($target_flg == 't')
	{
		$sql = "SELECT $select,ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_enquete_list" 
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id" 
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " JOIN m_age10 ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to" 
		. " WHERE el_enquete_id=$enquete_id AND an_valid_flag" 
		. " GROUP BY $group ge_age_cd";
	}
	else
	{
		$sql = "SELECT $select,ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_answer" 
		. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
		. " JOIN m_age10 ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to" 
		. " WHERE an_enquete_id=$enquete_id AND an_valid_flag" 
		. " GROUP BY $group ge_age_cd";
	}
}
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++)
{
	$fetch = pg_fetch_object($result, $i);
	$cell[$fetch->mn_sex][$fetch->ge_age_cd] = $fetch->recv_count;
}
// jeon_start seq=84
if ($age_option==2){
	if ($sex_option == 1){
		$sex_s = 0;
		$sex_e = 0;
	}else {
		$sex_s = 1;
		$sex_e = 2;
	}
	$age_s = 3;
	$age_e = 14;
}else if($age_option == 1){
	if ($sex_option == 1){
		$sex_s = 0;
		$sex_e = 0;
	}else {
		$sex_s = 1;
		$sex_e = 2;
	}
	$age_s = 1;
	$age_e = 1;	
}else {
	if ($sex_option == 1){
		$sex_s = 0;
		$sex_e = 0;
	}else {
		$sex_s = 1;
		$sex_e = 2;
	}
	$age_s = 2;
	$age_e = 8;
}
// Add Bti 2007/03/08 s
if ($sex_option == 1) {
	if ($search->sex == "1") {
		$default_sex = "����";
	} elseif ($search->sex == "2") {
		$default_sex = "����";
	} else {
		$default_sex = "���̻���ʤ�";
	}
}
// Add Bti 2007/03/08 e
?>

<html lang="ja">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
		<meta http-equiv="Pragma" content="no-cache">
		<title><?=$g_title?></title>
		<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
		<script type="text/javascript">
<!--
function onsubmit_form1(f) {
    return confirm("���ΣУ�異�󥱡��Ȥ�ǧ���ޤ���������Ǥ�����");
}
//-->
</script>	
<? page_header() ?>
<div align="center">
<table align="center" border=0 cellspacing=2 cellpadding=3 width="80%" frame="box">
  <tr>
    <td>
<table >
	<tr>
		<td><img src="../images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>

<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<!-- kim start seq no=50 image��ϩ�ѹ�
		<td width="10"><img src="../images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
			<tr>
					<td width="10"><img src="../images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="../images/t_mkk_enqdet1.gif" width="538" height="20"></td>
				</tr> kim start seq no=50//-->
			</table>
			<br>
			<table align="center" width="650" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<!-- kim_start seq no=50 ʸ���ѹ�<td>
						�ܥ��󥱡��Ȳ������ܺ�
					</td> kim_end seq no=50//-->
					<td align="right">
						<form method="post" action="pe_recv_csv.php">
						<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
						<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
						<input type="hidden" name="age_type" value="<?=$age_type?>">
						<input type="image" src="../../images/dl_csv.gif" alt="CSV�Υ��������">
						</form>
					</td>
				</tr>
				<tr><!--kim_start seq no 50  ��ϩ�ѹ�
					<td align="right" colspan=2>
						<a href="pe_enqdet1.php?pro_enq_no=<?=$pro_enq_no?>">���󥱡��Ⱦܺ�ɽ�������</a>
					</td> kim_end seq no50//-->
				</tr>
			</table>
			<br>
			<form>
			<center>
			<table width="650" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">

			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<!-- jeon_start seq=84-->
<?if ($age_option == 2){?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%" rowspan=2>&nbsp;</td>
					<td nowrap width="5%" align="center">10��</td>
					<td nowrap width="10%" align="center" colspan=2>20��</td>
					<td nowrap width="10%" align="center" colspan=2>30��</td>
					<td nowrap width="10%" align="center" colspan=2>40��</td>
					<td nowrap width="10%" align="center" colspan=2>50��</td>
					<td nowrap width="10%" align="center" colspan=2>60��</td>
					<td nowrap width="5%" align="center" rowspan=2>70��<br>�ʾ�</td>
					<td nowrap width="10%" align="center" rowspan=2>���</td>
				</tr>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
					<td nowrap width="5%" align="center">��</td>
				</tr>
<? 
}else if($age_option == 1){				
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">ǯ�����ʤ�</td>
					<td nowrap width="10%">���</td>
				</tr>
<?
} else {
?>	
<!-- jeon_end seq=84-->
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">10��</td>
					<td nowrap width="10%">20��</td>
					<td nowrap width="10%">30��</td>
					<td nowrap width="10%">40��</td>
					<td nowrap width="10%">50��</td>
					<td nowrap width="10%">60��</td>
					<td nowrap width="10%">70��ʾ�</td>
					<td nowrap width="10%">���</td>
				</tr>
<!-- jeon_start seq=84-->				
<?}?>				
<!-- jeon_end seq-84-->
<?

$sum_row = array ();
for ($sex = $sex_s; $sex <= $sex_e; $sex++)
{
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center"><?=decode_sex($sex, $default_sex)?></td>
<?

	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++)
	{
		$num = $cell[$sex][$age];
		$sum_col += $num;
		$sum_row[$age] += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?

	}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
<?

}
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center">���</td>
<?

$sum_col = 0;
for ($age = $age_s; $age <= $age_e; $age++)
{
	$num = $sum_row[$age];
	$sum_col += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?

}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
			</table>
					</td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="100%" align="center">
								<!--kim_start seq no=50  ��ϩ��//-->
								<a href="show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>"><img src="../../images/back.gif" alt="���" name="image2" onclick="" width="108" height="31" border="0"></a>
								<!-- kim_end seq no=50//-->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="../images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? page_footer() ?>
</body>
</html>
