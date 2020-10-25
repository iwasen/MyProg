<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
//jeon_start seq=84
include("$inc/search.php");
//jeon_end seq=84
include("$inc/format.php");
include("$inc/pro_enq_target.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
//kim_start seq no=50 文字変更
set_global('myenquete', 'Ｍｙアンケート管理', '本アンケート回答数詳細', BACK_TOP);
//kim_end seq no=50
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<!--
function onsubmit_form1(f) {
	return confirm("このＭｙアンケートを承認します。よろしいですか？");
}
<?
//jeon_start seq=84
/**
 * kim_start50 DB WHERE文 変更
 */
// アンケートID.開始日時取得
$sql = "SELECT me_enquete_id,en_start_date,me_search_id"
		. " FROM t_my_enquete"
		. " JOIN t_enquete ON en_enquete_id=me_enquete_id"
		. " WHERE me_marketer_id=$marketer_id AND me_my_enq_no=$my_enq_no";
		
/**
 * kim_end50
 */
 //jeon_end seq=84
$result = db_exec($sql);

if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->me_enquete_id;
	$start_date = sql_date(format_date($fetch->en_start_date));
	//jeon_end seq=84	
	$search_id = $fetch->me_search_id;
	//jeon_end seq=84
} else{
	//echo("資料がないです");
	redirect('show.php');
	false;
}
// 利用ネットワークの取得	2006/02/27 BTI
$pro_enq_target = new pro_enq_target_class;
$pro_enq_target->read_db($enquete_id);
$target_flg = $pro_enq_target->target_flg;
// jeon_start seq=84////////////////
//$owner_id = $marketer_id;
//$pro_enq->read_db($owner_id, $my_enq_no);
$pro_enq_age_option = new cell_class;
$pro_enq_age_option->read_db($search_id);
$age_option = $pro_enq_age_option->age_option;
$cell_option = $pro_enq_age_option->cell_option;
$search = &$pro_enq->search;
	if($cell_option==1){		
			if ($search->age_cd != '')
				$age_type = '1';
			elseif ($search->age_from != '' || $search->age_to != '')
				$age_type = '2';
			else
				$age_type = '';
	}

if ($age_option == 2 || $age_type==1){//5歳刻み時、またサンプル数割付しない時5歳刻み時){
		if ($target_flg == 't') {
		$sql = "SELECT mn_sex,ae_age_cd AS ge_age_cd,COUNT(*) AS recv_count"
				. " FROM t_enquete_list"
				. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
				. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
				. " JOIN m_age ON mn_age BETWEEN ae_age_from AND ae_age_to"
				. " WHERE el_enquete_id=$enquete_id AND an_valid_flag"
				. " GROUP BY mn_sex,ae_age_cd;";
	} else {
		$sql = "SELECT mn_sex,ae_age_cd,COUNT(*) AS recv_count"
				. " FROM t_answer"
				. " JOIN ${target_flg}_monitor ON mn_monitor_id=an_monitor_id"				
				. " JOIN m_age ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
				. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
				. " GROUP BY mn_sex,ae_age_cd;";
	}
	
}else {	
// jeon_end seq=84//////////////////
		if ($target_flg == 't') {
		$sql = "SELECT mn_sex,ge_age_cd,COUNT(*) AS recv_count"
				. " FROM t_enquete_list"
				. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
				. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
				. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
				. " WHERE el_enquete_id=$enquete_id AND an_valid_flag"
				. " GROUP BY mn_sex,ge_age_cd";
	} else {
		$sql = "SELECT mn_sex,ge_age_cd,COUNT(*) AS recv_count"
				. " FROM t_answer"
				. " JOIN ${target_flg}_monitor ON mn_monitor_id=an_monitor_id"
				. " JOIN m_age10 ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
				. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
				. " GROUP BY mn_sex,ge_age_cd";
	}
}			 
// jeon_end seq=84//////////////////
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$cell[$fetch->mn_sex][$fetch->ge_age_cd] = $fetch->recv_count;
}
// jeon_start seq=84
if ($age_option == 2 || $age_type==1){
$sex_s = 1;
$sex_e = 2;
$age_s = 3;
$age_e = 14;
}else{	
// jeon_end seq=84-->
$sex_s = 1;
$sex_e = 2;
$age_s = 2;
$age_e = 8;
}
?>
//-->
</script>
</head>
<body>
<? //kim seq no=50 全て image経路変更?>
<? page_header() ?>
<table align="center">
  <tr>
    <td>
