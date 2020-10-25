<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/pro_enq_target.php");
//jeon_start seq=77
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/enquete.php");
//jeon_end seq=77
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$owner_id = $_SESSION['ss_owner_id'];

if ($pro_enq_no) {
	$sql = "SELECT mep_enquete_id,mep_enquete2_id,mep_send_num,mep_real_send,mep_search_id,mep_finding_flag,mep_sample_num"
			. " FROM t_pro_enquete"
			. " WHERE mep_marketer_id=$owner_id AND mep_pro_enq_no=$pro_enq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$send_num = number_format($fetch->mep_send_num);
		$real_send = number_format($fetch->mep_real_send);
		$enquete_id = $fetch->mep_enquete_id;
		$enquete2_id = $fetch->mep_enquete2_id;
		$search_id = $fetch->mep_search_id;
		$finding_flag = $fetch->mep_finding_flag;
		$sample_num = $fetch->mep_sample_num;
	}
}
//jeon_start seq=77
//$enquete = new enquete_class;
//$enquete->read_db($enquete_id);
// セッション処理
if (isset($pro_enq_no)) {
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else {
$pro_enq = &$_SESSION['ss_pro_enq'];
$pro_enq_no = $pro_enq->pro_enq_no;
}
//jeon_end seq=77
if ($enquete_id == '')
	redirect('pe_index.php');
//jeon_start seq=77
//$sql = "SELECT en_enq_type,en_title,en_start_date,en_end_date,en_point,en_status FROM t_enquete WHERE en_enquete_id=$enquete_id";
$sql = "SELECT en_title,en_start_date,en_end_date,en_point,en_status,en_enq_kind FROM t_enquete WHERE en_enquete_id=$enquete_id";
//jeon_end seq=77
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->en_title);
	$start_date = format_date($fetch->en_start_date);
	$end_date = format_datetime($fetch->en_end_date);
	$status = decode_enq_status($fetch->en_status);
} else
	redirect('pe_index.php');

// 利用ネットワークの取得	2006/02/27 BTI
$pro_enq_target = new pro_enq_target_class;
$pro_enq_target->read_db($enquete_id);
$target_flg = $pro_enq_target->target_flg;

if ($target_flg == 't') {
	$sql = "SELECT COUNT(*) FROM t_enquete_list JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id WHERE el_enquete_id=$enquete_id AND an_valid_flag";
} else {
	$sql = "SELECT COUNT(*) FROM t_answer JOIN g_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id WHERE an_enquete_id=$enquete_id AND an_valid_flag";
}
$recv_count1 = db_fetch1($sql);

if ($target_flg == 't') {
	$sql = "SELECT COUNT(*) FROM t_enquete_list JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id WHERE el_enquete_id=$enquete2_id AND an_valid_flag";
} else {
	$sql = "SELECT COUNT(*) FROM t_answer JOIN g_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id WHERE an_enquete_id=$enquete2_id AND an_valid_flag";
}
$recv_count2 = db_fetch1($sql);

$appearance_ratio = $recv_count1 ? round($recv_count2 / $recv_count1 * 100) : 0;
?>
<? marketer_header('プロジェクト詳細表示', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_endenqdet1.gif" alt="" width="400" height="20" /><br>
		  <br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
		<td>
			<table width="600" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<b><?=$title?></b>についてのアンケートの詳細です。
					</td>
					<td align="right">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2"><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
				</tr>
				<tr>
					<td align="right" colspan="2">&nbsp;</td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>アンケート名</th>
					<td width="70%" align="center">
						<?=$title?>					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>開始日</th>
					<td width="70%" align="center">
						<?=$start_date?>					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>終了日</th>
					<td width="70%" align="center">
						<?=$end_date?>					</td>
				</tr>
<?
if ($finding_flag == DBTRUE) {
?>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>ファインディング回答数（出現率）</th>
					<td width="70%" align="center">
						<a href="pe_cell_count.php?pro_enq_no=<?=$pro_enq_no?>"><?=number_format($recv_count1)?></a> (<?=$appearance_ratio?>%) <a href="pe_enq_profile.php?pro_enq_no=<?=$pro_enq_no?>"></a>					</td>
				</tr>
<?
}
?>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>本アンケート回答数（設定値）</th>
					<td width="70%" align="center">
						<a href="pe_recv_count_detail.php?pro_enq_no=<?=$pro_enq_no?>"><?=number_format($recv_count2)?></a> / <?=number_format($sample_num)?> <a href="pe_enq_rmail.php?pro_enq_no=<?=$pro_enq_no?>"></a>					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>対象者条件</th>
					<td width="70%" align="center">

						<a href="pe_enq_pollee.php?search_id=<?=$search_id?>">対象者条件</a>					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" nowrap>状態</th>
					<td width="70%" align="center">
						<?=$status?>					</td>
				</tr>
				<tr>
				  <td align="center" bgcolor="#eeeeee" nowrap>調査票                
				  <td align="center"><a href="pe_webprev.php?pro_enq_no=<?=$pro_enq_no?>">調査票</a></td>
			  </tr>
			</table>
			<br><br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee">リアルタイム集計結果</td>
					<td width="70%" colspan="2">

							　<a href="pe_resdet_gt.php?pro_enq_no=<?=$pro_enq_no?>">全体</a>　｜<a href="pe_resdet_sex.php?pro_enq_no=<?=$pro_enq_no?>">性別</a>　｜　<a href="pe_resdet_age.php?pro_enq_no=<?=$pro_enq_no?>">年代</a>　｜　<a href="pe_resdet_sex_age.php?pro_enq_no=<?=$pro_enq_no?>">性別×年代</a> ｜</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee">回答データ</td>
					<td width="40%">・設問csv<br />
				    ・回答＋回答者属性csv</td>
					<td width="30%" align="center">
						<form method="post" action="pe_csv_zip.php">
						<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
						<input type="submit" value="　ダウンロード　">
						</form>
					</td>
				</tr>
			</table>
			<br>
<?
if ($pro_enq_no) {
?>
<!-- add enqview -->
<div align="center"><br />
    <input name="button" type="button" onclick="location.href='pe_index.php'" value="　戻る　" />
</div>
<!-- //add enqview -->
<?
}
?>		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
