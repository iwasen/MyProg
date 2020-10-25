<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enq_target.php");
include("$inc/pro_enquete.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
// セッション処理
set_global('proenquete', 'Ｐｒｏアンケート管理', 'ファインディング回答数', BACK_TOP);
if (isset($pro_enq_no)) {
	$pro_enq = new pro_enquete_class;
	$pro_enq->read_db($marketer_id, $pro_enq_no);
} else{
	//kim_start seq no=50 echo追加
	echo("資料がありません");
	exit;
	//redirect('../admin/proenq/manage/show.php');
	//kim_end seq no=50
}
$search = &$pro_enq->search;
$cell = &$pro_enq->cell;
//Add Bti 2007/03/08 s
$pro_enq_age_option = new cell_class;
$pro_enq_age_option->read_db($pro_enq->search_id);
$sex_option = $pro_enq_age_option->sex_option;
//Add Bti 2007/03/08 e
$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

$pro_enq_target =& new pro_enq_target_class;
$pro_enq_target->read_db($pro_enq->enquete_id);

// Add Bti 2007/03/08 s
if ($sex_option == 1) {
	if ($search->sex == "1") {
		$default_sex = "男性";
	} elseif ($search->sex == "2") {
		$default_sex = "女性";
	} else {
		$default_sex = "性別指定なし";
	}
}
// Add Bti 2007/03/08 e

// 現サンプル数取得
$sample_cell = $cell->get_sample_cell($pro_enq->enquete_id, $enquete->start_date, $pro_enq_target->target_flg);
?>

<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--kim_start seq no=50 関数追加//-->
<!--
function onsubmit_form1(f) {
    return confirm("このＰｒｏアンケートを承認します。よろしいですか？");
}
//-->
<!--kim_end seq no=50//-->
</script>
</head>
<body>
<? page_header() ?>
<table align="center">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="../images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<!--<td width="10"><img src="../images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="../images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="../images/t_mkk_enqdet1.gif" width="538" height="20"></td>
				</tr>//-->
			</table>
		
			<br>
			<table align="center"width="650" border="0" cellspacing="0" cellpadding="0">
				<tr><!--kim_start seq no=50 文字変更
					<td>
						ファインディング回答数
					</td>kim_end seq no=50//-->
					<td align="right">
						<form method="post" action="pe_cell_csv.php">
						<input type="hidden" name="marketer_id" value="<?=$marketer_id?>">
						<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
						<input type="image" src="../../images/dl_csv.gif" alt="CSVのダウンロード">
						</form>
					</td>
				</tr>
				<tr><!--
					<td align="right" colspan=2>
						<a href="pe_enqdet1.php?pro_enq_no=<?=$pro_enq_no?>">アンケート詳細表示に戻る</a>
					</td>//-->
				</tr>
			</table>
			<br>
			<form>
			<center>
			<table width="650" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">

			<table width="650" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
<?
if ($cell->age_option == 2) {
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="5%" rowspan=2>&nbsp;</td>
					<td nowrap width="5%">10代</td>
					<td nowrap width="10%" colspan=2>20代</td>
					<td nowrap width="10%" colspan=2>30代</td>
					<td nowrap width="10%" colspan=2>40代</td>
					<td nowrap width="10%" colspan=2>50代</td>
					<td nowrap width="10%" colspan=2>60代</td>
					<td nowrap width="5%" rowspan=2>70代<br>以上</td>
					<td nowrap width="5%" rowspan=2>合計</td>
				</tr>
				<tr bgcolor="#eeeeee" align="center">
					<td>後半</td>
					<td>前半</td>
					<td>後半</td>
					<td>前半</td>
					<td>後半</td>
					<td>前半</td>
					<td>後半</td>
					<td>前半</td>
					<td>後半</td>
					<td>前半</td>
					<td>後半</td>
				</tr>
<?
} elseif ($cell->age_option == 3) {
?>
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
<?
} else {
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">年代指定なし</td>
					<td nowrap width="10%">合計</td>
				</tr>
<?
}

$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center"><?=decode_sex($sex, $default_sex)?></td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $sample_cell[$sex][$age];
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
									<form>
									<!--kim_start seq no50 経路変更//-->
										<a href="show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>"><img src="../../images/back.gif" alt="戻る" name="image2"  width="108" height="31" border="0"></a>
									<!--kim_end seq no50//-->
									</form>
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