<table >
	<tr>
		<td><img src="../../../marketer/images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<!--<td width="10"><img src="../../../marketer/images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">//-->
			<table  border="0" cellspacing="0" cellpadding="0" width="778">
					<!--<tr>
					<td width="10"><img src="../../../marketer/images/common/spacer.gif" width="10" border="0"></td>
				<td width="768" valign="top"><img src="../../../marketer/images/t_mkk_enqdet1.gif" width="538" height="20"></td>
				</tr>//-->
			</table>
			<br>
			<table align="center" width="650" border="0" cellspacing="0" cellpadding="0">
				<tr>
					
					<td align="right">
						<form method="post" action="pe_recv_csv.php">
						<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
						<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
						<input type="hidden" name="age_type" value="<?=$age_type?>">
						<input type="image" src="../../../marketer/images/mkk_bt/dl_csv.gif" alt="CSVのダウンロード">
						</form>
					</td>
				</tr>
				<tr><?
						//kim_start seq no=50 経路変更 及び 認証変更
					?>
					<!--<td align="right" colspan=2>
						<a href="pe_enqdet1.php?pro_enq_no=<?=$my_enq_no?>">アンケート詳細表示に戻る</a>
					</td>//-->
					<?
						//kim_end seq no=50
					?>
				</tr>
			</table>
			<br>
			<form>
			<center>
			<table  width="650" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">

			<table align="center" width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
<!-- jeon_start seq=84-->
<?if ($age_option == 2 || $age_type==1){?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%" rowspan=2>&nbsp;</td>
					<td nowrap width="5%" align="center">10代</td>
					<td nowrap width="10%" align="center" colspan=2>20代</td>
					<td nowrap width="10%" align="center" colspan=2>30代</td>
					<td nowrap width="10%" align="center" colspan=2>40代</td>
					<td nowrap width="10%" align="center" colspan=2>50代</td>
					<td nowrap width="10%" align="center" colspan=2>60代</td>
					<td nowrap width="5%" align="center" rowspan=2>70代<br>以上</td>
					<td nowrap width="10%" align="center" rowspan=2>合計</td>
				</tr>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="5%" align="center">後</td>
					<td nowrap width="5%" align="center">前</td>
					<td nowrap width="5%" align="center">後</td>
					<td nowrap width="5%" align="center">前</td>
					<td nowrap width="5%" align="center">後</td>
					<td nowrap width="5%" align="center">前</td>
					<td nowrap width="5%" align="center">後</td>
					<td nowrap width="5%" align="center">前</td>
					<td nowrap width="5%" align="center">後</td>
					<td nowrap width="5%" align="center">前</td>
					<td nowrap width="5%" align="center">後</td>
				</tr>
<?}else{?>	
<!-- jeon_end seq=84-->
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">10代</td>
					<td nowrap width="10%">20代</td>
					<td nowrap width="10%">30代</td>
					<td nowrap width="10%">40代</td>
					<td nowrap width="10%">50代</td>
					<td nowrap width="10%">60代</td>
					<td nowrap width="10%">70代以上</td>
					<td nowrap width="10%">合計</td>
				</tr>
<!-- jeon_start seq=84-->				
<?}?>				
<!-- jeon_end seq-84-->
<?
$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center"><?=decode_sex($sex, '性別指定なし')?></td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
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
					<td bgcolor="#eeeeee" nowrap align="center">合計</td>
<?
$sum_col = 0;
for ($age = $age_s; $age <= $age_e; $age++) {
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
								<!--kim_start seq no50 経路変更 及び 認証変更//-->
									<a href="show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>"><img src="../../../marketer/images/mkk_bt/back.gif" alt="戻る" onclick="history.back(-1);" name="image2" width="100" height="31" border="0"></a>
								<!--kim_end seq no50//-->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			</form>
			<?$age_type?>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="../../../marketer/images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
<? page_footer() ?>
</body>
</html>
